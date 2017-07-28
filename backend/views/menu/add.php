<?php
$this->title='添加菜单';
$this->params['breadcrumbs'][] = ['label' => '菜单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput(['placeholder'=>'菜单名称']);
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenuOptions());
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getUrlOptions(),['prompt' => '=请选择路由=']);
echo $form->field($model,'sort')->textInput(['placeholder'=>'排序']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
