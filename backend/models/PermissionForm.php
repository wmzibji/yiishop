<?php
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    const SCENARIO_ADD='add';
    //---权限名称----
    public $name;
    //----权限描述---
    public $description;
    public function rules(){
        return [
          [['name','description'],'required'],
            //---权限名称唯一---
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels()
    {
        return [
           'name'=>'名称(路由)',
            'description'=>'描述'
        ] ;
    }
    public function validateName(){
        $authManage=\Yii::$app->authManager;
        if($authManage->getPermission($this->name)){
            $this->addError('name','权限已经存在！');
        }
    }
}





















