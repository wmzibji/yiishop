<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use frontend\models\Cart;
use Yii;
use frontend\models\Member;
use frontend\models\Address;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
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
    //主页
    public function actionIndex()
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航分类
        //-----查询购物车数据------判断是否登录----------
        if(Yii::$app->user->isGuest){
            //---没登录---cookie中查询数据--是否有商品--------
            $cart=Yii::$app->request->cookies->get('cart');
            if($cart==null){
                $carts=[];//
            }else{
//                $carts = unserialize($cart->value);
                //统计商品总数
                $carts=array_sum(unserialize($cart->value));
//                var_dump($carts);exit;
            } ;
        }else{
            //---登录---cart表中查询数据----------
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->getId()])->column());
//            var_dump($carts);exit;
        }
        return $this->render('index',['nav'=>$nav,'carts'=>$carts]);
    }
    //商品列表
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
            $query = Goods::find()->where(['in','goods_category_id',$ids]);
            $model = new GoodsSearchForm();
            $model->search($query);
            //分页工具
            $pager= new Pagination(
                [
                    //总条数
                    'totalCount'=>$query->count(),
                    //每页显示条数
                    'defaultPageSize'=>2
                ]
            );
            $models = $query->limit($pager->limit)->offset($pager->offset)->all();

        }
        //-----查询购物车数据------判断是否登录----------
        if(Yii::$app->user->isGuest){
            //---没登录---cookie中查询数据--是否有商品--------
            $cart=Yii::$app->request->cookies->get('cart');
            if($cart==null){
                $carts=[];//
            }else{
//                $carts = unserialize($cart->value);
                //统计商品总数
                $carts=array_sum(unserialize($cart->value));
//                var_dump($carts);exit;
            } ;
        }else{
            //---登录---cart表中查询数据----------
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->getId()])->column());
//            var_dump($carts);exit;
        }
        return $this->render('list',['nav'=>$nav,'model1s'=>$model1s,'models'=>$models,'model'=>$model,'pager'=>$pager,'carts'=>$carts]);
    }
    //商品
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
        //-----查询购物车数据------判断是否登录----------
        if(Yii::$app->user->isGuest){
            //---没登录---cookie中查询数据--是否有商品--------
            $cart=Yii::$app->request->cookies->get('cart');
            if($cart==null){
                $carts=[];//
            }else{
//                $carts = unserialize($cart->value);
                //统计商品总数
                $carts=array_sum(unserialize($cart->value));
//                var_dump($carts);exit;
            } ;
        }else{
            //---登录---cart表中查询数据----------
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->getId()])->column());
//            var_dump($carts);exit;
        }
        return $this->render('goods',['nav'=>$nav,'model'=>$model,'model1s'=>$model1s,'model1'=>$model1,'fathers'=>$fathers,'picture'=>$picture,'intro'=>$intro,'carts'=>$carts]);
    }
    //添加到购物车
    public function actionAddToCart($goods_id,$amount)
    {
        //----------未登录 ---商品id  商品数量---保存到cookie里面--------------
        //----已登录--保存到数据表------------
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
                //---获取cookie中的购物车商品数据----------------
            $cart = $cookies->get('cart');
                //--------cookie为空---写入------
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                //--------cookie不为空---重写----为数组[1=>1，2=>1]----------------
                $carts = unserialize($cart->value);
                if(isset($carts[$goods_id])){
                    //----已有该商品，数量累加-------------
                    $carts[$goods_id] += $amount;
                }else{
                    //----没有该商品，写入-------------
                    $carts[$goods_id] = $amount;
                }
            }
            //---将商品id和商品数量写入cookie-----------
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //----用户已登录，操作购物车数据表---------

            /*//登录后把cookie中数据写入数据表
            $cookies = Yii::$app->request->cookies;
            //---获取cookie中的购物车商品数据----------------
            $cookie_cart = $cookies->get('cart');
            if($cookie_cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cookie_cart->value);
                $goods_ids=array_keys($carts);
                $amounts=array_values($carts);
                var_dump($goods_ids);echo 'br';
                var_dump($amounts);exit;
            }*/


            //---------查询数据表是否已有该商品---------
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            if($cart==null){
                //---没有 添加数据到数据表------------
                $cart=new Cart();
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=Yii::$app->user->getId();
                $cart->insert();
                $cart->save();
            }else{
                //---已有该商品数据 修改数据到数据表------------
                $cart->updateall(['amount'=>$cart['amount']+$amount],['id'=>$cart['id']]);
                $cart->save();
            }
        }
