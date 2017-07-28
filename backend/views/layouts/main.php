<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '商城后台管理中心',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    /*$menuItems = [
        ['label' => '用户管理' ,'items'=>[
            ['label'=>'用户列表','url'=>['user/index']],
        ]],
        ['label' => '品牌管理' ,'items'=>[
            ['label'=>'品牌列表','url'=>['brand/index']],
            ['label'=>'添加品牌','url'=>['brand/add']],
            ['label'=>'品牌回收站','url'=>['brand/recycle']]
        ]],
        ['label' => '商品管理' ,'items'=>[
            ['label'=>'商品分类列表','url'=>['goods-category/index']],
            ['label'=>'添加商品分类','url'=>['goods-category/add']],
            ['label'=>'商品列表','url'=>['goods/index']],
            ['label'=>'添加商品','url'=>['goods/add']]
        ]],
        ['label'=>'文章管理','items'=>[
            ['label'=>'分类列表','url'=>['article-category/index']],
            ['label'=>'添加分类','url'=>['article-category/add']],
            ['label'=>'分类回收站','url'=>['article-category/recycle']],
            ['label'=>'文章列表','url'=>['article/index']],
            ['label'=>'添加文章','url'=>['article/add']],
        ]],
        ['label'=>'RBAC','items'=>[
            ['label'=>'权限列表','url'=>['rbac/permission-index']],
            ['label'=>'添加权限','url'=>['rbac/add-permission']],
            ['label'=>'角色列表','url'=>['rbac/role-index']],
            ['label'=>'添加角色','url'=>['rbac/add-role']],
        ]],
        ['label'=>'菜单管理','items'=>[
            ['label'=>'菜单列表','url'=>['menu/index']],
            ['label'=>'添加菜单','url'=>['menu/add']],
        ]]
    ];*/
    $menuItems = [];
    $menus=\backend\models\Menu::findAll(['parent_id'=>0]);
    foreach ($menus as $menu){
            //-------一级菜单----------
        $items = [];
        foreach ($menu->children as $child){
            //----------判断用户是否有该路由权限-----
            if(Yii::$app->user->can($child->url)){
                $items[] = ['label' => $child->label, 'url' => [$child->url]];
            }
        }
        //---------没有子菜单，不显示一级菜单-----------
        if(!empty($items)){
            $menuItems[] = ['label' => $menu->label, 'items' => $items];
        }

    }
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登陆', 'url' => ['user/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['user/logout'], 'post')
            . Html::submitButton(
                '退出登陆 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
//        $menuItems[] = ['label' => '修改密码', 'url' => ['user/changepw']];
        $menuItems[] =  '<li>'
            . Html::beginForm(['user/changepw'], 'post')
            . Html::submitButton(
                '修改密码',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; PHP学习 <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
