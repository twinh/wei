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
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function($){
        var capthca = $('#login-captcha');
        var captchaScr = capthca.attr('src');
        capthca.qui().click(function(){
            capthca.attr('src', captchaScr + '&' + new Date());
        });
        $('#qw-login-form .qw-login-input input')
            .qui()
            .addClass('ui-widget-content ui-corner-all');
    });
</script>
<div class="qw-middle ui-widget-content">
    <div class="qw-middle-header">
    	<?php Qwin::hook('ViewContentHeader', array('view' => $this)) ?>
    </div>
    <div class="qw-middle-content">
        <div class="qw-login-panel ui-widget">
            <h3 class="ui-state-default ui-corner-top"><a><?php echo $lang['LBL_LOGIN_TITLE'] ?></a></h3>
            <div class="qw-login-content ui-widget-content">
            <form id="qw-login-form" name="form" method="post" action="">
                <table class="qw-form-table" width="100%">
                    <tr>
                        <td class="qw-login-label"><label for="username"><?php echo $lang['FLD_USERNAME'] ?>:</label></td>
                        <td class="qw-login-input"><?php echo qw_form($meta['form']['fields']['username'], $request['username']) ?></td>
                    </tr>
                    <tr>
                        <td class="qw-login-label"><label for="password"><?php echo $lang['FLD_PASSWORD'] ?>:</label></td>
                        <td class="qw-login-input"><?php echo qw_form($meta['form']['fields']['password']) ?></td>
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
    </div>
</div>

