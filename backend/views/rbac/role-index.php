<?php
$this->title='角色列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <h1><?= \yii\bootstrap\Html::encode($this->title) ?></h1>
    <p>
        <?=\yii\bootstrap\Html::a('添加',['add-role'],['class'=>'btn btn-sm btn-primary'])?>
    </p>
    <div class="table-responsive"> <!-- //表单列表-->
        <table class="table table-hover <!--table-bordered--> <!--table-condensed--> list-table  text-center ">
            <thead class="text-info">
            <tr class="success">
                <td>名称</td>
                <td>描述</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody class="text-success">
            <?php foreach($models as $model): ?>
                <tr>
                    <td><?=$model->name ?></td>
                    <td><?=$model->description ?></td>
                    <td>
                        <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span> 编辑',['edit-role','name'=>($model->name)],['class'=>'btn btn-sm btn-warning'])?>
                        <?=\yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span> 删除',['delete-role','name'=>$model->name],['class'=>'btn btn-sm btn-danger ','data' => ['confirm' => '你确定要删除她么?', 'method' => 'post',]])?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>