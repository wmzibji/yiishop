<?php
$this->title='文章分类列表-回收站';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('返回列表',['article-category/index'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table <!--table-bordered--> table-condensed list-table">
    <thead>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($models as $model): ?>
        <tr>
            <td><?=$model->id ?></td>
            <td><?=$model->name ?></td>
            <td><?=$model->intro ?></td>
            <td><?=$model->sort ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['article-category/edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning'])?>

                <?=\yii\bootstrap\Html::a('还原',['article-category/reduction','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="">
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'尾页']);?>
</div>