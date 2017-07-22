<?php
$this->title='文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <h1><?= \yii\bootstrap\Html::encode($this->title) ?></h1>
    <p>
        <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-sm btn-primary'])?>

        <?=\yii\bootstrap\Html::a('回收站',['recycle'],['class'=>'btn btn-sm btn-warning'])?>
    </p>
    <div>
        <form class="" style="height: 30px">
            <input type="text" name="keywords" width="100px" class="col-lg-5" style="height: 30px">
            <input type="submit" value="搜索" class="btn btn-success" style="height: 30px">
        </form>
    </div>
<div class="table-responsive"> <!-- //表单列表-->
    <table class="table table-hover <!--table-bordered--> <!--table-condensed--> list-table  text-center ">
        <thead class="text-info">
        <tr class="success">
            <td>ID</td>
            <td>名称</td>
            <td>简介</td>
            <td>文章分类</td>
            <td>排序</td>
            <td>状态</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody class="text-success">
        <?php foreach($models as $model): ?>
            <tr>
                <td><?=$model->id ?></td>
                <td><?=$model->name ?></td>
                <td><?=$model->intro ?></td>
                <td><?=$model->articleCategory->name ?></td>
                <td><?=$model->sort ?></td>
                <td><?=date('Y-m-d H:i:s',$model->create_time) ?></td>
                <td><?=\backend\models\article::getStatusOptions()[$model->status] ?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('',['edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>

                    <?=\yii\bootstrap\Html::a('',['delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash','data' => ['confirm' => '你确定要删除她么?', 'method' => 'post',]])?>
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