<?php
$this->title='添加角色';
$this->params['breadcrumbs'][] = ['label' => '角色列表', 'url' => ['role-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = \yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($model,'name')->textInput(/*['readonly'=>$model->scenario!=\backend\models\RoleForm::SCENARIO_ADD]*/);
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>true])->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束















