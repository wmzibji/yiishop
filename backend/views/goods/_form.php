<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model backend\models\Goods */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
    $form = ActiveForm::begin();
    echo $form->field($model, 'name')->textInput(['maxlength' => true]) ;
    echo $form->field($model, 'logo')->hiddenInput(['maxlength' => true]) ;
    //-------图片外部TAG
    echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);;
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
        $("#goods-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
            ),
        ]
    ]);

echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>40]);
    //---/--图片---
    echo $form->field($model, 'goods_category_id')->hiddenInput() ;
/*$zTree =  \backend\widgets\ZTreeWidget::widget([
    'setting'=>'{
    data: {
		simpleData: {
			enable: true,
			pIdKey: "parent_id",
		}
	},
	callback: {
		onClick: function(event, treeId, treeNode) {
            $("#goods-goods_category_id").val(treeNode.id);
        }
	}
}',
    'zNodes'=>\backend\models\GoodsCategory::getZtreeNodes(),
    'selectNodes'=>['id'=>$model->goods_category_id],
]);
    echo $zTree ;*/
    echo '<div>
                <ul id="treeDemo" class="ztree"></ul>
            </div>';
    echo $form->field($model, 'brand_id')->dropDownList(\backend\models\Brand::getBrandOptions()) ;
    echo $form->field($model, 'market_price')->textInput(['maxlength' => true]) ;
    echo $form->field($model, 'shop_price')->textInput(['maxlength' => true]) ;
    echo $form->field($model, 'stock')->textInput() ;
    echo $form->field($model, 'is_on_sale')->radioList(\backend\models\Goods::$sale_options,['class'=>'form-inline radio-inline']) ;
    echo $form->field($model, 'status')->radioList(\backend\models\Goods::$status_options,['class'=>'form-inline radio-inline']) ;
    echo $form->field($model, 'sort')->textInput(['type'=>'number']) ;
    echo $form->field($model1, 'content')->widget(kucha\ueditor\UEditor::className()) ;
    echo Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ;
    ActiveForm::end();

//调用视图的方法加载静态资源
//加载css文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//加载js代码
$categories=\backend\models\GoodsCategory::getZtreeNodes();
$nodes = \yii\helpers\Json::encode($categories);
$nodeId = $model->goods_category_id;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
                var setting = {
                    data: {
                        simpleData: {
                            enable: true,
                            pIdKey: "parent_id",
                        }
                    },
                    callback: {
                        onClick: function(event, treeId, treeNode){
                            //将当期选中的分类的id，赋值给隐藏域
                            $("#goods-goods_category_id").val(treeNode.id);
                        }
                    }
                };
                var zNodes = {$nodes};
                zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
                // zTreeObj.expandAll(true);//展开全部节点
                //获取节点
                var node = zTreeObj.getNodeByParam("id", "{$nodeId}", null);
                //选中节点
                zTreeObj.selectNode(node);
JS

));
?>


