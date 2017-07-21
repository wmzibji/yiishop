<?php
$this->title='文章—回收站';
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('返回列表',['article/index'],['class'=>'btn btn-sm btn-primary'])?>
<div class="table-responsive"> <!-- //表单列表-->
    <table class="table table-hover <!--table-bordered--> <!--table-condensed--> list-table  text-center ">
        <thead class="text-info">
            <tr class="success">
                <td>ID</td>
                <td>名称</td>
                <td>简介</td>
                <td>文章分类</td>
                <td>排序</td>
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
                    <?=\yii\bootstrap\Html::a('编辑',['article/edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning'])?>

                    <?=\yii\bootstrap\Html::a('还原',['article/reduction','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
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