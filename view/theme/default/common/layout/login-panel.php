<?php
/**
 * LoginPanel
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
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
 * @version   2010-5-24 7:40:48
 * @since     2010-5-24 7:40:48
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_config['interface']['charset'] ?>" />
<title><?php echo qw_lang('LBL_HTML_TITLE')?></title>
<!-- Qwin_Packer_Css -->
<!-- Qwin_Packer_Js -->
<?php
$jQueryFile = array(
    'core' => $jquery->loadUi('core', false),
    'widget' => $jquery->loadUi('widget', false),
    'button' => $jquery->loadUi('button', false),
    'coreEffect' => $jquery->loadEffect('core', false),
    'qui' => $jquery->loadPlugin('qui', null, false),
);
$cssPacker
    ->add(QWIN_RESOURCE_PATH . '/view/theme/qwin2/style.css')
    ->add(QWIN_RESOURCE_PATH . '/view/theme/qwin2/login.css')
    ->add($jquery->loadTheme(null, false))
    ->add($jQueryFile['core']['css'])
    ->add($jQueryFile['widget']['css'])
    ->add($jQueryFile['button']['css'])
    ->add($jQueryFile['qui']['css']);
$jsPacker
    ->add($jquery->loadCore(false))
    ->add(QWIN_RESOURCE_PATH . '/view/theme/qwin2/style.js')
    ->add($jQueryFile['core']['js'])
    ->add($jQueryFile['widget']['js'])
    ->add($jQueryFile['button']['js'])
    ->add($jQueryFile['coreEffect'])
    ->add($jQueryFile['qui']['js']);
?>
</head>
<body>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function($){
        var capthca = $('#login-captcha');
        var captchaScr = capthca.attr('src');
        capthca.qui().click(function(){
            capthca.attr('src', captchaScr + '&' + new Date());
        });
    });
</script>
<div id="ui-header" class="ui-header ui-widget">
  <div class="ui-header-logo ui-widget-content"> <a href="">Qwin</a> </div>
</div>
<div class="ui-line ui-widget-content">&nbsp;</div>
<div class="ui-login-panel">
	<div id="ui-main-content" class="ui-main-content" >
  <div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"> <span class="ui-box-title"> <a href=""><?php echo qw_lang('LBL_LOGIN_TITLE')?></a> </span> </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
      <form id="login-form" name="form" method="post" action="">
        <table width="100%" cellpadding="4" cellspacing="10">
        	<tr>
                <td class="login-label" width="60"><label for="username"><?php echo qw_lang('LBL_FIELD_USERNAME')?>:</label></td>
                <td class="login-input" colspan="2"><?php echo qw_form($this->meta['field']['username']['form'], $_POST) ?></td>
            </tr>
            <tr>
            	<td class="login-label"><label for="password"><?php echo qw_lang('LBL_FIELD_PASSWORD')?>:</label></td>
                <td class="login-input" colspan="2"><?php echo qw_form($this->meta['field']['password']['form']) ?></td>
            </tr>
            <tr>
            	<td class="login-label"><label for="captcha"><?php echo qw_lang('LBL_FIELD_CAPTCHA')?>:</label></td>
                <td class="login-input"><?php echo qw_form($this->meta['field']['captcha']['form'], $_POST) ?></td>
                <td><img class="login-captcha ui-state-default ui-corner-all" id="login-captcha" alt="captcha image" src="?_entrance=captcha" /></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            	<td colspan="2">
                        <div class="ui-field-common ui-login-operation">
                            <input type="hidden" name="_action" value="<?php echo $this->_set['action']?>" />
                            <input type="hidden" name="_page" value="<?php echo qw_referer_page() ?>" />
                            <input type="submit" class="ui-form-button ui-state-default ui-corner-all" id="submit" value="<?php echo qw_lang('LBL_ACTION_SUBMIT')?>" />
                            <input type="reset" class="ui-form-button ui-state-default ui-corner-all" value="<?php echo qw_lang('LBL_ACTION_RESET')?>" />
                        </div>
                </td>
            </tr>
        </table>
      </form>
    </div>
  </div>
</div>
</div>
<div class="ui-footer ui-widget">
  <div class="ui-copyright ui-widget-content"><?php echo qw_lang('LBL_FOOTER_COPYRIGHT')?></div>
</div>
</body>
</html>
