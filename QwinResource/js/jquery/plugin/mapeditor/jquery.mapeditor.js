(function($) {
	/**
	 * map 数据编辑器
	 *
	 * @depend json2.js http://www.JSON.org/json2.js
	 * @depend qui.js Resource/js/jquery/plugin/qui.js
	 * @todo 隐藏 json 数据,以更好的数据形式显示给用户
	 */
	$.fn.mapEditor = function(options) {
		var $this = this;
		$this.opts = $.extend({}, $.fn.mapEditor.defaults, options);
		$this.bind('click', $this, function(){
			
			// textarea 框中数据作为主要来源,其次使用配置的数据
			if('' != $this.val())
			{
				$this.opts.data = JSON.parse($this.val());
			}
			// 初始化html
			init($this);
			

			// 加载已有数据
			for(var key in $this.opts.data)
			{
				addRow($this, key, $this.opts.data[key]);
			}
			// 在已有的基础上增加三行
			$.each( [0,1,2], function(i, n){
				addRow($this);
			}); 
			
			// 增加新行
			$this._html.find('.ui-mapeditor-add').click(function(){
				addRow($this);
			});
			
			// 保存数据
			$this._html.find('.ui-mapeditor-submit').click(function(){
				submit($this);
			});
			
			// 关闭对话框时,移除html内容
			$this.opts.dialog.close = function(event, ui){
				remove($this);
			};
			// 显示对话框
			$this._html.dialog($this.opts.dialog);
		});
		return this;
	};
	
	function init($this)
	{
		// 加载基本的界面
		$this._html = $(['<div class="ui-mapeditor"><form class="ui-mapeditor-form">',
				'<table cellpadding="0" cellspacing="5" width="96%" border="0">',
				'<thead><tr><td>' + $this.opts.language.key  + '</td><td>' + $this.opts.language.value,
				'</td><td>' + $this.opts.language.operate + '</td></tr></thead>',
				'<tbody></tbody><tfoot><tr><td colspan="3">',
				'<input type="button" class="ui-widget-content ui-corner-all ui-mapeditor-add" value="' + $this.opts.language.add +'" />',
				'<input type="button" class="ui-widget-content ui-corner-all ui-mapeditor-submit" value="' + $this.opts.language.submit + '" />',
				'</td></tr></tfoot></table></form></div>'].join(''));
		hoverAndFocus($this._html);
		$('body').append($this._html);
		$this._table = $this._html.find('table > tbody');
	}
	
	function hoverAndFocus(obj)
	{
		if('undefined' != typeof $.fn.qui)
		{
			obj.find('input').qui({focus: true});
		}
	}
	
	/**
	 * 提交处理数据
	 */
	function submit($this)
	{
		var json = {};
		// 在文本框中取值,构建 map 对象
		$('form.ui-mapeditor-form input[type=text]').each(function(i){
			var val = $.trim($(this).val());
			if(0 == i%2)
			{
				key = val;
				// 如果是空,则不加入
				if('' != val)
				{
					json[key] = '';
				}
			} else {
				// 如果键名为空,该行数据无效
				if('undefined' != typeof json[key])
				{
					json[key] = val;
				}
			}
		});
		$this.val(JSON.stringify(json));
		$this._html.dialog('close');
	}
	
	/**
	 * 移除html内容
	 */
	function remove($this)
	{
		$this._html.remove();
	}

	/**
	 * 添加新的一行数据
	 *
	 * @todo td onclick
	 */
	function addRow($this, key, val)
	{
		if('undefined' == typeof key) key = '';
		if('undefined' == typeof val) val = '';
		var html = $(['<tr><td><input type="text" name="key" value="' + key + '" class="ui-widget-content ui-corner-all" /></td>',
					'<td><input type="text" name="value" value="' + val + '" class="ui-widget-content ui-corner-all" /></td>',
					'<td><a onclick="jQuery(this).parent().parent().remove();" class="ui-state-default"><span class="ui-icon ui-icon-circle-close">X</span></a></td></tr>'].join(''));
		hoverAndFocus(html)
		$this._table.append(html);
	}

	$.fn.mapEditor.defaults = {
		dialog: {
			title: 'MapEditor',
			bgiframe: true,
			modal: true,
			width: 300,
			height: 'auto',
			maxHeight: 250
		},
		language: {
			key: 'Key',
			value: 'Value',
			operate: 'Operate',
			add:　'Add a new Row',
			submit: 'Submit'
		},
		data: {
		}
	}
})(jQuery);