<?php
namespace backend\models;
use yii\base\Model;

class Changepw extends Model{
    public $oldpassword;
    public $newpassword;
    public $repassword;
    public function attributeLabels()
    {
        return [
            'oldpassword'=>'旧密码',
            'newpassword'=>'新密码',
            'repassword'=>'确认密码',
        ];
    }

    public function rules()
    {
        return [
            [['oldpassword','newpassword','repassword'],'required'],
            //验证旧密码是否正确
            ['oldpassword','validatePassword'],
            //新旧密码不能一样
            ['newpassword','compare','compareAttribute'=>'oldpassword','operator'=>'!='],
            //确认密码必须和新密码一直
            ['repassword','compare','compareAttribute'=>'newpassword'],
        ];
    }
    public function validatePassword(){
        //新旧密码不一致
        if(!\Yii::$app->security->validatePassword($this->oldpassword,\Yii::$app->user->identity->password_hash)){
            //提示密码错误
            $this->addError('oldpassword','旧密码不正确！');
        }
    }

}
















