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
 * @version   2010-05-24 07:40:48
 * @since     2010-05-24 07:40:48
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $config['interface']['charset'] ?>" />
<title><?php echo qw_t('LBL_HTML_TITLE') ?></title>
<?php
echo $this->getPackerSign();
$member = Qwin::call('-session')->get('member');
$nickname = isset($member['contact']) ? $member['contact']['nickname'] : $member['username'];
$minify->addArray(array(
    $style->getCssFile(),
    $this->getTag('root') . 'com/style/style.css',
    $this->getTag('root') . 'com/style/login.css',
    QWIN . 'image/iconx.css',
    $jQuery->loadCore(false),
    $jQuery->loadUi('core', false),
    $jQuery->loadUi('widget', false),
    $jQuery->loadUi('button', false),
    $jQuery->loadEffect('core', false),
    $jQuery->loadPlugin('qui', null, false),
    QWIN . 'js/qwin.js',
    $this->getTag('root') . 'com/script/style.js',
));
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
        $('#qw-login-form .qw-login-input input').qui();
    });
</script>
<div id="ui-main" class="ui-main ui-widget-content ui-corner-all">
<table id="ui-main-table" border="0" cellpadding="0" cellspacing="0">
    <tr id="ui-header" class="ui-header ui-widget">
        <td id="ui-header-left" colspan="2">
            <div class="ui-header-logo ui-widget-content">
                <a href="?">
                    <img src="<?php echo $this->getTag('root') ?>com/image/logo.png" alt="logo" />
                </a>
            </div>
        </td>
        <td colspan="2" id="ui-header-middle">
            <div class="ui-header-shortcut" id="ui-header-shortcut">
                <a class="ui-state-default" href="<?php echo qw_u('com/member/my') ?>"><?php echo qw_t('LBL_WELCOME') ?>, <?php echo $nickname ?>!</a>
                <?php
                if('guest' == $member['username']):
                ?>
                <a class="ui-state-default" href="<?php echo qw_u('com/member/auth', 'login') ?>"><?php echo qw_t('LBL_LOGIN') ?></a>
                <?php
                else :
                ?>
                <a class="ui-state-default" href="<?php echo qw_u('com/member/auth', 'logout') ?>"><?php echo qw_t('LBL_LOGOUT') ?></a>
                <?php
                endif;
                ?>
            </div>
        </td>
    </tr>
    <tr id="ui-header2">
        <td colspan="4">
            <div class="ui-navbar2 ui-widget-content ui-state-default"></div>
        </td>
    </tr>
    <tr>
        <td class="qw-login" colspan="4">
<div class="qw-login-panel ui-widget">
    <h3 class="ui-state-default ui-corner-top"><a><?php echo qw_lang('LBL_LOGIN_TITLE')?></a></h3>
    <div class="qw-login-content ui-widget-content">
    <form id="qw-login-form" name="form" method="post" action="">
        <table width="100%" cellpadding="4" cellspacing="20">
            <tr>
                <td class="qw-login-label"><label for="username"><?php echo qw_lang('FLD_USERNAME')?>:</label></td>
                <td class="qw-login-input"><?php echo qw_form($meta['field']['username']['form'], $request['username']) ?></td>
            </tr>
            <tr>
                <td class="qw-login-label"><label for="password"><?php echo qw_lang('FLD_PASSWORD')?>:</label></td>
                <td class="qw-login-input"><?php echo qw_form($meta['field']['password']['form']) ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                        <div class="qw-login-operation">
                            <input type="hidden" name="_page" value="<?php echo $this->getRefererPage() ?>" />
                            <input type="submit" class="qw-login-buttun ui-state-default ui-corner-all" id="submit" value="<?php echo qw_t('ACT_SUBMIT')?>" />
                            <input type="reset" class="qw-login-buttun ui-state-default ui-corner-all" value="<?php echo qw_t('ACT_RESET')?>" />
                        </div>
                </td>
            </tr>
        </table>
      </form>
    </div>
</div>
        </td>
    </tr>
</table>
</div>
<div id="ui-floating-footer" class="ui-state-default">
    <div id="ui-footer-arrow" class="ui-icon ui-icon-arrowthickstop-1-n"></div>
    <div class="ui-footer-time"></div>
    <div class="ui-copyright ui-widget">Executed in <?php echo Qwin_Application::getInstance()->getEndTime() ?>(s). <?php echo qw_t('LBL_FOOTER_COPYRIGHT') ?></div>
</div>
</body>
</html>
