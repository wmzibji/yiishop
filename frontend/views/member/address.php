<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>收货地址</title>
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/base.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/global.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/header.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/home.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/address.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/footer.css" type="text/css">
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/address.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/header.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/home.js"></script>
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
                    <div class="prompt">
                        您好，请<a href="">登录</a>
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
                    <a href="">去购物车结算</a>
                    <b></b>
                </dt>
                <dd>
                    <div class="prompt">
                        购物车中还没有商品，赶紧选购吧！
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
        <div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
            <div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
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
        <!--  商品分类部分 end---->

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

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach($model1s as $model1):?>
                <dl>
                    <dt><?=$model1['name']?> <?=$model1['province']?> <?=$model1['city']?> <?=$model1['area']?> <?=$model1['detailed_address']?> <?=$model1['tel']?> </dt>
                    <dd>
                        <a href="">修改</a>
                        <a href="">删除</a>
                        <a href="">设为默认地址</a>
                    </dd>
                </dl>
            <?php endforeach;?>
            <dl class="last"> <!-- 最后一个dl 加类last -->
                <dt>2.许坤 四川省 成都市 高新区 仙人跳大街 17002810530 </dt>
                <dd>
                    <a href="">修改</a>
                    <a href="">删除</a>
                    <a href="">设为默认地址</a>
                </dd>
            </dl>

        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <!--				<form action="" name="address_form">-->
            <?php $form = \yii\widgets\ActiveForm::begin(['id'=>'address_form']); ?>
            <ul>
                <li id="li_name">
                    <label for=""><span>*</span>收 货 人：</label>
                    <input type="text" name="Address[name]" class="txt" />
                </li>
                <li id="li_province">
                    <label for=""><span>*</span>所在地区：</label>
                    <select name="Address[province]" id="province">
                        <option value="">=请选择省=</option>
                    </select>

                    <select name="Address[city]" id="city">
                        <option value="">=请选择城市=</option>
                    </select>

                    <select name="Address[area]" id="area">
                        <option value="">=请选择区/县=</option>
                    </select>
                </li>
                <li>
                    <label for=""><span>*</span>详细地址：</label>
                    <input type="text" name="Address[detailed_address]" class="txt address"  />
                </li>
                <li>
                    <label for=""><span>*</span>手机号码：</label>
                    <input type="text" name="Address[tel]" class="txt" />
                </li>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="checkbox" name="Address[status]" class="check" value="1"/>设为默认地址
                </li>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="submit" name="" class="address_btn" value="保存" />
                </li>
            </ul>
            <?php \yii\widgets\ActiveForm::end(); ?>
            <!--					</form>-->
        </div>
    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

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
<script>
    //加载省数据
    $.each(address,function(){
        //console.log(this);
        $("#province").append('<option>'+this.name+'</option');
    });
    //选中省
    $("#province").change(function(){
        var current_province = $(this).val();
        //console.log(current_province);
        $.each(address,function(){
            if(current_province == this.name){
                var html = '<option value="">=请选择城市=</option>';
                $.each(this.city,function(){
                    html += '<option>'+this.name+'</option>';
                })
                $("#city").html(html);
                $("#area").html('<option value="">=请选择区/县=</option>');
            }
        });
    });
    //选中城市
    $("#city").change(function(){
        var current_province = $("#province").val();
        var current_city = $("#city").val();
        $.each(address,function(){
            if(current_province == this.name){
                $.each(this.city,function(){
                    if(current_city == this.name){
                        var html = '<option value="">=请选择区/县=</option>';
                        $.each(this.area,function(){
                            html += '<option>'+this+'</option>';
                        });
                        $("#area").html(html);
                    }
                });
            }
        });
    });
    //--------使用AJAX提交表单-----------
    /*$(".address_btn").click(function(){
     $("#address_form p").text("");
     $.post('/member/ajax-address',$("#address_form").serialize(),function(data){
     var json = JSON.parse(data);
     console.log(json);
     if(json.status){
     alert('添加成功！');
     //                        window.location.href="/member/login";
     }/!*else{
     //注册失败-----显示错误信息
     $(json.msg).each(function(i,errors){
     console.log(errors);
     $.each(errors,function(name,error){
     $("#li_"+name+" p").text(error.join(","));
     });
     });
     }*!/
     });
     });*/
</script>
</body>
</html>