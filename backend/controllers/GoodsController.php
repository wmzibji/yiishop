<?php

namespace backend\controllers;

use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use Yii;
use backend\models\Goods;
use yii\captcha\CaptchaAction;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends Controller
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
    public function actionIndex()
    {
        //搜索
        $model = new GoodsSearchForm();
        $query = Goods::find()->where(['!=','status','0']);
        //接收表单提交的查询参数
        $model->search($query);
        //分页工具
        $pager= new Pagination(
            [
                //总条数
                'totalCount'=>$query->count(),
                //每页显示条数
                'defaultPageSize'=>8
            ]
        );
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['models'=>$models,'pager'=>$pager,'model'=>$model]);
    }
    //回收站
    public function actionRecycle()
    {
        //搜索
        $model = new GoodsSearchForm();
        $query = Goods::find()->where(['=','status','0']);
        //接收表单提交的查询参数
        $model->search($query);
        //分页工具
        $pager= new Pagination(
            [
                //总条数
                'totalCount'=>$query->count(),
                //每页显示条数
                'defaultPageSize'=>8
            ]
        );
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('recycle',['models'=>$models,'pager'=>$pager,'model'=>$model]);
    }
    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    //添加
    public function actionAdd()
    {
        $model = new Goods();
        $model1 = new GoodsIntro();//文章详情
        if(     $model->load(\Yii::$app->request->post()) && $model->validate()
            &&  $model1->load(\Yii::$app->request->post()) && $model1->validate()
        ){
            $model1->goods_id=($model->id);
            //sn货号
            $day=date('Y-m-d',time());
            $DayCount=GoodsDayCount::findOne(['day'=>$day]);
            if($DayCount==null) {
                $DayCount = new GoodsDayCount();//货号
                $DayCount->day=$day;
                $DayCount->count=0;
                $DayCount->save();
            }/*else{
                $count=$query['count']+1;
//                $model3->updateall(['count'=>$count],['day'=>$day]);
                $model3->update(['count'=>$count]);
                $model->sn=date('Ymd',time()).'000'.$count;
            }*/
            $model->create_time=time();
            $model->sn=date('Ymd',time()).sprintf("%04d",$DayCount->count+1);
            $model->save();
            $model1->save();
            $DayCount->count++;
            $DayCount->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }

    //编辑
    public function actionEdit($id)
    {
        $model = Goods::findOne(['id'=>$id]);
//        $model1 = $model->goodsIntro;
        $model1 = GoodsIntro::findOne(['goods_id'=>$id]);
        if(     $model->load(\Yii::$app->request->post()) && $model->validate()
            &&  $model1->load(\Yii::$app->request->post()) && $model1->validate()
        ){
            $model->save();
            $model1->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('edit',['model'=>$model,'model1'=>$model1]);
    }

    //删除
    public function actionDelete($id)
    {
        $model=Goods::findOne(['id'=>$id]);
        $model->updateall(['status'=>0],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('index'));
    }
    //回收站还原
    public function actionReduction($id)
    {
        $model=Goods::findOne($id);
        $model->updateall(['status'=>1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据还原成功！');
        return $this->redirect(array('recycle'));
    }
    //图片
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //验证码  ajax上传
    public function actions() {
        return [
            //图片上传
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filename = sha1_file($action->uploadfile->tempName);
                     return "{$filename}.{$fileext}";
                 },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']  = $url;
                },
            ],
        ];
    }
}
