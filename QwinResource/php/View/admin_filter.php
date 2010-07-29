<?php
/**
 * form 的名称
 *
 * form 的简要介绍
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
?>
<div id="form" class="ui_box">
	<div class="ui_title form_title">
            	<a href="<?php echo url(array('admin', $this->__query['controller'])); ?>"><?php echo $this->__meta['page']['title']?></a>
				<!-- <?php echo $this->__meta['page']['descrip']?> -->
    </div>
<form id="post_form" name="form" method="post" action="<?php echo url(array('admin', $this->__query['controller'], $this->__query['action']))?>">
<?php
// 构建表单
foreach($set['field'] as $val)
{
	switch($val['form']['_type'])
	{
		case 'custom' :
			continue;
			break;
		case 'hidden' :
		case 'qselect' :
			$val['form']['_type'] = 'text';
			break;
		// 为 selcet 增加空值
		case 'select' :
		case 'radio' :
			if(!isset($val['form']['_resource']['']))
			{
				$add_arr = array('' => '空');
				$val['form']['_resource'] = $add_arr + $val['form']['_resource'];
			}
			break;			
	}
	// 删除初始值
	$val['form']['_value'] = '';
?>
<p class="block_<?php echo $val['form']['_type']?> block_common" id="block_<?php echo $val['form']['name']?>">
	<span class="label_<?php echo $val['form']['_type']?> label_common" id="label_<?php echo $val['form']['name']?>"><?php echo $val['basic']['title']?>:</span>
	<span class="field_<?php echo $val['form']['_type']?> field_common" id="field_<?php echo $val['form']['name']?>"><?php echo qwForm($val['form'], $data)?></span>
	<span class="icon_common ui-widget ui-helper-clearfix" id="icon_<?php echo $val['form']['name']?>"></span>
</p>
<?php
}
?>
<div class="block_common">
	<div class="field_hidden"><input type="hidden" name="_action" value="<?php echo $this->__query['action']?>" /></div>
</div>
<div class="block_common">
	<div class="field_submit"><input type="submit" value="筛选" /></div>
</div>
</form>
</div>
