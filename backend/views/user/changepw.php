<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */
use yii\bootstrap\ActiveForm;
$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-changepw">
    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
    <p>请填写以下字段修改密码:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
                <?php //echo $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'oldpassword')->passwordInput() ?>
                <?= $form->field($model, 'newpassword')->passwordInput() ?>
                <?= $form->field($model, 'repassword')->passwordInput() ?>
                <div class="form-group">
                    <?= yii\bootstrap\Html::submitButton('确认', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
