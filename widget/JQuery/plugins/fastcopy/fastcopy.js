/**
 * jQuery fastcopy plugin
 *
 * 对指定对象的快速复制
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-12-13 18:46:00 utf-8 中文
 * @since     2009-12-13 18:46:00 utf-8 中文
 * @todo      限制长度,强制覆盖,提示覆盖等.
 */

if(jQuery)(function($){
	$.extend($.fn, {
		FastCopy: function(o){
		 	var _this = this;
			
			// 要复制的对象,可以是字符串,或对象
			if(!o.from) o.from = 'title';
			if(!o.is_text) o.is_text = false;
			
			// 取值
			if(undefined != $(o.from).attr('type'))
			{
				o.original_value = $(o.from).val();
			} else if(o.is_text) {
				o.original_value = $(o.from).text();
			} else {
				o.original_value = $(o.from).html();
			}
			
			// 赋值
			if(undefined != $(this).attr('type'))
			{
				$(this).val(o.original_value);
			} else {
				$(this).html(o.original_value);
			}
		}
	});
})(jQuery);