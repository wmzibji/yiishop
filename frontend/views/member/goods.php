<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>商品页面</title>
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/base.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/global.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/header.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/goods.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/common.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/footer.css" type="text/css">

    <!--引入jqzoom css -->
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/jqzoom.css" type="text/css">

    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/header.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/goods.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jqzoom-core.js"></script>

    <!-- jqzoom 效果 -->
    <script type="text/javascript">
        $(function(){
            $('.jqzoom').jqzoom({
                zoomType: 'standard',
                lens:true,
                preloadImages: false,
                alwaysOn:false,
                title:false,
                zoomWidth:400,
                zoomHeight:400
            });
        })
    </script>
</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！
                    <?php if(Yii::$app->user->isGuest):?>
                        [<?=\yii\helpers\Html::a('登录',['member/login'])?>]
                        [<?=\yii\helpers\Html::a('免费注册',['member/register'])?>]
                    <?php else:?>
                        [<?=\yii\helpers\Html::a('注销',['member/logout'])?>]
                    <?php endif;?>
                </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 头部 start -->
<div class="header w1210 bc mt15">
    <!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
    <div class="logo w1210">
        <h1 class="fl"><a href="<?=Yii::getAlias('@web')?>/member/index"><img src="<?=Yii::getAlias('@web')?>/images/logo.png" alt="京西商城"></a></h1>
        <!-- 头部搜索 start -->
        <div class="search fl">
            <div class="search_form">
                <div class="form_left fl"></div>
                <form action="" name="serarch" method="get" class="fl">
                    <input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
                </form>
                <div class="form_right fl"></div>
            </div>

            <div style="clear:both;"></div>

            <div class="hot_search">
                <strong>热门搜索:</strong>
                <a href="">D-Link无线路由</a>
                <a href="">休闲男鞋</a>
                <a href="">TCL空调</a>
                <a href="">耐克篮球鞋</a>
            </div>
        </div>
        <!-- 头部搜索 end -->

        <!-- 用户中心 start-->
        <div class="user fl">
            <dl>
                <dt>
                    <em></em>
                    <a href="">用户中心</a>
                    <b></b>
                </dt>
                <dd>
                    <?php if(Yii::$app->user->isGuest):?>
                        <div class="prompt">
                            您好，请[<?=\yii\helpers\Html::a('登录',['member/login'])?>]
                        </div>
                    <?php else:?>
                        <div class="prompt">
                            您好!
                        </div>
                        <div class="uclist mt10">
                            <ul class="list1 fl">
                                <li><a href="">用户信息></a></li>
                                <li><a href="">我的订单></a></li>
                                <li><a href="">收货地址></a></li>
                                <li><a href="">我的收藏></a></li>
                            </ul>

                            <ul class="fl">
                                <li><a href="">我的留言></a></li>
                                <li><a href="">我的红包></a></li>
                                <li><a href="">我的评论></a></li>
                                <li><a href="">资金管理></a></li>
                            </ul>

                        </div>
                    <?php endif;?>
                    <div style="clear:both;"></div>
                    <div class="viewlist mt10">
                        <h3>最近浏览的商品：</h3>
                        <ul>
                            <li><a href=""><img src="<?=Yii::getAlias('@web')?>/images/view_list1.jpg" alt="" /></a></li>
                            <li><a href=""><img src="<?=Yii::getAlias('@web')?>/images/view_list2.jpg" alt="" /></a></li>
                            <li><a href=""><img src="<?=Yii::getAlias('@web')?>/images/view_list3.jpg" alt="" /></a></li>
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
        <!-- 用户中心 end-->

        <!-- 购物车 start -->
        <div class="cart fl">
            <dl>
                <dt>
                    <a href="<?=Yii::getAlias('@web')?>/member/cart">去购物车结算</a>
                    <b></b>
                </dt>
                <dd>
                    <div class="prompt">
                        <?php if($carts==null):?>
                            您的购物车中还没有商品，赶紧选购吧！
                        <?php else:?>
                            您的购物车已有 <?=$carts?> 件商品，去结算！
                        <?php endif;?>
                    </div>
                </dd>
            </dl>
        </div>
        <!-- 购物车 end -->
    </div>
    <!-- 头部上半部分 end -->

    <div style="clear:both;"></div>

    <!-- 导航条部分 start -->
    <div class="nav w1210 bc mt10">
        <!--  商品分类部分 start-->
        <div class="category fl cat1">
            <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，并将cat_bd设置为不显示(加上类none即可)，鼠标滑过时展开菜单则将off类换成on类 -->
                <h2>全部商品分类</h2>
                <em></em>
            </div>

            <div class="cat_bd none">
                <?php foreach($nav as $nav1): ?>
                    <div class="cat item1">
                        <h3><a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$nav1['id']?>"><?=$nav1['name']?></a> <b></b></h3>
                        <div class="cat_detail">
                            <?php foreach($nav1->children as $child1):?>
                                <dl class="dl_1st">
                                    <dt><a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$child1['id']?>"><?=$child1->name?></a></dt>
                                    <?php foreach($child1->children as $child2): ?>
                                        <dd>
                                            <a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$child2['id']?>"><?=$child2->name?></a>
                                        </dd>
                                    <?php endforeach;?>
                                </dl>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>

        </div>
        <!--  商品分类部分 end-->

        <div class="navitems fl">
            <ul class="fl">
                <li class="current"><a href="">首页</a></li>
                <li><a href="">电脑频道</a></li>
                <li><a href="">家用电器</a></li>
                <li><a href="">品牌大全</a></li>
                <li><a href="">团购</a></li>
                <li><a href="">积分商城</a></li>
                <li><a href="">夺宝奇兵</a></li>
            </ul>
            <div class="right_corner fl"></div>
        </div>
    </div>
    <!-- 导航条部分 end -->
