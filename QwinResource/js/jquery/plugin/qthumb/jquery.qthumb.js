/**
 * jQuery qthumb plugin
 *
 * 缩略图控制面板
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-12-14 02:34:00 utf-8 中文
 * @since     2009-12-14 02:34:00 utf-8 中文
 */

if(jQuery)(function($){
	$.extend($.fn, {
		QThumb: function(o){
		 	var _this = this;
			if(!o) o = {};
			// 默认配置
			if(!o.input) o.input = '#url';
			if(!o.width) o.width = 80;
			if(!o.height) o.height = 80;
			if(!o.url) o.url = 'admin.php?namespace=admin&controller=file&action=thumb';
			
			// 对话框配置
			if(!o.dialog) o.dialog = {};
			if(!o.dialog.title) o.dialog.title = 'QThumb 缩略图控制面板';
			if(!o.dialog.width) o.dialog.width = 300;
			if(!o.dialog.height) o.dialog.height = 300;
			if(!o.dialog.bgiframe) o.dialog.bgiframe = true;
			if(!o.dialog.modal) o.dialog.modal = true;
			o.dialog.autoOpen = false;
			
			// 初始化
			if($('#qthumb_dialog').html() == null)
			{
				$('body').append('<div id="qthumb_dialog"></div>');
			}
			$('#qthumb_dialog').dialog(o.dialog);
			$(_this).bind('click', function(){
				$('#qthumb_dialog')
				.html('<p>宽 <input class="qthumb_input" id="qthumb_width" type="text" /></p><p>高 <input class="qthumb_input" id="qthumb_height" type="text" /></p><p><input type="button" value="生成" id="qthumb_button" /></p>')
				.dialog('open');

				$('#qthumb_width').val(o.width);
				$('#qthumb_height').val(o.height);
				
				$('#qthumb_button').bind('click', function(){
					var qthumb_width = parseInt($('#qthumb_width').val());
					var qthumb_height = parseInt($('#qthumb_height').val());
					if(qthumb_width < 0 || isNaN(qthumb_width))
					{
						qthumb_width = o.width;
					}
					if(qthumb_height < 0 || isNaN(qthumb_height))
					{
						qthumb_height = o.height;
					}
					$.getJSON(o.url, {
						width : qthumb_width,
						heigth : qthumb_height,
						url : $(o.input).val(),
						t : new Date()
					}, function(json){
						alert(json.msg);
						if((json.error == 0))
						{
							$(o.input).val(json.file_name);
							$('#qthumb_dialog').dialog('close');
						}
					});
				});
			});
		}
	});
})(jQuery);