<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('添加', ['add'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('回收站',['recycle'],['class'=>'btn btn-sm btn-warning'])?>
    </p>
    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'method' => 'get',
        //get方式提交,需要显式指定action
        'action'=>\yii\helpers\Url::to(['index']),
        'layout'=>'inline'
    ]);
    echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
    echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
    /*echo $form->field($model,'brand_id')->textInput(['placeholder'=>'品牌'])->label(false);
    echo $form->field($model,'goods_category_id')->textInput(['placeholder'=>'分类'])->label(false);*/
    echo $form->field($model,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
    echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'￥'])->label('-');
    echo \yii\bootstrap\Html::submitButton('<span class="glyphicon glyphicon-search"></span>搜索',['class'=>'btn btn-default']);
    \yii\bootstrap\ActiveForm::end();?>
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
                        <?=Html::a('<span class="glyphicon glyphicon-eye-open"></span>查看',['view','id'=>$model['id' ]],['class'=>'btn btn-sm btn-success'])?>
                        <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-picture"></span>相册',['gallery','id'=>$model->id],['class'=>'btn btn-sm btn-info'])?>
                        <?=Html::a('<span class="glyphicon glyphicon-edit"></span>编辑',['edit','id'=>$model['id' ]],['class'=>'btn btn-sm btn-warning'])?>
                        <?=Html::a('<span class=" glyphicon glyphicon-trash"></span>删除',['delete','id'=>$model['id' ]],['class'=>'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除她么?', 'method' => 'post',]])?>
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
