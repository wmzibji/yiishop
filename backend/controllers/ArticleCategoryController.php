<?php
namespace backend\controllers;
use yii\web\Controller;
use backend\models\ArticleCategory;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
class ArticleCategoryController extends Controller
{
    //列表
    public function actionIndex()
    {
        $query=ArticleCategory::find()->where(['!=','status','-1']);
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
        $query=ArticleCategory::find()->where(['=','status','-1']);
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
    public function actionAdd(){
        //实例化表单模型
        $model = new ArticleCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','文章分类添加成功');
            return $this->redirect(['article-category/index']);
        }
    }
    //编辑
    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model==null){//如果不存在，则显示404页面
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','文章分类修改成功');
            return $this->redirect(['article-category/index']);
        }
        return $this->render('add',['model'=>$model]);
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
        $model=ArticleCategory::findOne($id);
        $model->updateall(['status'=>-1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('article-category/index'));
    }
    //回收站还原
    public function actionReduction($id)
    {
        $model=ArticleCategory::findOne($id);
        $model->updateall(['status'=>1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据还原成功！');
        return $this->redirect(array('article-category/recycle'));
    }
}