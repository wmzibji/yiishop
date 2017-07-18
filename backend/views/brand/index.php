<?php
$this->title='品牌列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table <!--table-bordered--> table-condensed list-table">
    <thead>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>简介</td>
        <td>LOGO图片</td>
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
            <td><?=\yii\bootstrap\Html::img($model->logo?$model->logo:'/upload/brand/default.jpg',['height'=>40]) ?></td>
            <td><?=$model->sort ?></td>
            <td><?=\backend\models\Brand::getStatusOptions()[$model->status] ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['brand/edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning'])?>

                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
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