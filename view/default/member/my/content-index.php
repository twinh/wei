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
<div class="qw-content ui-widget-content">
    <div class="qw-content-content">
        <div class="ui-space-10px"></div>
        <div class="qw-p5">
            <a class="qw-anchor" href="<?php echo qw_u('member/my', 'view') ?>" data="{icons:{primary:'ui-icon-lightbulb'}}"><?php echo $lang['LBL_VIEW_DATA'] ?></a>
            <a class="qw-anchor" href="<?php echo qw_u('member/my', 'edit') ?>" data="{icons:{primary:'ui-icon-tag'}}"><?php echo $lang['LBL_EDIT_DATA'] ?></a>
            <a class="qw-anchor" href="<?php echo qw_u('member/my', 'editpassword') ?>" data="{icons:{primary:'ui-icon-key'}}"><?php echo $lang['LBL_EDIT_PASSWORD'] ?></a>
            <a class="qw-anchor" href="<?php echo qw_u('member/log', 'index', array('search' => 'member_id:' . $member['id'])) ?>" data="{icons:{primary:'ui-icon-script'}}"><?php echo $lang['LBL_MODULE_MEMBER_LOGINLOG'] ?></a>
            <a class="qw-anchor" href="<?php echo qw_u('member/my', 'style') ?>" data="{icons:{primary:'ui-icon-calculator'}}"><?php echo $lang['LBL_SWITCH_STYLE'] ?></a>
            <a class="qw-anchor" href="<?php echo qw_u('member/my', 'language') ?>" data="{icons:{primary:'ui-icon-script'}}"><?php echo $lang['LBL_SWITCH_LANGUAGE'] ?></a>
            <a class="qw-anchor" href="javascript:history.go(-1);" data="{icons:{primary:'ui-icon-arrowthickstop-1-w'}}"><?php echo $lang['ACT_RETURN'] ?></a>
        </div>
        <div class="ui-space-10px"></div>
    </div>
</div>