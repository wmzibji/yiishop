<?php
$this->title='添加品牌';
$this->params['breadcrumbs'][] = ['label' => '品牌列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=\yii\bootstrap\Html::a('返回列表',['index'],['class'=>'btn btn-sm btn-primary'])?>
<?php
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin(['class'=>'form-horizontal']);//表单开始
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
//echo $form->field($model,'logo')->fileInput();
echo $form->field($model,'logo')->hiddenInput();
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片的地址赋值给logo字段
        $("#brand-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);

echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>40]);


echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($model,'status')->radioList(\backend\models\Brand::getStatusOptions(), ['class'=>'form-inline radio-inline']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();//表单结束