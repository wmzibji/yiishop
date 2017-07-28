<?php
namespace backend\models;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class User extends ActiveRecord implements IdentityInterface
{
    public $password;//明文密码
    public $rememberMe;
    //定义场景常量
    const SCENARIO_ADD = 'add';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_EDIT = 'edit';
    //状态
    public static $status_options=[0=>'禁用',10=>'正常'];
    public static function tableName()
    {
        return 'user';
    }
    //规则
    public function rules()
    {
        return [
//            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
//            [['status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'integer'],
//            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
//            [['auth_key'], 'string', 'max' => 32],
//            [['username'], 'unique'],
//            [['email'], 'unique'],
//            [['password_reset_token'], 'unique'],
//            ['status', 'default', 'value' => self::STATUS_ACTIVE],
//            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [['username'], 'required'],
            [['email'], 'required','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            ['password', 'required','on'=>[self::SCENARIO_ADD,self::SCENARIO_LOGIN]],//该规则只在添加场景生效
            [['status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'integer','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            [['username', 'password', 'password_reset_token', 'email'], 'string', 'max' => 255,'on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            [['auth_key'], 'string', 'max' => 32,'on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            [['username'], 'unique','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            [['email'], 'unique','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            [['password_reset_token'], 'unique','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            //验证邮箱格式
            ['email','email','on'=>[self::SCENARIO_ADD,self::SCENARIO_EDIT]],
            ['rememberMe','boolean','on'=>self::SCENARIO_LOGIN],
        ];
    }
    //找到身份----------
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }
    //通过访问令牌查找----------------
    public static function findIdentityByAccessToken($token, $type = null){}
    //--------
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    //验证 认证密钥
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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
            'password_reset_token' => '密码重置密匙',
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
            $this->status=10;
            $this->auth_key=\Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at=time();
        }
        if($this->password){
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }
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




    /*//行为
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    //设置hash密码
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    //生产认证密钥
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    //验证密码
     public function validatePassword($password)
     {
         return Yii::$app->security->validatePassword($password, $this->password_hash);
     }

    //生产密码重置令牌
    public function generatePasswordResetToken()
       {
           $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
       }
    //移出密码重置令牌
    public function removePasswordResetToken()
        {
            $this->password_reset_token = null;
        }
    ////通过用户名查找
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    //通过密码重置令牌找到
    public static function findByPasswordResetToken($token)
     {
         if (!static::isPasswordResetTokenValid($token)) {
             return null;
         }

         return static::findOne([
             'password_reset_token' => $token,
             'status' => self::STATUS_ACTIVE,
         ]);
     }
    //密码重置令牌有效吗
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }*/
}
