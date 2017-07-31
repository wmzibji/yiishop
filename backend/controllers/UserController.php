<?php
namespace backend\controllers;
use backend\models\Changepw;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
class UserController extends BaseController
{
    /**
     * @inheritdoc
     */
/*   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ]
                ]
            ]
        ];
    }*/
    /**
     * @inheritdoc
     */
/*    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }*/
    //登录
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new User(['scenario'=>User::SCENARIO_LOGIN]);//指定当前场景
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($model->login()){
                \Yii::$app->session->setFlash('success','登陆成功！');
//                return $this->redirect(['index']);
                return $this->goHome();
            }
        }
        return $this->render('login', ['model' => $model]);
    }
    //退出
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
    //列表
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);//带搜索
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //添加
    public function actionAdd()
    {
        $model = new User(['scenario'=>User::SCENARIO_ADD]);//指定当前场景为添加场景
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['index']);
        } else {
            return $this->render('add', ['model' => $model]);
        }
    }
    //修改
    public function actionEdit($id)
    {
        $model = $this->findModel($id);
//        if($model==null){
//            throw new NotFoundHttpException('该用户不存在');
//        }
        $model->scenario = User::SCENARIO_EDIT;//指定当期场景为修改场景
        //------------设置权限----------
        $model->roles = ArrayHelper::map(\Yii::$app->authManager->getRolesByUser($id),'name','description');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('edit', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
    /*    $this->findModel($id)->delete();
        return $this->redirect(['index']);*/
        $model=$this->findModel($id);
        if($model->status==0){
            throw new NotFoundHttpException('该账户已在回收站.');
        }else{
            $model->updateall(['status'=>0],['id'=>$id]);
            $model->save();
            \Yii::$app->session->setFlash('success','数据删除成功！');
            return $this->redirect(array('index'));
        }
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
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
