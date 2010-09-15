<?php
/**
 * mangementcreatenamespace
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
 * @subpackage  View
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-13 13:24:18
 */
// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
$groupList = $this->groupList;
$relatedField = $this->relatedField;
$data = array();
$jQueryValidateCode = $this->jQueryValidateCode;
?>
<script type="text/javascript">
jQuery(function($){
    $('table.ui-table tr').not('.ui-table-header').qui();
    $('table.ui-table td.ui-state-default').qui();

})
</script>
<style type="text/css">

</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <span class="ui-box-title">
            <?php echo qw_lang('LBL_MODULE_APPLICATION_STRUCTURE')?>
        </span>
        <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)">
            <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span>
        </a>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <table class="ui-table ui-widget-content ui-corner-all" cellpadding="0" cellspacing="0">
            <tr class="ui-widget-header ui-table-header">
                <td colspan="3" class="ui-corner-top">
                    <?php echo qw_lang('LBL_FORM_VALUE_ADVICE') ?>
                </td>
            </tr>
            <tr class="ui-widget-content">
                <td class="ui-state-default"><?php echo qw_lang('LBL_FIELD') ?></td>
                <td class="ui-state-default"><?php echo qw_lang('LBL_TYPE') ?></td>
                <td class="ui-state-default"><?php echo qw_lang('LBL_VALUE') ?></td>
            </tr>
            <tr class="ui-widget-content">
                <td width="25%"><?php echo qw_lang('LBL_FIELD_NAMESPACE') ?></td>
                <td class=""><?php echo qw_lang('LBL_TYPE_NOT_IN') ?></td>
                <td class=""><?php echo $this->banNamespace ?></td>
            </tr>
        </table>
        <?php require QWIN_RESOURCE_PATH . '/view/theme/' . $this->_theme . '/element/basic-form.php' ?>
    </div>
</div>
