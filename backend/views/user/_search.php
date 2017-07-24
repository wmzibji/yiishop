<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'layout'=>'inline'
    ]); ?>
    <?php // echo $form->field($model, 'id')->label(false) ?>
    <?php echo $form->field($model, 'username')->textInput(['placeholder'=>'用户名'])->label(false) ?>
    <?php // echo$form->field($model, 'auth_key')->label(false) ?>
    <?php // echo $form->field($model, 'password_hash')->label(false) ?>
    <?php // echo $form->field($model, 'password_reset_token')->label(false) ?>
    <?php  echo $form->field($model, 'email')->textInput(['placeholder'=>'邮箱'])->label(false) ?>
    <?php // echo $form->field($model, 'status')->label(false) ?>
    <?php  echo $form->field($model, 'created_at')->textInput(['placeholder'=>'创建时间'])->label(false) ?>
    <?php // echo $form->field($model, 'updated_at')->label(false) ?>
    <?php // echo $form->field($model, 'last_login_time')->label(false) ?>
    <?php  echo $form->field($model, 'last_login_ip')->textInput(['placeholder'=>'最后登录IP'])->label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
