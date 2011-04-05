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
 * @package     View
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-28 19:29:07
 */
?>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
	<div class="ui-box-header">
    	<?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
    	<div class="ui-space-10px"></div>
        <div class="ui-operation-field">
        <?php 
        echo Qwin_Util_JQuery::link(qw_u('com/member/my', 'view'), qw_lang('LBL_VIEW_DATA'), 'ui-icon-lightbulb'),
             Qwin_Util_JQuery::link(qw_u('com/member/my', 'edit'), qw_lang('LBL_EDIT_DATA'), 'ui-icon-tag'),
             Qwin_Util_JQuery::link(qw_u('com/member/my', 'editpassword'), qw_lang('LBL_EDIT_PASSWORD'), 'ui-icon-key'),
             Qwin_Util_JQuery::link(qw_url(array('module' => 'Member', 'controller' => 'LoginLog', 'searchField' => 'member_id', 'searchValue' => $member['id'])), qw_lang('LBL_MODULE_MEMBER_LOGINLOG'), 'ui-icon-script'),
             Qwin_Util_JQuery::link(qw_u('com/member/my', 'style'), qw_lang('LBL_SWITCH_STYLE'), 'ui-icon-calculator'),
             Qwin_Util_JQuery::link(qw_u('com/member/my', 'language'), qw_lang('LBL_SWITCH_LANGUAGE'), 'ui-icon-script'),
             Qwin_Util_JQuery::link('javascript:history.go(-1);', qw_lang('ACT_RETURN'), 'ui-icon-arrowthickstop-1-w')
        ?>
        </div>
        <div class="ui-space-10px"></div>
    </div>
    </form>
</div>
