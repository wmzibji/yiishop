<?php
namespace backend\controllers;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\SignupForm;
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    //登录
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    //退出
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
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
    //详情
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    //添加
    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    //注册
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goBack();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    //修改
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', ['model' => $model,]);
        }
    }

    //删除
    public function actionDelete($id)
    {
    /*    $this->findModel($id)->delete();
        return $this->redirect(['index']);*/
        $model=$this->findModel($id);
        $model->updateall(['status'=>0],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('index'));
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在.');
        }
    }
}
