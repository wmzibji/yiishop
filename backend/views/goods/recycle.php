<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => '商品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="table-responsive"> <!-- //表单列表-->
        <table class="table table-hover list-table  text-center ">
            <thead class="text-info">
            <tr class="success">
                <td>ID</td>
                <td>货号</td>
                <td>商品名称</td>
                <td>品牌</td>
                <td>商品分类</td>
                <td>价格</td>
                <td>库存</td>
                <td>是否在售</td>
                <td>LOGO</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody class="text-success">
            <?php foreach($models as $model): ?>
                <tr>
                    <td><?=$model['id' ]?></td>
                    <td><?=$model['sn' ]?></td>
                    <td><?=$model['name' ]?></td>
                    <td><?=$model->brand->name ?></td>
                    <td><?=$model->goodsCategory->name ?></td>
                    <td><?=$model->shop_price?></td>
                    <td><?=$model['stock'] ?></td>
                    <td><?=\backend\models\Goods::$sale_options[$model->is_on_sale] ?></td>
                    <td><img src="<?=$model['logo'] ?>" alt="" height="40"></td>
                    <td>
                        <?=Html::a('',['view','id'=>$model['id' ]],['class'=>'btn btn-sm btn-info glyphicon glyphicon-eye-open'])?>

                        <?=Html::a('',['edit','id'=>$model['id' ]],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
                        <?=Html::a('还原 ',['reduction','id'=>$model['id' ]],['class'=>'btn btn-sm btn-success'])?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div  class="pull-right"><!--//分页工具条-->
<?php
    echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'尾页']);?>
</div>
