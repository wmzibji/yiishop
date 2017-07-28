<?php
namespace backend\models;
use Yii;
use yii\helpers\ArrayHelper;
class Menu extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'menu';
    }
    public function rules()
    {
        return [
            [['label','parent_id','sort'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址/路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }
    //--------获取一级菜单-----------
    public static function getMenuOptions()
    {
        return ArrayHelper::merge([''=>'=请选择上级菜单=',0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label'));
    }
    //-------获取地址----------------
    public static function getUrlOptions()
    {
        return  ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name');
    }

    //--------------获取子菜单-----------------
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
