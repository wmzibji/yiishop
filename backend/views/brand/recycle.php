<?php
$this->title='品牌—回收站';
$this->params['breadcrumbs'][] = ['label' => '品牌列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-responsive"> <!-- //表单列表-->
    <table class="table table-hover <!--table-bordered--> <!--table-condensed--> list-table  text-center ">
        <thead class="text-info">
            <tr class="success">
                <td>ID</td>
                <td>名称</td>
                <td>简介</td>
                <td>LOGO图片</td>
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
                <td><?=\yii\bootstrap\Html::img($model->logo?$model->logo:'/upload/brand/default.jpg',['height'=>30]) ?></td>
                <td><?=$model->sort ?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['edit','id'=>($model->id)],['class'=>'btn btn-sm btn-warning'])?>

                    <?=\yii\bootstrap\Html::a('还原',['reduction','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
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