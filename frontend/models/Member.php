<?php
namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class Member extends ActiveRecord implements IdentityInterface
{
    public $password;//明文密码
    public $rePassword;//确认密码
    public $rememberMe;
    public $code;//图像验证码
    public $smsCode;//短信验证码
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
            ['password','compare','compareAttribute'=>'rePassword','on'=>self::SCENARIO_REGISTER],
            ['rememberMe','boolean','on'=>self::SCENARIO_LOGIN],

            [['username','tel','email'], 'required'],
            [['username','tel','email'],'unique'],
            [['code','smsCode','password','rePassword'], 'required','on'=>self::SCENARIO_REGISTER],
            [['code'], 'captcha','captchaAction'=>'member/captcha','on'=>[self::SCENARIO_REGISTER,self::SCENARIO_LOGIN]],
            [['status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'integer'],
            [['username', 'password', 'tel', 'email'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],

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

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
            'rePassword' => '确认密码',
            'tel' => '电话',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'rememberMe' => '记住我',
            'code' => '验证码',
            'smsCode' => '短信验证码',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
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
//                var_dump($model);exit;
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
    //建立address和member的关系
    public function getAddressForm()
    {
        return $this->hasMany(AddressForm::className(),['id'=>'member_id']);
    }
}
