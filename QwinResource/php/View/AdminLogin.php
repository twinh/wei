<?php
/**
 * login 的名称
 *
 * login 的简要介绍
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
qw('-rsc')->load('js/other/form');

// 增加 jquery ui class
qw('-str')->set($set['field']['']['username']['form']['class']);
qw('-str')->set($set['field']['']['password']['form']['class']);
qw('-str')->set($set['field']['']['verify_code']['form']['class']);
$set['field']['']['username']['form']['class'] .= ' ui-widget-content ui-corner-all';
$set['field']['']['password']['form']['class'] .= ' ui-widget-content ui-corner-all';
$set['field']['']['verify_code']['form']['class'] .= ' ui-widget-content ui-corner-all';
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all">
	<div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"> <span class="ui-box-title"><a href="?namespace=admin&controller=default&action=default">用户组</a></span> <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)"><span class="ui-icon  ui-icon-circle-triangle-n">open/close</span></a> </div>
	<div class="ui-form-content ui-box-content ui-widget-content">
		<form id="post_form" name="form" method="post" action="?namespace=admin&controller=default&action=login">
			<fieldset>
				<legend>默认组</legend>
				&nbsp;
				<div class="ui-fieldset-content">
					<div class="ui-block-common ui-block-text" id="ui-block-username">
						<div class="ui-widget-content ui-label-common ui-label-text" id="ui-label-username">
							<label for="username">用户名:</label>
						</div>
						<div class="ui-field-common ui-field-text" id="ui-field-username"> <?php echo qwForm($set['field']['']['username']['form'])?> </div>
					</div>
					<div class="ui-block-common ui-block-password" id="ui-block-password">
						<div class="ui-widget-content ui-label-common ui-label-password" id="ui-label-password">
							<label for="password">密码:</label>
						</div>
						<div class="ui-field-common ui-field-password" id="ui-field-password"> <?php echo qwForm($set['field']['']['password']['form'])?> </div>
						<div class="ui-icon-common ui-widget ui-helper-clearfix ui-state-default"> </div>
					</div>
					<div class="ui-block-common ui-block-text" id="ui-block-verify_code">
						<div class="ui-widget-content ui-label-common ui-label-text" id="ui-label-verify_code">
							<label for="verify_code">验证码:</label>
						</div>
						<div class="ui-field-common ui-field-text" id="ui-field-verify_code"> <?php echo qwForm($set['field']['']['verify_code']['form'])?> </div>
						<div class="ui-icon-common ui-widget ui-helper-clearfix ui-state-default"> <img src="captcha.php?time=<?php echo TIMESTAMP?>" /> </div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>操作</legend>
				&nbsp;
				<div class="ui-fieldset-content">
					<div class="ui-block-common">
						<div class="ui-label-common ui-widget-content"> </div>
						<div class="ui-field-common ui-field-operate">
							<input type="hidden" name="_action" value="<?php echo $this->__query['action']?>" />
							<input type="hidden" name="_page" value="<?php echo urlencode($_SERVER['HTTP_REFERER'])?>" />
							<input type="submit" class="ui-button ui-state-default" value="Submit" />
							<input type="reset" class="ui-button ui-state-default" value="Reset" />
							<input type="button" class="action-return ui-button ui-state-default" value="Return" />
						</div>
						<div class="ui-icon-common ui-widget ui-helper-clearfix ui-state-default"> </div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?php
//echo $this->__html->packAll();
//echo $this->__js->packAll();
?>
