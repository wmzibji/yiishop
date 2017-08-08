<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;


class ApiController extends Controller
{
    //接口开发必须关闭
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    //1.会员
    //---会员注册
    public function actionMemberRegister()
    {
        if(\Yii::$app->request->isPost){
            $model = new Member();
            $model->username = \Yii::$app->request->post('username');
            $model->password = \Yii::$app->request->post('password');
            $model->tel = \Yii::$app->request->post('tel');
            $model->email = \Yii::$app->request->post('email');
            if($model->validate()){
                $model->save();
                //注册成功
                $result = [
                    'errorCode'=>1022,
                    'errorMsg'=>'注册成功',
                    'data'=>[]
                ];
            }else{
                //验证不通过
                $result = [
                    'errorCode'=>1021,
                    'errorMsg'=>'注册失败，请检测错误信息',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'errorCode'=>9999,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
//-会员登录

    public function actionMemberLogin()
    {
        if(\Yii::$app->request->isPost){
            $model = Member::findOne(['username'=>\Yii::$app->request->post('username')]);
            if($model){
                //验证密码
                if(\Yii::$app->security->validatePassword(\Yii::$app->request->post('password'),$model->password_hash)) {
                    $result = [
                        'errorCode'=>1032,
                        'errorMsg'=>'登陆成功',
                        'data'=>$model->getErrors()
                    ];
                }else{
                    $result = [
                        'errorCode'=>1031,
                        'errorMsg'=>'登陆失败、密码不正确',
                        'data'=>$model->getErrors()
                    ];
                }
            }else{
                $result = [
                    'errorCode'=>1011,
                    'errorMsg'=>'该用户不存在',
                    'data'=>[]
                ];
            }
        }else{
            $result = [
                'errorCode'=>9999,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
//-修改密码
    public function actionMemberChangepwd()
    {
        if(\Yii::$app->request->isPost){
            $username=\Yii::$app->request->post('username');
            $oldPassword=\Yii::$app->request->post('oldPassword');
            $newPassword=\Yii::$app->request->post('newPassword');
            $password=\Yii::$app->request->post('password');
            $model = Member::findOne(['username'=>$username]);

            if($model){
                //验证密码
                if(\Yii::$app->security->validatePassword($oldPassword,$model->password_hash)) {
//                    $Password=\Yii::$app->security->generatePasswordHash($newPassword);
//                    Member::updateAll(['password_hash'=>$Password],['username'=>$username]);
                    $model->password=$password;
                    $model->save();
                    $result = [
                        'errorCode'=>1002,
                        'errorMsg'=>'修改成功',
                        'data'=>$model->getErrors()
                    ];
                }else{
                    $result = [
                        'errorCode'=>1001,
                        'errorMsg'=>'修改失败、检查错误信息',
                        'data'=>$model->getErrors()
                    ];
                }
            }else{
                $result = [
                    'errorCode'=>1011,
                    'errorMsg'=>'该用户不存在',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'errorCode'=>9999,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
//-获取当前登录的用户信息
//-注销
    public function actionMemberLogout()
    {
        if(\Yii::$app->request->isPost){
            if(\Yii::$app->user->isGuest){
                $result = [
                    'errorCode'=>1010,
                    'errorMsg'=>'用户未登录'
                ];
            }else{
                \Yii::$app->user->logout();
                $result = [
                    'errorCode'=>1012,
                    'errorMsg'=>'注销成功'
                ];
            }
        }else{
            $result = [
                'errorCode'=>9999,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
    //2.地址
    //-添加地址
    public function actionAddAddress()
    {
        if(\Yii::$app->request->isPost){
            if (\Yii::$app->user->isGuest){
                $result = [
                    'errorCode'=>1011,
                    'errorMsg'=>'用户未登陆'
                ];
            }else{
                $model = new Address();
                $model->name =\Yii::$app->request->post('name');
                $model->member_id =\Yii::$app->user->id;
                $model->province =\Yii::$app->request->post('province');
                $model->city =\Yii::$app->request->post('city');
                $model->area = \Yii::$app->request->post('area');
                $model->detailed_address =\Yii::$app->request->post('detailed_address');
                $model->tel = \Yii::$app->request->post('tel');
                $model->status = \Yii::$app->request->post('status');
                if($model->validate()){
                    $model->save();
                    //添加成功
                    $result = [
                        'errorCode'=>1002,
                        'errorMsg'=>'添加成功',
                    ];
                }else{
                    //验证不通过
                    $result = [
                        'errorCode'=>1001,
                        'errorMsg'=>'添加失败，请检测错误信息',
                        'data'=>$model->getErrors()
                    ];
                }
            }
        }else{
            $result = [
                'errorCode'=>9999,
                'errorMsg'=>'请求方式错误，请使用POST提交数据',
                'data'=>[]
            ];
        }
        return $result;
    }
}