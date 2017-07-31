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
use yii\web\ForbiddenHttpException;
use yii\db\Exception;
/**
 * GoodsCategoryController implements the CRUD actions for GoodsCategory model.
 */
class GoodsCategoryController extends BaseController
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
        $models=GoodsCategory::find()->orderBy('tree ,lft ')->asArray()->all();
        //分页工具
         /*$pager= new Pagination(
             [
                 //总条数
                 'totalCount'=>$query->count(),
                 //每页显示条数
                 'defaultPageSize'=>3
             ]
         );*/
//         $models = $query->limit($pager->limit)->offset($pager->offset)->all();
         //分配数据
//         return $this->render('index',['models'=>$models,'pager'=>$pager]);
         return $this->render('index',['models'=>$models]);
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
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try{
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
                    //bug fix:修复根节点修改为根节点的bug
                    if($model->oldAttributes['parent_id']==0){//post之前的数据oldAttributes
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','分类修改成功');
                return $this->redirect(['index']);
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }
        }
        //获取所以分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //删除
    public function actionDelete($id)
    {
        //判断该分类下是否有子分类
        $model =GoodsCategory::find()->where(['parent_id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品分类不存在');
        }
        //方法一
        $count = $model->count();
        if($count > 0){
            throw new ForbiddenHttpException('该分类下有子分类,不能删除!');
        }
        //方法二
        /*if(!$model->isLeaf()){//判断是否是叶子节点，非叶子节点说明有子分类
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }*/
//        $this->findModel($id)->delete();
        $model->deleteWithChildren();
        \Yii::$app->session->setFlash('success','删除成功');
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
