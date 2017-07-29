<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use yii\filters\AccessControl;
class BrandController extends \yii\web\Controller
{
/*    public function behaviors()
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
    //列表
    public function actionIndex()
    {
        $query=Brand::find()->where(['!=','status','-1']);
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
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //回收站
    public function actionRecycle()
    {
        $query=Brand::find()->where(['=','status','-1']);
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
        return $this->render('recycle',['models'=>$models,'pager'=>$pager]);
    }
    //添加
 /*   public function actionAdd(){
        //实例化表单模型
        $model = new Brand();
        $request = new Request();
        //判断请求方式
        if($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
            $model->logo = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //处理图片
                //有文件上传
                if($model->logo){
                    $d = \Yii::getAlias('@webroot').'/upload/brand/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $fileName = '/upload/brand/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //创建文件夹
                    $model->logo->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo = $fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success','数据添加成功！');
                return $this->redirect(['brand/index']);
                //保存并跳转
            }else{
                //验证失败 打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }*/
    public function actionAdd()
    {
        $model = new Brand();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','品牌添加成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //编辑
/*    public function actionEdit($id){
        //实例化表单模型
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        //判断请求方式
        if($request->isPost){
            $model->load($request->post());
            //实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //处理图片
                //有文件上传
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/brand/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $fileName = '/upload/brand/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //创建文件夹
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo = $fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success','数据修改成功！');
                return $this->redirect(['brand/index']);
                //保存并跳转
            }else{
                //验证失败 打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }*/
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        if($model==null){//如果品牌不存在，则显示404页面
            throw new NotFoundHttpException('品牌不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','品牌修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //验证码
/*    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>2,
                'maxLength'=>4,
            ]
        ];
    }*/
    //删除
    public function actionDelete($id)
    {
        $model=Brand::findOne($id);
        $model->updateall(['status'=>-1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('index'));
    }
    //回收站还原
    public function actionReduction($id)
    {
        $model=Brand::findOne($id);
        $model->updateall(['status'=>1],['id'=>$id]);
        $model->save();
        \Yii::$app->session->setFlash('success','数据还原成功！');
        return $this->redirect(array('recycle'));
    }
    //验证码  ajax上传
    public function actions() {
        return [
            //验证码
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>2,
                'maxLength'=>4,
            ],
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
                    //throw new Exception('test error');
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
    //测试七牛云文件上传
    public function actionQiniu()
    {
        $config = [
            'accessKey'=>'LZhuxBZMjJh1p_I5q0Hg_GknE6Tb1WBNKkWSjAIE',
            'secretKey'=>'9y4wo9yJm9gtF2sDrb8XUlxE_A3TTXK2bx2mdCUI',
            'domain'=>'http://otc0yp9zm.bkt.clouddn.com/',
            'bucket'=>'yii2shop',
            'area'=>Qiniu::AREA_HUADONG
        ];
        $qiniu = new Qiniu($config);
        $key = 'upload/brand/default.jpg';

        //将图片上传到七牛云
        $qiniu->uploadFile(
            \Yii::getAlias('@webroot').'/upload/brand/default.jpg',
            $key);
        //获取该图片在七牛云的地址
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}
