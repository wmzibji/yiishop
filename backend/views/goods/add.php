<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = '添加商品';
$this->params['breadcrumbs'][] = ['label' => '商品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-add">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model'=>$model,'model1'=>$model1/*,'model2'=>$model2,'model3'=>$model3*/]
    ) ?>

</div>
