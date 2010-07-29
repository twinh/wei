// JavaScript Document
jQuery(function($){
	$('a.ui-box-title-icon')
	.qui()
	.click(function(){
		// 切换按钮
		var icon_obj = $(this).find('span');
		if(icon_obj.hasClass('ui-icon-circle-triangle-n'))
		{
			icon_obj.removeClass("ui-icon-circle-triangle-n").addClass("ui-icon-circle-triangle-s");
		} else {
			icon_obj.removeClass("ui-icon-circle-triangle-s").addClass("ui-icon-circle-triangle-n");
		}
		// 显示/隐藏指定内容
		var name = $(this).attr('name');
		if('' != name)
		{
			$(name).slideToggle('fast');
		}
	});
});


