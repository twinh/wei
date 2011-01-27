<?php
/**
 * editor-option
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-20 15:08:07
 */

foreach ($codeMeta['field'] as $field => $value) {
    $codeMeta['field']->addClass($field, 'ui-widget-content ui-corner-all');
}
?>
<div class="ui-optioneditor ui-widget-content">
    <table class="ui-state-default ui-optioneditor-title" width="100%">
        <tr>
            <td class="ui-optioneditor-value-title"><?php echo $lang['LBL_OPTION_EDITOR_VALUE'] ?></td>
            <td class="ui-optioneditor-common-title"><?php echo $lang['LBL_OPTION_EDITOR_NAME'] ?></td>
            <td class="ui-optioneditor-color-title"><?php echo $lang['LBL_OPTION_EDITOR_COLOR'] ?></td>
            <td class="ui-optioneditor-color-title"><?php echo $lang['LBL_OPTION_EDITOR_STYLE'] ?></td>
            <td class="ui-optioneditor-oper-title"><?php echo $lang['LBL_OPTION_EDITOR_OPER'] ?></td>
        </tr>
    </table>
    <ul id="ui-optioneditor-sortable">
    <?php
    $key = 0;
    foreach($view['data']['code'] as $row) :
    ?>
        <li class="ui-widget-content">
            <table>
                <tr>
                    <td class="ui-optioneditor-value">
                        <?php echo qw_form($codeMeta->getDynamicFieldForm('value', $key), $row['value']) ?>
                    </td>
                    <td class="ui-optioneditor-common">
                        <?php echo qw_form($codeMeta->getDynamicFieldForm('name', $key), $row['name']) ?>
                    </td>
                    <td class="ui-optioneditor-color">
                        <?php echo qw_form($codeMeta->getDynamicFieldForm('color', $key), $row['color']) ?>
                    </td>
                    <td class="ui-optioneditor-color">
                        <?php echo qw_form($codeMeta->getDynamicFieldForm('style', $key), $row['style']) ?>
                    </td>
                    <td class="ui-optioneditor-oper">
                        <a class="ui-state-default"><span class="ui-icon ui-icon-circle-close">X</span></a>
                    </td>
                </tr>
            </table>
        </li>
    <?php
        $key++;
    endforeach;
    ?>
    </ul>
    <table class="ui-optioneditor-footer ui-widget-content" width="100%">
        <tr>
            <td>
                <input id="ui-optioneditor-number" type="text" class="ui-widget-content ui-corner-all" value="1">
                <?php echo qw_jQuery_link('javascript:;', $lang['LBL_OPTION_EDITOR_ADD'], 'ui-icon-plus', 'ui-optioneditor-button')?>
                <!--<?php echo qw_jQuery_link('javascript:;', '重置顺序', 'ui-icon-plus', 'ui-optioneditor-reset')?>-->
                <!--<?php echo qw_jQuery_link('javascript:;', '按值排序', 'ui-icon-plus', 'ui-optioneditor-order')?>-->
            </td>
        </tr>
    </table>
</div>
