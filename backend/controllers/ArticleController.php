<?php

namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
class ArticleController extends \yii\web\Controller
{
    //列表
    public function actionIndex()
    {
        $query=Article::find()->where(['!=','status','-1']);
        //分页工具
        $pager= new Pagination(
            [
                //总条数
                'totalCount'=>$query->count(),
                //每页显示条数
                'defaultPageSize'=>3
            ]
        );
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //回收站
    public function actionRecycle()
    {
        $query=Article::find()->where(['=','status','-1']);
        //分页工具
        $pager= new Pagination(
            [
                //总条数
                'totalCount'=>$query->count(),
                //每页显示条数
                'defaultPageSize'=>3
            ]
        );
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('recycle',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd()
    {
        $model = new Article();
        $model1 = new ArticleDetail();
        if($model->load(\Yii::$app->request->post()) && $model->validate() &&$model1->load(\Yii::$app->request->post()) && $model1->validate()){
            $model->create_time=time();
            $model->save();
            $model1->save();
            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['article/index']);
        }
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }
    //编辑
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$id]);
        if($model==null){//如果品牌不存在，则显示404页面
            throw new NotFoundHttpException('文章不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate() && $model1->load(\Yii::$app->request->post()) && $model1->validate()){
            $model->save();
            $model1->save();
            \Yii::$app->session->setFlash('success','文章修改成功');
            return $this->redirect(['article/index']);
        }
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }
    //验证码
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>2,
                'maxLength'=>4,
            ]
        ];
    }
    //删除
    public function actionDelete($id)
    {
        $model=Article::findOne($id);
        $model->updateall(['status'=>-1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('article/index'));
    }
    //回收站还原
    public function actionReduction($id)
    {
        $model=Article::findOne($id);
        $model->updateall(['status'=>1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据还原成功！');
        return $this->redirect(array('article/recycle'));
    }

}
