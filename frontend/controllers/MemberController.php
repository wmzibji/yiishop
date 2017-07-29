<?php
namespace frontend\controllers;
use Yii;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
class MemberController extends Controller
{
    public $layout=false;
    public $enableCsrfValidation=false;
    //登录
    public function actionLogin()
    {
        $model = new Member(['scenario'=>Member::SCENARIO_LOGIN]);//指定当前场景
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($model->login()){
                \Yii::$app->session->setFlash('success','登陆成功！');
                return $this->redirect(['index']);
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
    //注册
    public function actionRegister()
    {
        $model = new Member(['scenario'=>Member::SCENARIO_REGISTER]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['index']);
        } else {
            return $this->render('register', ['model' => $model]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在.');
        }
    }
    public function actionChangepw()
    {
        $model=new Changepw();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //
            \Yii::$app->session->setFlash('success','密码修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('changepw', ['model' => $model,]);
    }
}
