<?php
namespace frontend\controllers;
use Yii;
use frontend\models\Member;
use frontend\models\AddressForm;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\helpers\Json;
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
        return $this->render('index');
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
/*    public function actionAddress()
    {
        $indexs=AddressForm::find()->all();

        return $this->render('address',['indexs'=>$indexs]);
    }*/
/*    public function actionAdd()
    {
        $adds=new AddressForm();
        return $this->redirect('address',['adds'=>$adds]);
    }*/
    public function actionAddress()
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
