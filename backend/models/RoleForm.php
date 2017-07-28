<?php
namespace backend\models;
use yii\base\Model;
class RoleForm extends Model{
    const SCENARIO_ADD='add';
    //--名称---
    public $name;
    //---描述---
    public $description;
    public $permissions=[];
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            //--角色名称唯一-----------
//            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permission'=>'权限',
        ] ;
    }
    public function validateName(){
        $authManager=\Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','角色已经存在！');
        }
    }
}











