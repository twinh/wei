<?php
/**
 * sidebar 的名称
 *
 * sidebar 的简要介绍
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
 * @version   2009-10-31 01:19:12 utf-8 中文
 * @since     2009-11-24 18:47:32 utf-8 中文
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
//print_r($menu_list);
?>
<script type="text/javascript">
jQuery(function($){
	$('.ui-sidebar-title-icon').click(function(){
		var content_name = $(this).attr('name');
		var id_arr = content_name.split('-');
		var id = id_arr[id_arr.length - 1];
		// 注意 ui-box-title-icon 与此代码的位置
		//alert($(content_name).css('display'));
		// 混乱
		if('none' == $(content_name).css('display'))
		{
			var category_state = 0;
		} else {
			var category_state = 1;
		}
		$.ajax({
			type : 'POST',
			url : Qwin.url.auto({
				0 : 'admin',
				1 : '',
				2 : 'toggle_menu_state'
			}),
			data : {
				category_id : id,
				category_state : category_state
			},
			success : function(data){
				//alert(data);
			}
		});
	});
	$('.ui-sidebar-list').qui();
});
</script>
<div class="sidebar">
<?php
foreach($menu_list as &$val)
{
	if(/*!isset($_SESSION['menu'][$val['id']]) || */$val['category_id'] != 0)
	{
		continue;
	}
	// TODO 简化
	// SESSION 强制开关
	if(isset($_SESSION['menu_category'][$val['id']]))
	{
		if($_SESSION['menu_category'][$val['id']] == 0)
		{
			$style = 'style="display:none;"';
			$class = 'ui-icon-circle-triangle-s';
		} else {
			$style = '';
			$class = 'ui-icon-circle-triangle-n';
		}
	// 默认配置控制开关
	} elseif($val['is_show'] != 2001001) {
		$style = ' style="display:none;"';
		$class = 'ui-icon-circle-triangle-s';
	} else {
		$style = '';
		$class = 'ui-icon-circle-triangle-n';
	}
?>
	<div class="ui-siderbar ui-box ui-widget ui-widget-content ui-corner-all">
		<div class="ui-sidebar-titlebar ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix" id="ui-sidebar-title-<?php echo $val['id']?>">
			<span class="ui-box-title"><?php echo $val['title']?></span>
			<a class="ui-sidebar-title-icon ui-box-title-icon ui-corner-all" name="#ui-sidebar-content-<?php echo $val['id']?>" href="javascript:void(0)"><span class="ui-icon <?php echo $class?>">open/close</span></a>
		</div>
		<div class="ui-box-content ui-widget-content">
			<ul id="ui-sidebar-content-<?php echo $val['id']?>"<?php echo $style?>>
<?php
	foreach($menu_list as &$val_2)
	{
		/*if(!isset($_SESSION['menu'][$val_2['id']]))
		{
			continue;
		}*/
		if($val_2['category_id'] == $val['id'])
		{
?>
				<li class="ui-sidebar-list ui-widget-content"><a href="<?php echo $val_2['url']?>" target="<?php echo $val_2['target']?>"><?php echo $val_2['title']?></a></li>
<?php
			unset($val_2);
		}
	}
	unset($val);
?>
			</ul>
		</div>
	</div>
<?php
} 
?>
</div>