<?php
$this->title='文章分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-10 col-md-9 navbar-left">
            <?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-sm btn-primary'])?>
        </div>
        <div class="col-xs-2 col-md-2 navbar-right">
            <?=\yii\bootstrap\Html::a('回收站',['article-category/recycle'],['class'=>'btn btn-sm btn-warning'])?>
        </div>
        <div class="col-xs-6 col-md-1 navbar-right"></div>
    </div>
</div>
<div class="table-responsive"> <!-- //表单列表-->
    <table class="table table-hover <!--table-bordered--> <!--table-condensed--> list-table  text-center ">
        <thead class="text-info">
            <tr class="success">
                <td>ID</td>
                <td>名称</td>
                <td>简介</td>
                <td>排序</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
    </thead>
    <tbody class="text-success">
        <?php foreach($models as $model): ?>
            <tr>
                <td><?=$model->id ?></td>
                <td><?=$model->name ?></td>
                <td><?=$model->intro ?></td>
                <td><?=$model->sort ?></td>
                <td><?=\backend\models\ArticleCategory::getStatusOptions()[$model->status] ?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('',['article-category/edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>

                    <?=\yii\bootstrap\Html::a('',['article-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<div  class="pull-right"><!--//分页工具条-->
    <?php
    echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'尾页']);?>
</div>