<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //列表
    public function actionIndex()
    {
        //总条数
        $total=Brand::find()->count();
        //每页显示条数
        $perPage=4;
        //分页工具
        $pager= new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$perPage
            ]
        );
        $models = Brand::find()->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
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
    }
    //编辑
    public function actionEdit($id){
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
        Brand::findOne($id)->delete();
        \Yii::$app->session->setFlash('success','数据删除成功！');
        return $this->redirect(array('brand/index'));
    }
    }