//        return $this->redirect(['cart']);
    }
    //购物车
    public function actionCart()
    {
        $this->layout = false;
        //----------未登录 ---从cookie取出商品数据--------------
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cart->value);
            };
            //获取商品数据
            $models = Goods::find()->where(['in','id',array_keys($carts)])->asArray()->all();
            return $this->render('cart',['models'=>$models,'carts'=>$carts]);
        }else{
           /* //登录后把cookie中数据写入数据表
            $cookies = Yii::$app->request->cookies;
            //---获取cookie中的购物车商品数据----------------
            $cookie_cart = $cookies->get('cart');
            if($cookie_cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cookie_cart->value);
                $goods_ids=array_keys($carts);
                $amounts=array_values($carts);
            }*/


            //----------已登录--从数据表取出商品数据------------
            //用户已登录，购物车数据从数据表取
            //根据用户ID查询cart表中用户的所有goods_id数据----为数组
            $goods_ids=Cart::find()->select('goods_id')->where(['member_id'=>Yii::$app->user->getId()])->asArray()->column();
            //查询数组中商品ID的商品数据
            $models=Goods::find()->where(['in','id',$goods_ids])->all();
            //商品数量在商品模型中建立get方法
            return $this->render('cart',['models'=>$models]);
        };
    }
    //修改购物车数据
    public function actionAjaxCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        //数据验证
        //----------未登录 ---商品id  商品数量---保存到cookie里面--------------
        //----已登录--保存到数据表------------
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //---获取cookie中数据---------
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    $carts[$goods_id] = $amount;
                }else{
                    $carts[$goods_id] = $amount;
                }
            }
            //---将商品id和商品数量写入cookie-----------
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }else {
            //用户已登录，操作购物车数据表
            //---------查询数据表是否已有该商品---------
            $cart=Cart::findOne(['goods_id'=>$goods_id]);
            //---已有该商品数据 修改数据到数据表------------
            $cart->updateall(['amount'=>$amount],['id'=>$cart['id']]);
            $cart->save();
            return 'success';
        }
    }
    public function actionDelCart($goods_id)
    {
        //--------用户未登录-----修改cookie数据----------
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies/*->remove()*/;
            $cart = $cookies->get('cart');
            $carts = unserialize($cart->value);//[1=>99，2=》1]
            //删除cookie数组中键为$goods_id的数据
            unset($carts[$goods_id]);
            //---将商品id和商品数量写入cookie-----------
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
        }else{
            //--------用户已登录-----修改数据表数据----------
            Cart::findOne(['goods_id'=>$goods_id])->delete();
        };
        return $this->redirect(['cart']);
    }
    //用户地址
    public function actionAddress()
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航商品分类
        $model1s = Address::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();//收货地址展示
        $model = new Address();
        $request = new Request();
        //开始验证数据
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                if(\Yii::$app->user->isGuest){
                    return $this->redirect(['login']);
                }else{
                    $model->member_id = \Yii::$app->user->getId();
                    $model->save();
                    return $this->redirect(['address']);
                }
            }else{
//               var_dump($model->getErrors());exit;
            }
        }
        //-----查询购物车数据------判断是否登录----------
        if(Yii::$app->user->isGuest){
            //---没登录---cookie中查询数据--是否有商品--------
            $cart=Yii::$app->request->cookies->get('cart');
            if($cart==null){
                $carts=[];//
            }else{
//                $carts = unserialize($cart->value);
                //统计商品总数
                $carts=array_sum(unserialize($cart->value));
//                var_dump($carts);exit;
            } ;
        }else{
            //---登录---cart表中查询数据----------
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->getId()])->column());
//            var_dump($carts);exit;
        }
        return $this->render('address',['nav'=>$nav,'model1s'=>$model1s,'model'=>$model,'carts'=>$carts]);
    }
    public function actionEditAddress($id)
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航商品分类
        $model1s = Address::find()->where(['member_id'=>\Yii::$app->user->getId()])->all();//收货地址展示
        $model = Address::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                $model->save();
                $model->status=0;
                return $this->redirect(['address']);
            }else{
//               var_dump($model->getErrors());exit;
            }
        }
        //-----查询购物车数据------判断是否登录----------
        if(Yii::$app->user->isGuest){
            //---没登录---cookie中查询数据--是否有商品--------
            $cart=Yii::$app->request->cookies->get('cart');
            if($cart==null){
                $carts=[];//
            }else{
//                $carts = unserialize($cart->value);
                //统计商品总数
                $carts=array_sum(unserialize($cart->value));
//                var_dump($carts);exit;
            } ;
        }else{
            //---登录---cart表中查询数据----------
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->getId()])->column());
//            var_dump($carts);exit;
        }
        return $this->render('address',['nav'=>$nav,'model'=>$model,'model1s'=>$model1s,'carts'=>$carts]);
    }
    public function actionDelAddress($id){
        Address::findOne(['id'=>$id])->delete();
        return $this->redirect(['address']);
    }
    public function actionStatusAddress($id){
        $model=Address::findOne(['id'=>$id]);
        $model->updateall(['status'=>1],['id'=>$id]);
        $model->save();
        return $this->redirect(['address']);
    }
    //注册--------表单
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

//            $smsCode = rand(1000,9999);
//            $tel = $model->tel;
//            $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$smsCode])->send();
            //将短信验证码保存redis（session，mysql）
//            \Yii::$app->session->set('smsCode_'.$tel,$smsCode);
//            //验证
//            $code2 = \Yii::$app->session->get('smsCode_'.$tel);
//            if($model->smsCode == $smsCode){

                $model->save(false);
                //保存数据，提示保存成功
                return Json::encode(['status'=>true,'msg'=>'注册成功']);
//            }


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
                'minLength'=>2,
                'maxLength'=>4,
            ]
        ];
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
    //测试发送短信功能
    public function actionTestSms()
    {

        $code = rand(1000,9999);
        $tel = '18828098518';
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
        //验证
        $code2 = \Yii::$app->session->get('code_'.$tel);
        if($code == $code2){

        }

    }
}
