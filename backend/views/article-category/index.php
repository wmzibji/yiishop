<?php
$this->title='文章分类列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-5 navbar-left">
        <?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-sm btn-primary'])?>
    </div>
    <div class="col-lg-3 navbar-right">
        <?=\yii\bootstrap\Html::a('回收站',['article-category/recycle'],['class'=>'btn btn-sm btn-warning'])?>
    </div>
</div>
<table class="table  <!--table-bordered--> table-condensed list-table">
    <thead>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
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
            <td><?=\backend\models\ArticleCategory::getStatusOptions()[$model->status] ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['article-category/edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning'])?>

                <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
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