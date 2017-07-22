<?php
$this->title='添加文章';
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('返回列表',['index'],['class'=>'btn btn-sm btn-primary'])?>
<?php
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin(['class'=>'form-horizontal']);//表单开始
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea(['rows' => '2']);
echo $form->field($model1,'content')->widget(kucha\ueditor\UEditor::className());
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\ArticleCategory::getCategoryOptions());
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status')->radioList(\backend\models\Brand::getStatusOptions(), ['class'=>'form-inline radio-inline']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束