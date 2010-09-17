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
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <?php echo qw_lang('LBL_MEMBER_CENTER')?>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content ui-image-list">
        <ul>
            <li>
                <?php 
                echo qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'View', 'id' => $member['id'])), qw_lang('LBL_VIEW_DATA'), 'ui-icon-lightbulb'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'Edit', 'id' => $member['id'])), qw_lang('LBL_EDIT_DATA'), 'ui-icon-tag'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Member', 'action' => 'EditPassword', 'id' => $member['id'])), qw_lang('LBL_EDIT_PASSWORD'), 'ui-icon-key'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'LoginLog', 'searchField' => 'member_id', 'searchValue' => $member['id'])), qw_lang('LBL_MODULE_MEMBER_LOGINLOG'), 'ui-icon-script'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchStyle')), qw_lang('LBL_SWITCH_STYLE'), 'ui-icon-calculator'),
                     qw_jquery_link(qw_url(array('module' => 'Member', 'controller' => 'Setting', 'action' => 'SwitchLanguage')), qw_lang('LBL_SWITCH_LANGUAGE'), 'ui-icon-script'),
                     qw_jquery_link('javascript:history.go(-1);', qw_lang('LBL_ACTION_RETURN'), 'ui-icon-arrowthickstop-1-w')
                ?>
            </li>
        </ul>
        <hr class="ui-line ui-widget-content" />
    </div>
    </form>
</div>
