<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Yii;
use frontend\models\Member;
use frontend\models\Address;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\db\Exception;
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
        if(!Yii::$app->user->isGuest){
            return $this->goBack('index');
        };
        $model = new Member(['scenario'=>Member::SCENARIO_LOGIN]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($model->login()){
                //登录后把cookie中数据写入数据表
            $cookies = Yii::$app->request->cookies;
            //---获取cookie中的购物车商品数据----------------
            $cookie_cart = $cookies->get('cart');
            if($cookie_cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cookie_cart->value);
            }
            //--循环遍历cookie购物车数据------
                foreach($carts as $goods_id=>$amount){
                    //---查询数据库该用户名下是否有该商品-----------
                    $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
                    if($cart){
                        //----如果数据表已经有这个商品,就合并cookie中的数量
                        $cart->amount+=$amount;
                        $cart->save();
                    }else{
                        //----如果数据表没有这个商品,就添加这个商品到购物车表
                        $cart=new Cart();
                        $cart->amount=$amount;
                        $cart->goods_id=$goods_id;
                        $cart->member_id=Yii::$app->user->id;
                        $cart->save();
                    }
                }
                //---同步完后，清空cookie购物车数据----调用cookie对象的删除方法--
                Yii::$app->response->cookies->remove('cart');
                return $this->redirect(['member/index']);
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
            $models = Goods::find()->where(['in','goods_category_id',$ids])->all();
            /*$query = Goods::find()->where(['in','goods_category_id',$ids]);
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
            $models = $query->limit($pager->limit)->offset($pager->offset)->all();*/

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
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->id])->column());
//            var_dump($carts);exit;
        }
        return $this->render('list',['nav'=>$nav,'model1s'=>$model1s,'models'=>$models,/*'model'=>$model,'pager'=>$pager,*/'carts'=>$carts]);
    }
    //商品详情
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
            $carts=array_sum(Cart::find()->select('amount')->where(['member_id'=>Yii::$app->user->id])->column());
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
            //---------查询数据表是否已有该商品---------
            $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
            if($cart==null){
                //---没有 添加数据到数据表------------
                $cart=new Cart();
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=Yii::$app->user->id;
                $cart->insert();
                $cart->save();
            }else{
                //---已有该商品数据 修改数据到数据表------------
                $cart->updateall(['amount'=>$cart['amount']+$amount],['id'=>$cart['id']]);
                $cart->save();
            }
        }
        return $this->redirect(['cart']);
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
            //----------已登录--从数据表取出商品数据------------
            //用户已登录，购物车数据从数据表取
            //根据用户ID查询cart表中用户的所有goods_id数据----为数组
            $goods_ids=Cart::find()->select('goods_id')->where(['member_id'=>Yii::$app->user->id])->asArray()->column();
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
            //$cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
            //---已有该商品数据 修改数据到数据表------------
