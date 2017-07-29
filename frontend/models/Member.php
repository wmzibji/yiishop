<?php
namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class Member extends ActiveRecord
{
    public $password;//明文密码
    public $rememberMe;
    public $roles=[];
    //定义场景常量
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_LOGIN = 'login';
    //状态
    public static $status_options=[0=>'删除',1=>'正常'];
    public static function tableName()
    {
        return 'member';
    }
    //规则
    public function rules()
    {
        return [

            [['username'], 'required'],
            [['email'], 'required','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            ['password', 'required','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_LOGIN]],//该规则只在添加场景生效
            [['status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'integer','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            [['username', 'password', 'tel', 'email'], 'string', 'max' => 255,'on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            [['auth_key'], 'string', 'max' => 32,'on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            [['username'], 'unique','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            [['email'], 'unique','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            //验证邮箱格式
            ['email','email','on'=>[self::SCENARIO_LOGIN,self::SCENARIO_REGISTER]],
            ['rememberMe','boolean','on'=>self::SCENARIO_LOGIN],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '密匙',
            'password_hash' => '密码',
            'password' => '密码',
            'tel' => '电话',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'rememberMe' => '记住我',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
            $this->updated_at=time();
            $this->status=1;
            $this->auth_key=\Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at=time();
        }
        if($this->password){
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }
  /*  public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if(in_array($this->scenario,[self::SCENARIO_ADD,self::SCENARIO_EDIT])){
            $authManager = Yii::$app->authManager;
            $authManager->revokeAll($this->id);
            if(is_array($this->roles)){
                foreach ($this->roles as $roleName){
                    $role = $authManager->getRole($roleName);
                    if($role) $authManager->assign($role,$this->id);
                }
            }
        }

    }*/
    public function login()
    {
        $model=self::findOne(['username'=>$this->username]);
        //用户存在
        if($model){
            //验证密码
            if(\Yii::$app->security->validatePassword($this->password,$model->password_hash)){
                //验证成功-登陆--保存用户信息到session
                \Yii::$app->user->login($model,$this->rememberMe?7*24*3600:0);
                $model->last_login_time=time();
                $model->last_login_ip=ip2long(Yii::$app->request->userIP);
                $model->save();
                return true;
            }else{
                //密码错误---
                $this->addError('password','密码错误！');
            }
    }else{
            //用户不存在
            $this->addError('username','该用户不存在！');
        }
        return false;
    }
}
