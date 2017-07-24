<?php
namespace backend\models;
use Yii;
use yii\base\Model;
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;

    //登录规则
    public function rules()
    {
        return [
            // 不为空
            [['username', 'password'], 'required'],
            // 必须是一个布尔值
            ['rememberMe', 'boolean'],
            // 密码得和验证密码一致
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我'
        ];
    }
    //验证登录密码
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    //查询用户
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