//            var_dump($cart);exit;
            /*$cart->amount=$amount;
            $cart->save();*/
            Cart::updateAll(['amount'=>$amount],['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
            return 'success';
        }
    }
    //删除购物车数据
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
            Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id])->delete();
        };
        return $this->redirect(['cart']);
    }
    //订单
    public function actionOrder(){
        $model = new Order();
        //---开启事务----
        $transaction = Yii::$app->db->beginTransaction();
        if(\Yii::$app->request->post() && $model->validate()) {
            try {
                $model->member_id = Yii::$app->user->id;//用户ID
                $delivery_id =\Yii::$app->request->post('delivery_id');//送货方式id
                $address_id =\Yii::$app->request->post('address_id');//收货地址id
                $payment_id =\Yii::$app->request->post('payment_id');//支付方式id
                $model->create_time=time();//创建时间
                //----收货人信息------
                $address=Address::findOne(['id'=>$address_id]);
                //根据$model->address_id 从地址表获取以下数据，并赋值给订单相应字段
                $model->name = $address-> name;//收货人
                $model->province = $address->province;//省
                $model->city = $address->city;//市
                $model->area = $address->area;//县
                $model->address = $address->detailed_address;//详细地址
                $model->tel = $address->tel;//电话号码
                //-------配送方式---
                $model->delivery_id=$delivery_id;
                $model->delivery_name = Order::$deliveries[$delivery_id]['name'];
                $model->delivery_price = Order::$deliveries[$delivery_id]['price'];
                //---支付方式-----
                $model->payment_id=$payment_id;
                $model->payment_name = Order::$payments[$payment_id]['name'];
                 //订单状态
                $model->status=1;
                $model->save(false);
                //（检查库存，如果足够）保存订单商品表
                //检查库存：购物车商品的数量和商品表库存对比，足够
                //---获取购物车数据----
                $carts=Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
                $total=0;
                foreach ($carts as $cart) {
                    $goods = Goods::findOne(['id' => $cart->goods_id]);
                    $order_goods = new OrderGoods();
                    //--购物车商品数量《--》商品库存-------
                    if ($cart->amount <= $goods->stock) {
                        //$order_goods的其他属性赋值
                        $order_goods->order_id = $model->id;//订单id
                        $order_goods->goods_id = $goods->id;//商品id
                        $order_goods->goods_name = $goods->name;//商品名称
                        $order_goods->logo = $goods->logo;//图片
                        $order_goods->price = $goods->shop_price;//价格
                        $order_goods->amount = $cart->amount;//数量
                        $order_goods->total = $cart->amount * $goods->shop_price;//小计
                        $order_goods->save(false);
                        //扣减对应商品的库存
                        $goods->stock=$goods->stock -$cart->amount;
                        $goods->save(false);
                        //所有商品的金额
                        $total+=$order_goods->total;
                    } else {
                        //（检查库存，如果不够）
                        //抛出异常
                        throw new Exception('商品库存不足，无法继续下单，请修改购物车商品数量');
                    }
                }
                //下单成功后清除购物车
                Cart::deleteAll(['member_id'=>Yii::$app->user->id]);
                //order表的总金额 加上邮寄的金额
                $model->total=$total+Order::$deliveries[$delivery_id]['price'];
                $model->update(false,['total']);
                //提交事务
                $transaction->commit();
                return 'success';
            } catch (Exception $e) {
                //回滚
                $transaction->rollBack();
            }
        }
        //根据用户ID查询cart表中用户的所有goods_id数据----为数组
        $goods_ids=Cart::find()->select('goods_id')->where(['member_id'=>Yii::$app->user->id])->asArray()->column();
        $num = Cart::find()->where(['member_id'=>Yii::$app->user->id])->sum('amount');//商品总数
        //查询数组中商品ID的商品数据
        $goods=Goods::find()->where(['in','id',$goods_ids])->all();
        $address = Address::find()->where(['member_id'=>Yii::$app->user->id])->all();//地址数据
        //总金额
        $prices=0;
        foreach ($goods as $good ) {
            $prices += $good['shop_price'] * $good->amount['amount'];
        }
        return $this->render('order',['goods'=>$goods,'address'=>$address,'num'=>$num,'prices'=>$prices]);
    }
    public function actionOrder1()
    {
        return $this->render('order1');
    }
    public function actionOrder2()
    {
        return $this->render('order2');
    }
    public function actionOrder3()
    {
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
        return $this->render('order3',['carts'=>$carts]);
    }
    //用户地址
    public function actionAddress()
    {
        $nav=GoodsCategory::find()->where(['depth'=>0])->all();//导航商品分类
        $model1s = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();//收货地址展示
        $model = new Address();
        $request = new Request();
        //开始验证数据
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                if(\Yii::$app->user->isGuest){
                    return $this->redirect(['login']);
                }else{
                    $model->member_id = \Yii::$app->user->Id;
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
    //修改地址
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
    //删除地址
    public function actionDelAddress($id){
        Address::findOne(['id'=>$id])->delete();
        return $this->redirect(['address']);
    }
    //设置默认地址
    public function actionStatusAddress($id){
        Address::updateall(['status'=>1],['id'=>$id]);
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
    }
    //AJAX表单验证
    public function actionAjaxRegister()
    {
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        if($model->load(\Yii::$app->request->post()) && $model->validate() ){
            $tel =$model->tel;
            $smsCode =$model->smsCode;
            $code = \Yii::$app->session->get('code_'.$tel);
            if($code == $smsCode){
                $model->save(false);
                //保存数据，提示保存成功
                return Json::encode(['status'=>true,'msg'=>'注册成功']);
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
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
    //短信验证码
    public function actionSmsCode()
    {
        $tel =\Yii::$app->request->post('tel');
        $code = rand(1000,9999);
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
    }
}
