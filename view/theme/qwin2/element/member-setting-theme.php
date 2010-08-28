<?php
/**
 * Style
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
 * @version   2010-5-23 0:34:08
 * @since     2010-5-23 0:34:08
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript">
jQuery(function($){
    $('div.ui-theme-list ul li').qui();
});
</script>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <a href="<?php echo qw_url(array('module' => 'Style', 'controller' => 'Theme')) ?>"><?php echo qw_lang('LBL_THEME')?></a>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content ui-theme-list">
        <div class="ui-theme-operation ui-operation-field">
            <button class="ui-action-submit" type="submit"><?php echo qw_lang('LBL_ACTION_SUBMIT') ?></button>
            <a class="ui-action-return" href="javascript:history.go(-1);"><?php echo qw_lang('LBL_ACTION_RETURN') ?></a>
            <input type="hidden" name="_submit" value="1" />
        </div>
        <hr class="ui-line ui-widget-content" />
        <ul>
<?php
foreach($theme as $row){
    $url = qw_url($this->_set, array('style' => $row['path_name']));
?>
            <li class="ui-widget-content ui-corner-all">
                <a href="<?php echo $url?>">
                    <img alt="<?php echo $row['name']?>" src="<?php echo QWIN_RESOURCE_PATH?>/js/jquery/image/<?php echo $row['picture']?>" />
                </a>
                <p>
                    <a href="<?php echo qw_url(array('module' => 'Style', 'controller' => 'Theme', 'action' => 'Edit', 'id' => $row['id'])) ?>" title="<?php echo qw_lang('LBL_ACTION_EDIT')?>"><?php echo $row['name']?></a>
                   
                </p>
            </li>
<?php
}
?>
        </ul>
        <hr class="ui-line ui-widget-content" />
        <div class="ui-theme-operation ui-operation-field">
            <button class="ui-action-submit" type="submit"><?php echo qw_lang('LBL_ACTION_SUBMIT') ?></button>
            <a class="ui-action-return" href="javascript:history.go(-1);"><?php echo qw_lang('LBL_ACTION_RETURN') ?></a>
        </div>
    </div>
    </form>
</div>