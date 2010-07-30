<?php
/**
 * 404 的名称
 *
 * 404 的简要介绍
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
 * @version   2009-11-29 19:57:00
 * @since     2009-11-24 20:24:43
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript">
jQuery(function($){
	qwin_layout.close('west');
	setUiBoxWidth('.ui-layout-center', '.ui-error-box');
	$('.ui-layout-center').resize(function(){
		setUiBoxWidth('.ui-layout-center', '.ui-error-box');
	});
});
</script>
<div class="ui-error-box ui-box ui-widget ui-widget-content ui-corner-all">
	<div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
		<span class="ui-box-title">401 权限不够</span>
		<a class="ui-box-title-icon ui-corner-all" name=".ui-error-box-content" href="javascript:void(0)"><span class="ui-icon ui-icon-circle-triangle-n">open/close</span></a>
	</div>
	<div class="ui-error-box-content ui-box-content ui-widget-content">
		<a href="?">首页</a>
		<a href="<?php echo url(array('Admin', '', 'Login'))?>">登录页</a>
	</div>
</div>
