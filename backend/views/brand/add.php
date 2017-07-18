
<?=\yii\bootstrap\Html::a('返回列表',['brand/index'],['class'=>'btn btn-sm btn-primary'])?>

<?php
$form = \yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->fileInput();
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status')->radioList(\backend\models\Brand::getStatusOptions(), ['class'=>'form-inline radio-inline']);
/*echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'brand/captcha',
        'template'=>'<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>'])->label('验证码');*/
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束