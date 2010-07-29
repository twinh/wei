/* utf-8 编码 */
/* 2009-11-14 by twin */
if(jQuery)(function($){
	$.extend($.fn, {
		QInitalContent : function(o) {
			if(!o) var o = {};
			if(o.value == undefined) o.value = '*';			
			if(o.form == undefined) o.form = 'form';
			if(o.hover_class == undefined) o.hover_class = 'hover_class';
			
			var _this = this;
			if($(_this).val() == '')
			{
				$(_this).val(o.value)
			}
			$(_this)
			.bind('focus', function(){
				if($(_this).val() == o.value)
				{
					$(_this).val('');
				}
				$(_this).addClass(o.hover_class);
			})
			.bind('blur', function(){
				if($(_this).val() == '')
				{
					$(_this).val(o.value);
					$(_this).removeClass(o.hover_class);
				}
			});
			
			$(o.form).bind('submit', function(){
				if($(_this).val() == o.value)
				{
					$(_this).val('');
				}
			});
		}
	});
})(jQuery);