<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('添加用户', ['signup'], ['class' => 'btn btn-success']) ?>
    </p>
    <!--//搜索框-->
    <p>
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </p>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel, //搜索
        //分页
       /* 'layout'=> '{items}<div class="text-right tooltip-demo">{pager}</div>',
        'pager'=>[
            //'options'=>['class'=>'hidden']//关闭分页
            'firstPageLabel'=>"First",
            'prevPageLabel'=>'Prev',
            'nextPageLabel'=>'Next',
            'lastPageLabel'=>'Last',
        ],*/
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
             'email:email',
//             'status',
            [
                'label'=>'状态',
                'attribute' => 'status',
                'value' => function ($model) {
                    $state = [
                        '0' => '回收站',
                        '10' => '正常'
                    ];
                    return $state[$model->status];
                },
                'headerOptions' => ['width' => '120']
            ],
            [
                'label'=>'创建时间',
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => 'created_at'
            ],
            [
                'label'=>'更新时间',
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => 'updated_at'
            ],

            // 'last_login_time:datetime',
            // 'last_login_ip',
//            ['class' => 'yii\grid\ActionColumn'],//操作  查看 删除 编辑
            [
                //动作列yii\grid\ActionColumn
                //用于显示一些动作按钮，如每一行的更新、删除操作。
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete} {update}',//只需要展示删除和更新
                'headerOptions' => ['width' => '150'],
                'buttons' => [
                    'delete' => function($url, $model, $key){
                        return Html::a('<span class=" glyphicon glyphicon-trash"></span> 删除',['delete','id'=>$model['id' ]],['class'=>'btn btn-sm btn-danger','data' => ['confirm' => '你确定要删除她么?', 'method' => 'post',]]);
                    },
                    'update' => function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-edit"></span> 编辑',['update','id'=>$model['id' ]],['class'=>'btn btn-sm btn-warning']);
                    },
                ],
            ],

        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
