<?php
/**
 * mangementpackagelist
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-16 11:38:24
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
?>
<div class="ui-widget">
<table class="ui-table ui-widget-content ui-corner-all" cellpadding="0" cellspacing="0">
            <tr class="ui-widget-header ui-table-header">
                <td colspan="3" class="ui-corner-top">
                    <?php echo qw_lang('LBL_NAMESPACE') ?>
                </td>
            </tr>
            <tr class="ui-widget-content ui-table-toolbar">
                <td class="ui-state-default" colspan="4">
                    <!--&nbsp;<a class="action-add" href="<?php echo qw_url($asc, array('action' => 'Add')) ?>"><?php echo qw_lang('ACT_ADD')?></a>-->
                </td>
            </tr>
            <tr class="ui-widget-content">
                <td class="ui-state-default" width="4%">&nbsp;</td>
                <td class="ui-state-default"><?php echo qw_lang('LBL_FIELD_NAMESPACE') ?></td>
                <td class="ui-state-default"><?php echo qw_lang('LBL_FIELD_OPERATION') ?></td>
            </tr>
            <?php
            foreach($data as $row):
            ?>
            <tr class="ui-widget-content">
                <td class="ui-state-default"><?php echo $row['id'] ?></td>
                <td><?php echo $row['package'] ?></td>
                <td><?php echo $row['operation'] ?></td>
            </tr>
            <?php
            endforeach;
            ?>
        </table>
</div>
