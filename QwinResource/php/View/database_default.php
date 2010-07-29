<?php
/**
 * default 的名称
 *
 * default 的简要介绍
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


$this->loadView(array('admin', '', 'header'));?>
<div class="content">
<?php $this->loadView(array('admin', '', 'sidebar'));?>
<div class="right">
<div id="list" class="ui_box">
<form action="<?php echo url(array('admin', 'database', 'backup'))?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td class="ui_title" colspan="4" style="text-align:left;">
            	<a>数据库</a>
            </td>
        </tr>
		<tr class="field_title">
			<td>名称</td>
			<td>创建时间</td>
			<td width="2%">&nbsp;</td>
			<td width="2%">&nbsp;</td>
		</tr>
<?php
foreach($sql_file as $val){
?>
        <tr class="field_value">
			<td><a href="<?php echo 'cache/sql/' . $val['title']?>"><?php echo $val['title']?></a></td>
			<td><?php echo date('Y-m-d H:i:s', $val['time'])?></td>
			<td width="2%"><a onclick="return confirm('导入后不可还原,确定要导入?')" href="<?php echo url(array('admin', 'database', 'import'), array('file' => $val['title']))?>"><img src="public/image/admin/import.gif" alt="导入" /></a></td>
			<td width="2%"><a onclick="return confirm('确定要删除?')" href="<?php echo url(array('admin', 'database', 'delete'), array('file' => $val['title']))?>"><img src="public/image/admin/delete.gif" alt="delete" /></a></td>
		</tr>
<?php
}
?>
    </table>
</form>
</div>
</div>
</div>
<?php
$this->loadView(array('admin', '', 'footer'));