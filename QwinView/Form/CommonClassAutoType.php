<?php
/**
 * 通用分类表单视图
 *
 * 通用分类表单视图
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   20110-02-18 13:5 utf-8 中文
 * @since     20110-02-18 13:59 utf-8 中文
 * @todo      radio 赋值问题
 */

?>
<script type="text/javascript">
jQuery(function($){
	var type_id = parseInt($('#code').val().substr(0, 1));
	if(type_id > 4) type_id = 4;
	$('#type_' + (type_id - 1)).attr('checked', true);
	// 替换 code 的第一位
	$('input[name=type]').click(function(){
		var url = Qwin.url.auto({
			0: _get['namespace'],
                        1: _get['module'],
			2: _get['controller'],
			3: 'getMaxCode'
		}, {
			type_id: $(this).val(),
			date: Date() + ''
		});
		$.get(url, function(data){
			$('#code').val(data);
		}); 
	});
});
</script>
