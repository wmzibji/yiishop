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
    <div class="table-responsive"> <!-- //表单列表-->
        <table class="table table-hover <!--table-condensed--> list-table  text-center ">
            <thead class="text-info">
            <tr class="success">
                <td>ID</td>
                <td>分类名称</td>
                <td>简介</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody class="text-success">
            <?php foreach($models as $model): ?>
                <tr>
                    <td><?=$model->id ?></td>
                    <td><?=$model->name ?></td>
                    <td><?=$model->intro ?></td>
                    <td>
                        <?=Html::a('',['edit','id'=>$model->id,'name'=>$model->name,'parent_id'=>$model->parent_id],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
                        <?=Html::a('',['delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash','data' => ['confirm' => '你确定要删除她么?', 'method' => 'post',],])?>
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
