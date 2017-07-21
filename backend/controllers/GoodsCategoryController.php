<?php

namespace backend\controllers;

use Yii;
use backend\models\GoodsCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\data\Pagination;
/**
 * GoodsCategoryController implements the CRUD actions for GoodsCategory model.
 */
class GoodsCategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    //列表
    public function actionIndex1()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => GoodsCategory::find(),
        ]);

        return $this->render('index1', [
            'dataProvider' => $dataProvider,
        ]);
    }
    //列表
    public function actionIndex()
    {
        $query=GoodsCategory::find()/*->where(["name like '%{$keywords}%'"])->orderBy('sort desc,id desc')*/;
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

    //添加
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

    //编辑
    public function actionEdit($id)
    {
        $model =GoodsCategory::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类修改成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
 /*   public function actionEdit1($id,$parent_id,$name)
    {
        $model =GoodsCategory::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //修改到的分类下面,不能出现同名的分类,但是可以和自己同名
            $model1 =GoodsCategory::find()->where('and',['parent_id'=>$parent_id,'name'=>$name,'id'<>$id]);
            $count = $model1->count($model1);
            if($count > 0){
                throw new HttpException("修改到的分类下面,不能出现同名的分类,但是可以和自己同名!") ;
            }
            //不能修改到自己分类下面,并且不能修改到自己的子孙分类下面
            //parent_id 不能等于自己的id和子孙的id
            //准备一个数组,用于装所有不能的id
            $ids = [];
            //获取子孙的id
            $children = GoodsCategory::find()->where(['id'=>$id])->asArray();
            $ids = $children['id'];
            //不能是自己的id
            $ids[] = $id;
            if(in_array($parent_id,$ids)){
                throw new HttpException("不能修改到自己分类下面,并且不能修改到自己的子孙分类下面");
            }

            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }*/

    //删除
    public function actionDelete($id)
    {
        //判断该分类下是否有子分类
        $model =GoodsCategory::find()->where(['parent_id'=>$id]);
        $count = $model->count();
        if($count > 0){
            throw new HttpException(404,'该分类下有子分类,不能删除!');
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
