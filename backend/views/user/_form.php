<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'password')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ;
    if(!$model->isNewRecord){
       echo $form->field($model, 'status')->radioList(\backend\models\User::$status_options);
    }?>
    <?= $form->field($model,'roles')->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description'));?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
