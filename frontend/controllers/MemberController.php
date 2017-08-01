<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use Yii;
use frontend\models\Member;
use frontend\models\Address;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\web\Cookie;
use yii\web\Request;

class MemberController extends Controller
{
    public $layout=false;
    public $enableCsrfValidation=false;
    //登录
    public function actionLogin()
    {
        $model = new Member(['scenario'=>Member::SCENARIO_LOGIN]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($model->login()){
                \Yii::$app->session->setFlash('success','登陆成功！');
                return $this->redirect(['member/address']);
            }
        }
        return $this->render('login', ['model' => $model]);
    }
    //退出
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['index']);
    }
    //列表
    public function actionIndex()
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航分类
        return $this->render('index',['nav'=>$nav]);
    }
    public function actionGoods($id)
{
    $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航分类
    $model=Goods::findOne(['id'=>$id]);//s商品数据
    //左侧相关分类列表
    $model1=GoodsCategory::findOne(['id'=>$model['goods_category_id']]);//查询商品分类 3
    $father=GoodsCategory::findOne(['id'=>$model1['parent_id']]);//查询商品父分类 2
    $fathers=GoodsCategory::findOne(['id'=>$father['parent_id']]);//查询商品爷分类 1
    $model1s=GoodsCategory::find()->where(['parent_id'=>$model1['parent_id']])->all();//查询该商品分类父ID下的同级分类 2
//    var_dump($model1s);exit;
    $picture=GoodsGallery::find()->where(['goods_id'=>$id])->all();//商品图片
    $intro=GoodsIntro::findOne(['goods_id'=>$id]);//商品详情
    return $this->render('goods',['nav'=>$nav,'model'=>$model,'model1s'=>$model1s,'model1'=>$model1,'fathers'=>$fathers,'picture'=>$picture,'intro'=>$intro]);
}
    public function actionList($category_id)
{
    $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航分类
    $model1s=GoodsCategory::findOne(['id'=>$category_id]);//左侧分类列表
        //3级分类  叶子分类
    if($model1s->depth==2){
        $models = Goods::find()->where(['goods_category_id'=>$category_id])->all();
    }else{
        //1 2级分类
        $ids = $model1s->leaves()->asArray()->column();
        $models = Goods::find()->where(['in','goods_category_id',$ids])->all();
    }
    return $this->render('list',['nav'=>$nav,'model1s'=>$model1s,'models'=>$models]);
}
    //添加到购物车成功页面
    public function actionAddToCart($goods_id,$amount)
    {
        //未登录
        if(Yii::$app->user->isGuest){
            //商品id  商品数量
            //如果没有登录就存放在cookie中
            $cookies = Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，数量累加
                    $carts[$goods_id] += $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //用户已登录，操作购物车数据表
        }

        return $this->redirect(['cart']);
    }
    //购物车页面
    public function actionCart()
    {
        $this->layout = false;
        //1 用户未登录，购物车数据从cookie取出
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //var_dump(unserialize($cookies->getValue('cart')));
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cart->value);
            }
            //$carts=[1=>99,2=>1]   []    =>array_keys($carts)  => [1,2]
            //获取商品数据
            $models = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
        }else{
            //2 用户已登录，购物车数据从数据表取
        }

        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }
    //修改购物车数据
    public function actionAjaxCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        //数据验证

        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }
    }
    //注册-----表单
    public function actionRegister1()
    {
//        $this->layout = 'login';
        $model = new Member(['scenario'=>Member::SCENARIO_REGISTER]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['member/login']);
        }
        return $this->render('register1', ['model' => $model]);

    }
    //注册---ajax
    public function actionRegister()
    {
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        return $this->render('register',['model'=>$model]);
    }//AJAX表单验证
    public function actionAjaxRegister()
    {
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        if($model->load(\Yii::$app->request->post()) && $model->validate() ){
            $model->save(false);
            //保存数据，提示保存成功
            return Json::encode(['status'=>true,'msg'=>'注册成功']);
        }else{
            //验证失败，提示错误信息
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }
    //定义验证码操作
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }
    //用户地址
    public function actionAddress()
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航商品分类
        $model1s = Address::find()->all();//地址展示
        $model = new Address();
        $request = new Request();
        //开始验证数据
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                $model->member_id = \Yii::$app->user->getId();
                $model->save();
                return $this->redirect(['address']);
            }else{
//               var_dump($model->getErrors());exit;
            }
        }
        return $this->render('address',['nav'=>$nav,'model1s'=>$model1s]);
    }

    public function actionAjaxAddress()
    {
        $adds = new AddressForm();
        if($adds->load(\Yii::$app->request->post()) && $adds->validate() ){
            $adds->save(false);
            //保存数据，提示保存成功
            return Json::encode(['status'=>true,'msg'=>'保存成功']);
        }else{
            //验证失败，提示错误信息
            return Json::encode(['status'=>false,'msg'=>$adds->getErrors()]);
        }
    }

}
