<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends ActionFilter{
        //--------操作执行之前------------
    public function beforeAction($action)
    {
        //-----没有登录------------引导登录-----------
        if(\Yii::$app->user->isGuest){
            return $action->controller->redirect(\Yii::$app->user->loginUrl);
        }
        if(!\Yii::$app->user->can($action->uniqueId)){
            throw new ForbiddenHttpException('对不起，您没有该执行权限');
        }
        return parent::beforeAction($action); //return true;//放行
    }
}