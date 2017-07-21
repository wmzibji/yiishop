<?php
$this->title='添加文章分类';
$this->params['breadcrumbs'][] = ['label' => '文章分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('返回列表',['article/index'],['class'=>'btn btn-sm btn-primary'])?>
<?php
$form = \yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status')->radioList(\backend\models\ArticleCategory::getStatusOptions(), ['class'=>'form-inline radio-inline']);
/*echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'article/captcha',
        'template'=>'<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>'])->label('验证码');*/
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束