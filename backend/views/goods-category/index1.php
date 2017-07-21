<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '商品分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('添加', ['add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
//            'tree',
//            'lft',
//            'rgt',
            'depth',
             'name',
             'parent_id',
             'intro',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