</div>
<!-- 头部 end-->

<div style="clear:both;"></div>


<!-- 商品页面主体 start -->
<div class="main w1210 mt10 bc">
    <!-- 面包屑导航 start -->
    <div class="breadcrumb">
        <h2>当前位置：<a href="<?=Yii::getAlias('@web')?>/member/index">首页</a> >
            <a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$fathers['id']?>"><?=$fathers['name']?></a> >
            <a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$model1['id']?>"><?=$model1['name']?></a> >
            <?=$model['name']?>
        </h2>
    </div>
    <!-- 面包屑导航 end -->

    <!-- 主体页面左侧内容 start -->
    <div class="goods_left fl">
        <!-- 相关分类 start -->
        <div class="related_cat leftbar mt10">
            <h2><strong>相关分类</strong></h2>
            <div class="leftbar_wrap">
                <ul><?php foreach($model1s as $model1):?>
                        <li><a href="<?=Yii::getAlias('@web')?>/member/list?category_id=<?=$model1['id']?>"><?=$model1->name?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <!-- 相关分类 end -->

        <!-- 热销排行 start -->
        <div class="hotgoods leftbar mt10">
            <h2><strong>热销排行榜</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li></li>
                </ul>
            </div>
        </div>
        <!-- 热销排行 end -->


        <!-- 浏览过该商品的人还浏览了  start 注：因为和list页面newgoods样式相同，故加入了该class -->
        <div class="related_view newgoods leftbar mt10">
            <h2><strong>浏览了该商品的用户还浏览了</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li>
                        <dl>
                            <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/relate_view1.jpg" alt="" /></a></dt>
                            <dd><a href="">ThinkPad E431(62771A7) 14英寸笔记本电脑 (i5-3230 4G 1TB 2G独显 蓝牙 win8)</a></dd>
                            <dd><strong>￥5199.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/relate_view2.jpg" alt="" /></a></dt>
                            <dd><a href="">ThinkPad X230i(2306-3V9） 12.5英寸笔记本电脑 （i3-3120M 4GB 500GB 7200转 蓝牙 摄像头 Win8）</a></dd>
                            <dd><strong>￥5199.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/relate_view3.jpg" alt="" /></a></dt>
                            <dd><a href="">T联想（Lenovo） Yoga13 II-Pro 13.3英寸超极本 （i5-4200U 4G 128G固态硬盘 摄像头 蓝牙 Win8）晧月银</a></dd>
                            <dd><strong>￥7999.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/relate_view4.jpg" alt="" /></a></dt>
                            <dd><a href="">联想（Lenovo） Y510p 15.6英寸笔记本电脑（i5-4200M 4G 1T 2G独显 摄像头 DVD刻录 Win8）黑色</a></dd>
                            <dd><strong>￥6199.00</strong></dd>
                        </dl>
                    </li>

                    <li class="last">
                        <dl>
                            <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/relate_view5.jpg" alt="" /></a></dt>
                            <dd><a href="">ThinkPad E530c(33662D0) 15.6英寸笔记本电脑 （i5-3210M 4G 500G NV610M 1G独显 摄像头 Win8）</a></dd>
                            <dd><strong>￥4399.00</strong></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 浏览过该商品的人还浏览了  end -->

        <!-- 最近浏览 start -->
        <div class="viewd leftbar mt10">
            <h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
            <div class="leftbar_wrap">
                <dl>
                    <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/hpG4.jpg" alt="" /></a></dt>
                    <dd><a href="">惠普G4-1332TX 14英寸笔记...</a></dd>
                </dl>

                <dl class="last">
                    <dt><a href=""><img src="<?=Yii::getAlias('@web')?>/images/crazy4.jpg" alt="" /></a></dt>
                    <dd><a href="">直降200元！TCL正1.5匹空调</a></dd>
                </dl>
            </div>
        </div>
        <!-- 最近浏览 end -->

    </div>
    <!-- 主体页面左侧内容 end -->

    <!-- 商品信息内容 start -->
    <div class="goods_content fl mt10 ml10">
        <!-- 商品概要信息 start -->
        <div class="summary">
            <h3><strong><?=$model['name']?></strong></h3>

            <!-- 图片预览区域 start -->
            <div class="preview fl">
                <div class="midpic">
                    <a href="<?=$model['logo']?>" class="jqzoom" rel="gal1">   <!-- 第一幅图片的大图 class 和 rel属性不能更改 -->
                        <img src="<?=$model['logo']?>" alt="" />               <!-- 第一幅图片的中图 -->
                    </a>
                </div>

                <!--使用说明：此处的预览图效果有三种类型的图片，大图，中图，和小图，取得图片之后，分配到模板的时候，把第一幅图片分配到 上面的midpic 中，其中大图分配到 a 标签的href属性，中图分配到 img 的src上。 下面的smallpic 则表示小图区域，格式固定，在 a 标签的 rel属性中，分别指定了中图（smallimage）和大图（largeimage），img标签则显示小图，按此格式循环生成即可，但在第一个li上，要加上cur类，同时在第一个li 的a标签中，添加类 zoomThumbActive  -->

                <div class="smallpic">
                    <a href="javascript:;" id="backward" class="off"></a>
                    <a href="javascript:;" id="forward" class="on"></a>
                    <div class="smallpic_wrap">
                        <ul><?php foreach($picture as $picture1):?>
                                <li class="cur">
                                    <a  href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: 'http://admin.yii2.com<?=$picture1->path?>',largeimage: 'http://admin.yii2.com<?=$picture1->path?>'}"><img src="http://admin.yii2.com<?=$picture1->path?>"></a>
                                </li>
                            <?php endforeach;?>
                            <!--<li>
									<a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: 'images/preview_m2.jpg',largeimage: 'images/preview_l2.jpg'}"><img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s2.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m3.jpg',largeimage: 'images/preview_l3.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s3.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m4.jpg',largeimage: 'images/preview_l4.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s4.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m5.jpg',largeimage: 'images/preview_l5.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s5.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m6.jpg',largeimage: 'images/preview_l6.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s6.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m7.jpg',largeimage: 'images/preview_l7.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s7.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m8.jpg',largeimage: 'images/preview_l8.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s8.jpg"></a>
								</li>
								<li>
									<a href="javascript:void(0);"
									rel="{gallery: 'gal1', smallimage: 'images/preview_m9.jpg',largeimage: 'images/preview_l9.jpg'}">
	    							<img src="<?/*=Yii::getAlias('@web')*/?>/images/preview_s9.jpg"></a>
								</li>-->
                        </ul>
                    </div>

                </div>
            </div>
            <!-- 图片预览区域 end -->

            <!-- 商品基本信息区域 start -->
            <div class="goodsinfo fl ml10">
                <ul>
                    <li><span>商品编号： </span><?=$model['sn']?></li>
                    <li class="market_price"><span>定价：</span><em>￥<?=$model['market_price']?></em></li>
                    <li class="shop_price"><span>本店价：</span> <strong>￥<?=$model['shop_price']?></strong> <a href="">(降价通知)</a></li>
                    <li><span>上架时间：</span><?=date('Y-h-d',$model['create_time'])?></li>
                    <li class="star"><span>商品评分：</span> <strong></strong><a href="">(已有 <?=$model['view_times']?> 人评价)</a></li> <!-- 此处的星级切换css即可 默认为5星 star4 表示4星 star3 表示3星 star2表示2星 star1表示1星 -->
                </ul>
                <form action="<?=\yii\helpers\Url::to(['member/add-to-cart'])?>" method="get" class="choose">
                    <ul>

                        <li>
                            <dl>
                                <dt>购买数量：</dt>
                                <dd>
                                    <a href="javascript:;" id="reduce_num"></a>
                                    <input type="text" name="amount" value="1" class="amount"/>
                                    <a href="javascript:;" id="add_num"></a>
                                </dd>
                            </dl>
                        </li>

                        <li>
                            <dl>
                                <dt>&nbsp;</dt>
                                <dd>
                                    <input type="hidden" name="goods_id" value="<?=$model->id?>"/><!----商品ID--->
                                    <input type="submit" value="" class="add_btn" />
                                </dd>
                            </dl>
                        </li>

                    </ul>
                </form>
            </div>
            <!-- 商品基本信息区域 end -->
        </div>
        <!-- 商品概要信息 end -->

        <!-- 商品详情 start -->
        <div class="detail">
            <div class="detail_hd">
                <ul>
                    <li class="first"><span>商品介绍</span></li>
                </ul>
            </div>
            <div class="detail_bd">
            </div>
            <div class="detail_bd">
                <?=$intro['content']?>
            </div>

            <!-- 商品详情 end -->


        </div>
        <!-- 商品信息内容 end -->


    </div>
    <!-- 商品页面主体 end -->


    <div style="clear:both;"></div>

    <!-- 底部导航 start -->
    <div class="bottomnav w1210 bc mt10">
        <div class="bnav1">
            <h3><b></b> <em>购物指南</em></h3>
            <ul>
                <li><a href="">购物流程</a></li>
                <li><a href="">会员介绍</a></li>
                <li><a href="">团购/机票/充值/点卡</a></li>
                <li><a href="">常见问题</a></li>
                <li><a href="">大家电</a></li>
                <li><a href="">联系客服</a></li>
            </ul>
        </div>

        <div class="bnav2">
            <h3><b></b> <em>配送方式</em></h3>
            <ul>
                <li><a href="">上门自提</a></li>
                <li><a href="">快速运输</a></li>
                <li><a href="">特快专递（EMS）</a></li>
                <li><a href="">如何送礼</a></li>
                <li><a href="">海外购物</a></li>
            </ul>
        </div>


        <div class="bnav3">
            <h3><b></b> <em>支付方式</em></h3>
            <ul>
                <li><a href="">货到付款</a></li>
                <li><a href="">在线支付</a></li>
                <li><a href="">分期付款</a></li>
                <li><a href="">邮局汇款</a></li>
                <li><a href="">公司转账</a></li>
            </ul>
        </div>

        <div class="bnav4">
            <h3><b></b> <em>售后服务</em></h3>
            <ul>
                <li><a href="">退换货政策</a></li>
                <li><a href="">退换货流程</a></li>
                <li><a href="">价格保护</a></li>
                <li><a href="">退款说明</a></li>
                <li><a href="">返修/退换货</a></li>
                <li><a href="">退款申请</a></li>
            </ul>
        </div>

        <div class="bnav5">
            <h3><b></b> <em>特色服务</em></h3>
            <ul>
                <li><a href="">夺宝岛</a></li>
                <li><a href="">DIY装机</a></li>
                <li><a href="">延保服务</a></li>
                <li><a href="">家电下乡</a></li>
                <li><a href="">京东礼品卡</a></li>
                <li><a href="">能效补贴</a></li>
            </ul>
        </div>
    </div>
    <!-- 底部导航 end -->

    <div style="clear:both;"></div>
    <!-- 底部版权 start -->
    <div class="footer w1210 bc mt10">
        <p class="links">
            <a href="">关于我们</a> |
            <a href="">联系我们</a> |
            <a href="">人才招聘</a> |
            <a href="">商家入驻</a> |
            <a href="">千寻网</a> |
            <a href="">奢侈品网</a> |
            <a href="">广告服务</a> |
            <a href="">移动终端</a> |
            <a href="">友情链接</a> |
            <a href="">销售联盟</a> |
            <a href="">京西论坛</a>
        </p>
        <p class="copyright">
            © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
        </p>
        <p class="auth">
            <a href=""><img src="<?=Yii::getAlias('@web')?>/images/xin.png" alt="" /></a>
            <a href=""><img src="<?=Yii::getAlias('@web')?>/images/kexin.jpg" alt="" /></a>
            <a href=""><img src="<?=Yii::getAlias('@web')?>/images/police.jpg" alt="" /></a>
            <a href=""><img src="<?=Yii::getAlias('@web')?>/images/beian.gif" alt="" /></a>
        </p>
    </div>
    <!-- 底部版权 end -->

    <script type="text/javascript">
        document.execCommand("BackgroundImageCache", false, true);
    </script>
</body>
</html>