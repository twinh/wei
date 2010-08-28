<?php
/**
 * 用户设置中心
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-28 19:29:07
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<script type="text/javascript">
    jQuery(function($){
        $('a.ui-action-switch-style').button({icons: {primary: 'ui-icon-calculator'}});
        $('a.ui-action-switch-language').button({icons: {primary: 'ui-icon-script'}});
    })
</script>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <?php echo qw_lang('LBL_LANGUAGE')?>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content ui-image-list">
        <ul>
            <li>
                    <a class="ui-action-view" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'View', 'id' => $member['id'])) ?>"><?php echo qw_lang('LBL_VIEW_DATA')?></a>
                    <a class="ui-action-edit" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'Edit', 'id' => $member['id'])) ?>"><?php echo qw_lang('LBL_EDIT_DATA')?></a>
                    <a class="ui-action-edit" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'EditPassword', 'id' => $member['id'])) ?>"><?php echo qw_lang('LBL_EDIT_PASSWORD')?></a>
                    <a class="ui-action-switch-style" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchStyle')) ?>"><?php echo qw_lang('LBL_SWITCH_STYLE')?></a>
                    <a class="ui-action-switch-language" href="<?php echo qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchLanguage')) ?>"><?php echo qw_lang('LBL_SWITCH_LANGUAGE')?></a>
                    <a class="ui-action-return" href="javascript:history.go(-1);">Return</a>
            </li>
        </ul>
        <hr class="ui-line ui-widget-content" />
    </div>
    </form>
</div>
