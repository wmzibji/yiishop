<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
$this->title = '编辑: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="user-edit">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
