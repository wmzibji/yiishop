/*
 @功能：购物车页面js
 @作者：diamondwang
 @时间：2013年11月14日
 */

$(function(){

    //减少
    $(".reduce_num").click(function(){
        var amount = $(this).parent().find(".amount");
        if (parseInt($(amount).val()) <= 1){
            alert("商品数量最少为1");
        } else{
            $(amount).val(parseInt($(amount).val()) - 1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('data-id');
        changeNum(goods_id,amount.val());
    });

    //增加
    $(".add_num").click(function(){
        var amount = $(this).parent().find(".amount");
        $(amount).val(parseInt($(amount).val()) + 1);
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('data-id');
        changeNum(goods_id,amount.val());
    });

    //直接输入
    $(".amount").blur(function(){
        if (parseInt($(this).val()) < 1){
            alert("商品数量最少为1");
            $(this).val(1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = parseFloat($(this).text());
        $(".col5 span").each(function(){
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        var goods_id = $(this).closest('tr').attr('data-id');
        changeNum(goods_id,$(this).val());
    });
    //默认总金额
    var total = 0;
    $(".col5 span").each(function(){
        var a=(parseFloat($(this).text()).toFixed(2));
        total += parseFloat(a).toFixed(2);
    });
    $("#total").text(parseFloat(total).toFixed(2));
});
var changeNum = function(goods_id,amount){
    $.post('ajax-cart',{goods_id:goods_id,amount:amount},function(data){
        console.log(data);
    });
};