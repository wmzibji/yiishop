<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('修改', ['edit', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除她么?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'sn',
            'logo',
            'goods_category_id',
            'brand_id',
            'market_price',
            'shop_price',
            'stock',
            'is_on_sale',
            'status',
            'sort',
            'create_time:datetime',
            'view_times:datetime',
        ],
    ]) ?>
    <h4>商品图库：</h4>
    <?=\yii\bootstrap\Carousel::widget([
        'items' => $model->getPics()
    ]);?>
    <h4>商品描述：</h4>
    <div class="container">
        <?=$model->goodsIntro->content?>
    </div>
</div>
