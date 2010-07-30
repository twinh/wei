<?php
/**
 * allocatepermissions 的名称
 *
 * allocatepermissions 的简要介绍
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
 * @version   2009-10-31 01:19:12
 * @since     2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
$this->loadView(array('admin', '', 'header'));?>
<div class="content">
<?php $this->loadView(array('admin', '', 'sidebar'));?>
<div class="right">
<style type="text/css">
#list td{
	text-align:left;
}
#list td input{
	margin-left:10px;
}
.namespace{
	font-weight:bold;
}
.controller{
	width:20px;
	float:left;
	font-weight:bold;
}
.action{
	width:40px;
	float:left;
}
.submit{
	float:right;
	margin-right:40px;
}
.field_value td a{
	margin:4px;
}
</style>
<script type=text/javascript>
$(function(){
	$('#checked_all').click(function(){
		$(":checkbox").attr('checked', true);
	});
	$('#unchecked_all').click(function(){
		$(":checkbox").attr('checked', false);
	});
	$('#invert_select').click(function(){
		$.each($(":checkbox"), function(){
			if($(this).attr('checked') == true)
			{
				$(this).attr('checked', false);
			} else {
				$(this).attr('checked', true);
			}
		});
	});
});
</script>
<div id="list" class="ui_box">
<form action="<?php url(array('admin', 'group', 'allocatepermissions'))?>" method="post">
<table id="nca" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td style="text-align:left;" class="list_title ui_title">
		<a>权限管理</a>
	</td>
</tr>
<tr class="field_value">
	<td>
		<a href="javascript:void(0);" id="checked_all">全选</a><a href="javascript:void(0);" id="unchecked_all">全不选</a><a href="javascript:void(0);" id="invert_select">反选</a>
		<strong>以下中文由系统自带词库和Google翻译自动生成,仅供参考</strong>
	</td>
</tr>
<?php
foreach($list as $key => $val)
{
	// namsespace
	if($val['category_id'] == 0)
	{
		if($permissions[$val['name']])
		{
			$checked = ' checked="checked"';
		} else {
			$checked = '';
		}
		!$val['descrip'] && $val['descrip'] = $val['name'];
?>
		<tr class="namespace field_value"><td>
			<input type="checkbox" name="nca[<?php echo $val['name']?>]" value="1" <?php echo $checked?> />
			<a href="<?php echo url(array($val['name']))?>"><?php echo $val['descrip']?>(<?php echo $val['name']?>)</a>
		</td></tr>
<?php
		unset($list[$key]);
		foreach($list as $key_2 => $val_2)
		{
			// controller
			if($val_2['category_id'] == $val['id'])
			{
				if($permissions[$val['name'] . '|' . $val_2['name']])
				{
					$checked = ' checked="checked"';
				} else {
					$checked = '';
				}
				!$val_2['descrip'] && $val_2['descrip'] = $val_2['name'];
?>
		<tr class="field_value"><td>
			<div class="controller"></div>
			<input type="checkbox" name="nca[<?php echo $val['name']?>|<?php echo $val_2['name']?>]" value="1" <?php echo $checked?> />
			<a href="<?php echo url(array($val['name'], $val_2['name']))?>"><?php echo $val_2['descrip']?>(<?php echo $val_2['name']?>)</a>
		</td></tr>
		<tr class="field_value"><td>
			<div class="action"></div>
<?php
				unset($list[$key_2]);
				foreach($list as $key_3 => $val_3)
				{
					if($val_3['category_id'] == $val_2['id'])
					{
						if($permissions[$val['name'] . '|' . $val_2['name'] . '|' . $val_3['name']])
						{
							$checked = ' checked="checked"';
						} else {
							$checked = '';
						}
						!$val_3['descrip'] && $val_3['descrip'] = $val_3['name'];
?>
			<input type="checkbox" name="nca[<?php echo $val['name']?>|<?php echo $val_2['name']?>|<?php echo $val_3['name']?>]" value="1" <?php echo $checked?> />
			<a href="<?php echo url(array($val['name'], $val_2['name'], $val_3['name']))?>"><?php echo $val_3['descrip']?>(<?php echo $val_3['name']?>)</a>
<?php
						unset($list[$key_3]);
					}
					
				}
?>
		</td></tr>
<?php
			}
		}
	}
}
?>
	<tr class="field_value">
		<td>
			<input type="hidden" name="id" value="<?php echo qw('-ini')->g('id')?>" />
			<input class="submit" type="submit" value="提交" />
		</td>
	</tr>
</table>
</form>
</div>
</div>
</div>
<?php $this->loadView(array('admin', '', 'footer')); ?>
