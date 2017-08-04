/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/
$(function(){
	//收货人修改
	$("#address_modify").click(function(){
		$(this).hide();
		$(".address_info").hide();
		$(".address_select").show();
	});

	$(".new_address").click(function(){
		$("form[name=address_form]").show();
		$(this).parent().addClass("cur").siblings().removeClass("cur");

	}).parent().siblings().find("input").click(function(){
		$("form[name=address_form]").hide();
		$(this).parent().addClass("cur").siblings().removeClass("cur");
	});
	//送货方式修改
	$("#delivery_modify").click(function(){
		$(this).hide();
		$(".delivery_info").hide();
		$(".delivery_select").show();
	})

	$("input[name=delivery]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//支付方式修改
	$("#pay_modify").click(function(){
		$(this).hide();
		$(".pay_info").hide();
		$(".pay_select").show();
	})

	$("input[name=pay]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//发票信息修改
	$("#receipt_modify").click(function(){
		$(this).hide();
		$(".receipt_info").hide();
		$(".receipt_select").show();
	})

	$(".company").click(function(){
		$(".company_input").removeAttr("disabled");
	});

	$(".personal").click(function(){
		$(".company_input").attr("disabled","disabled");
	});

//默认选中第一个
	$('p input:first').attr('checked','checked');
	//选择时赋予checked，清除其他的
	$("input[name='address']").click(function () {
		$('.address input').removeAttr('checked');
		$(this).attr('checked','checked');
	});
//默认选中第一个
	$('.delivery_select input:first').attr('checked','checked');
//选择时赋予checked，清除其他的
	$("input[name='delivery']").click(function () {
		$('.delivery_select input').removeAttr('checked');
		$(this).attr('checked','checked');
	});
//默认选中第一个
	$('.col1 input:first').attr('checked','checked');
//选择时赋予checked，清除其他的
	$("input[name='pay']").click(function () {
		$('.pay_select input').removeAttr('checked');
		$(this).attr('checked','checked');
	});

$("#submit-btn").click(function(){
    var address_id=$("input[name='address']:checked").val();
    var delivery_id=$("input[name='delivery']:checked").val();
    var payment_id=$("input[name='pay']:checked").val();
    $.post(
        'order',
        {delivery_id:delivery_id,payment_id:payment_id, address_id:address_id},
        function (data) {
            console.log(data);
            console.log(address_id);
            console.log(delivery_id);
            console.log(payment_id);
            if(data == 'success'){
                window.location.href="/member/order1";
            }else {
                alert('订单失败');
            }
        }
    );
});
});