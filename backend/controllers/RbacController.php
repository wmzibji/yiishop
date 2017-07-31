<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends BaseController
{
        //---添加权限-----
    public function actionAddPermission()
    {
        $model=new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;
            //----------创建权限---
                //--权限名称----
            $permission=$authManager->createPermission($model->name);
                //--权限描述---
            $permission->description=$model->description;
                //---保存---
            $authManager->add($permission);
            \Yii::$app->session->setFlash('success','权限['.$model->name.']添加成功！');
            return $this->redirect(['permission-index']);
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name){
            //---检查权限是否存在-------
        $authManage=\Yii::$app->authManager;
        $permission=$authManage->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在！');
        }
        $model=new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                    //----将表单数据赋值给权限-----
                //--名称--
                $permission->$name=$model->name;
                //--描述---
                $permission->descrition=$model->description;
                    //----更新权限-----
                $authManage->update($name,$permission);
                \Yii::$app->session->setFlash('success','权限修改成功！');
            }
        }else{
                //-------回显权限数据到表单-----------
            //---名称----
            $model->name=$permission->name;
            //---描述-----
            $model->description=$permission->description;
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDeletePermission($id){

    }
    //权限列表
    public function actionPermissionIndex(){
        //----获取所有权限数据--------
        $authManage=\Yii::$app->authManager;
        $models=$authManage->getPermissions();
        //------分配数据到页面---------
        return $this->render('permission-index',['models'=>$models]);
    }
    //添加角色
    public function actionAddRole(){
        $model=new RoleForm();
//        $model->scenario = RoleForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //------创建、保存角色----------
            $authManager=\Yii::$app->authManager;
            //----角色名称----
            $role=$authManager->createRole($model->name);
            //------角色描述--
            $role->description=$model->description;
            $authManager->add($role);
                //--------给角色赋予权限---------
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission)
                        $authManager->addChild($role, $permission);
                }
            }
            \Yii::$app->session->setFlash('success','角色添加成功！');
            return $this->redirect(['role-index']);
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //--修改角色----
    public function actionEditRole($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $model=new RoleForm();
            //------取消角色和权限的关联--------
//        $authManager=\Yii::$app->authManager;
//        $role=$authManager->getRole($name);
        //---获取角色权限-----回显--
        $permission=$authManager->getPermissionsByRole($name);
        //---名称----
        $model->name=$role->name;
        //----描述----
        $model->description=$role->description;
        $model->permissions=ArrayHelper::map($permission,'name','name');
        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $role->description = $model->description;
            $role->name = $model->name;
            $authManager->update($name, $role);
            //--------给角色赋予权限---------
            $authManager->removeChildren($role);
            if (is_array($model->permissions)) {
                foreach ($model->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) $authManager->addChild($role, $permission);
                }
            }
            \Yii::$app->session->setFlash('success', '角色修改成功！');
            return $this->redirect(['role-index']);
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //-----角色列表--------
    public function actionRoleIndex(){
        $models = \Yii::$app->authManager->getRoles();
        return $this->render('role-index',['models'=>$models]);
    }
}























