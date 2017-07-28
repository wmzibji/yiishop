<?php
$this->title='添加权限';
$this->params['breadcrumbs'][] = ['label' => '权限列表', 'url' => ['permission-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = \yii\bootstrap\ActiveForm::begin(['class'=>'form-horizontal']);//表单开始
echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario!=\backend\models\PermissionForm::SCENARIO_ADD]);
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束