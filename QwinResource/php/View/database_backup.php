<?php
/**
 * backup 的名称
 *
 * backup 的简要介绍
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
$this->loadView(array('admin', '', 'header'));
echo qw('-rsc')->loadJQueryPlugin('dialog');
?>
<div class="content">
<?php $this->loadView(array('admin', '', 'sidebar'));?>
<style type="text/css">
#dialog{
	display:none;
	padding:40px 0 0 20px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#on_submit').click(function(){
		$("#dialog").dialog({
			title : '请要输入备份的文件的名称',
			bgiframe : true,
			modal : true
		});
	});
}); 
</script>
<div class="right">
<div id="list" class="ui_box">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td class="ui_title" colspan="2" style="text-align:left;">
            	<a>数据库</a>
            </td>
        </tr>
<?php
foreach($table as $val){
?>
        <tr class="field_value">
			<td style="text-align:left; padding-left:8px;"><?php echo $val;?></td>
			<td>&nbsp;</td>
		</tr>
<?php
}
?>
		<tr class="field_value">
			<td>&nbsp;</td>
			<td>
				<input id="on_submit" style="width:60px; height:30px;" type="submit" value="备份" />
			</td>
		</tr>
    </table>
</div>
</div>
</div>
<div id="dialog">
	<form action="<?php echo url(array('admin', 'database', 'backup'))?>" method="post">
		<input type="text" name="file_name" value="" />
		<input type="hidden" name="is_submit" value="1" />
		<input type="submit" value="提交" />
	</form>
</div>
<?php
$this->loadView(array('admin', '', 'footer'));
