// by twin 
// 2009-04-06 2009-10-31
// TODO 多个实例
var qUploadClickedFunction;
var qUploadFilePath;
function qUploadCallback(id, file)
{
	qUploadFilePath = file;
	var msg;
	switch(id)
	{
		case 0 :
			msg = '<span style="color:green">上传成功! <a href="#">确定</a></span>';
			break;
		case 1 :
			msg = '没有文件被上传!';
			break;
		case 2 :
		case 5 :
			msg = '非法文件扩展名!';
			break;
		case 3 :
			msg = '文件大小超过限制!';
			break;
		case 4 :
			msg = '无法移动的上传文件!';
			break;
		default :
			msg = '';
			break;
	}
	$('#qupload_message').html(msg);
	if(id == 0)
	{
		$('#qupload_message span a').bind('click', function(){
			$('#qupload_dialog').dialog('close');
		});
	}
}

if(jQuery)(function($){
	$.extend($.fn, {
		QUpload: function(o) {
			var _this = this;
			if(!o) var o = {};
			if(!o.dialog)
			{
				o.dialog = {
					title : 'File Upload',
					width : 400,
					autoOpen : false,
					bgiframe : true,
					modal : true
				}
			}
			if(!o.type) o.type = 'click';
			if(!o.name)
			{
				var name = $(_this).attr('name');
				if(name != null)
				{
					o.name = name;
				} else {
					o.name = 'file';
				}
			}
			if(!o.url) o.url = 'admin.php?namespace=admin&controller=file&action=upload&name=' + o.name;
			if(!o.clicked)
			{
				o.clicked = function()
				{
					$(_this).val(qUploadFilePath);
				}
			}
			
			// 点击确定
			o.dialog.close = o.clicked;
			
			// 初始化
			if($('#qupload_dialog').html() == null)
			{
				$('body').append('<div id="qupload_dialog"></div>');
			}
			$('#qupload_dialog').dialog(o.dialog);
			$(_this).bind(o.type, function(){
				$('#qupload_dialog').html('<div class="ui-widget qupload_message_box"><div class="ui-state-highlight ui-corner-all"><p><span class="ui-icon ui-icon-info qupload_message_icon"></span><strong>上传信息: </strong><span id="qupload_message">等待上传</span></span></p></div></div><div class="qupload_form_box"><form name="qupload_form" id="qupload_form" action="" method="post" enctype="multipart/form-data" target="qupload_target"><input type="file" id="qupload_field_name" name="" /><input type="submit" value="上传" /><iframe class="qupload_target" id="qupload_target" name="qupload_target" src="#" ></iframe></form></div>');
				
				$('#qupload_form').attr('action', o.url);
				$('#qupload_field_name').attr('name', o.name);
				
				$(_this).blur();
				$('#qupload_dialog').dialog('open');
			});
		}
	});
})(jQuery);