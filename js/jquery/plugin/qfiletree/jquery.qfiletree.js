// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           script         - location of the serverside AJAX file to use; default = jqueryFileTree.php
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// jQuery File Tree is licensed under a Creative Commons License and is copyrighted (C)2008 by Cory S.N. LaViska.
// For details, visit http://creativecommons.org/licenses/by/3.0/us/
//
if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.script == undefined ) o.script = 'jqueryFileTree.php';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			// 增加双击文件夹事件
			// todo 
			if( o.dblClickFolder == undefined ) o.dblClickFolder = function(){}
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post(o.script, { dir: t }, function(data) {
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });
						bindTree(c);
					});
				}
				
				function bindTree(t) {
					// 双击
					$(t).find('LI.directory')
					.attr('title', '双击执行绑定事件');
					
					$(t).find('LI.directory A')
					.bind('dblclick', function(){
						o.dblClickFolder($(this).attr('rel'));
					})
					$(t).find('LI A').bind(o.folderEvent, function() {
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
								$(this).parent().removeClass('collapsed').addClass('expanded');
							} else {
								// Collapse
								$(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
						} else {
							h($(this).attr('rel'));
						}
						return false;
					});
					// Prevent A from triggering the # on non-click events
					if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
					
					// hover img
					$('body').append('<div class="QFTimg"></div>');
					$(t).find('LI').bind("mouseenter", function(e){
						var b = $(this).css('background-image');
						b = b.slice(-13, -6);
						// ie/op url("..")
						// ff url(..)
						if(b == 'picture' || b == '/pictur')
						{
							var a = $(this).find('A');
							var u = a.attr('rel');
							var os = a.offset();
							var h = a.height();
							var ie6 = '';
							if ($.browser.msie)
							{
								if($.browser.version < 7.0)
								{
									ie6 = 'width="200"';
								}
							}
							$('.QFTimg').html('<img ' + ie6 + ' src="' + u +'" />');
							$(".QFTimg").css({
								'display' : 'block',
								'left' : os.left + 'px',
								'top' : (os.top + h) + 'px'
							});
						}
					}).bind("mouseleave", function(){
						$(".QFTimg").css({
								'display' : 'none'
						});
						//$(".QFTimg").html('');
					});
				}
				// Loading message
				$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				showTree( $(this), escape(o.root) );
			});
		}
	});
	
})(jQuery);
// by twin 2009-04-04
// lastupdate 2009-10-31
// require jquery.js
// require jqueryFileTree.js
// require dialog
if(jQuery)(function($){
	$.extend($.fn, {
		QFileTree: function(o){
			var _this = this;
			if(!o) var o = {};
			// 自己配置
			if(!o.type) o.type = 'click';
			if(!o.seleted)
			{
				o.seleted = function(file)
				{
					if('text' == $(_this).attr('type'))
					{
						$(_this).val(file);
					// 指定表单
					} else if(undefined != o.input) {
						$(o.input).val(file);
					} else {
						alert('选择的文件为: ' + file);
					}
					$('#qfiletree_dialog').dialog('close');
				}
			}
			
			// 对话框配置
			if(!o.dialog) o.dialog = {};
			if(!o.dialog.title) o.dialog.title = 'QFileTree 树形文件浏览器';
			if(!o.dialog.width) o.dialog.width = 300;
			if(!o.dialog.height) o.dialog.height = 300;
			if(!o.dialog.bgiframe) o.dialog.bgiframe = true;
			if(!o.dialog.modal) o.dialog.modal = true;
			o.dialog.autoOpen = false;
			
			// filetree 配置
			if(!o.filetree) o.filetree = {};
			if(!o.filetree.root) o.filetree.root = 'upload/';
			if(!o.filetree.script) o.filetree.script = '?namespace=Default&module=File&controller=JQuery&action=FileTree';
			if(!o.filetree.dblClickFolder)
			{
				o.filetree.dblClickFolder = function(path)
				{
					alert(path)
				}
			}
			
			
			
			// 初始化
			if($('#qfiletree_dialog').html() == null)
			{
				$('body').append('<div id="qfiletree_dialog"></div>');
			}
			// 加载 dialog
			$('#qfiletree_dialog').dialog(o.dialog);
			$(_this).bind(o.type, function(){
				$(_this).blur();
				$('#qfiletree_dialog').dialog('open');
				$('#qfiletree_dialog').fileTree(o.filetree, o.seleted);
			});
		}
	});
})(jQuery);