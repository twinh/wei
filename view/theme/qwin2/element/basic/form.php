<?php
/**
 * basicform
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
 * @since       2010-09-13 14:44:01
 */
!isset($jQueryValidateCode) && $jQueryValidateCode = '{"rules":[],"messages":[]};';
!isset($data) && $data = array();
echo $jquery->loadPlugin('validate', 'qwin');
?>
<script type="text/javascript">
var jQueryValidateCode = <?php echo $jQueryValidateCode?>;
</script>
        <form id="post-form" name="form" method="post" action="<?php echo qw_url()?>">
        <div class="ui-operation-field">
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_button('reset', qw_lang('LBL_ACTION_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
        </div>
        <?php foreach($groupList as $group => $fieldList): ?>
        <fieldset id="ui-fieldset-<?php echo $group ?>" class="ui-widget-content ui-corner-all">
          <legend><?php echo qw_lang($group) ?></legend>
          <table class="ui-form-table" id="ui-form-table-<?php echo $group ?>" width="100%">
            <tr>
              <td width="12.5%"></td>
              <td width="37.5%"></td>
              <td width="12.5%"></td>
              <td width="37.5%"></td>
            </tr>
            <?php
            foreach($fieldList as $fieldName => $field):
                $relatedField->addClass($fieldName, 'ui-widget-content ui-corner-all');
                $type = $relatedField[$fieldName]['form']['_type'];
            ?>
            <tr class="ui-block-<?php echo $type ?>">
              <td class="ui-label-common"><label for="<?php echo $relatedField[$fieldName]['form']['id'] ?>"><?php echo qw_lang($field) ?>:</label></td>
              <td class="ui-field-common ui-field-<?php echo $type ?>" colspan="3">
                <?php echo qw_form($relatedField[$fieldName]['form'], $data) , qw_widget($relatedField[$fieldName]['form'], $data) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </table>
        </fieldset>
        <?php endforeach; ?>
        <div class="ui-operation-field">
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_button('reset', qw_lang('LBL_ACTION_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
        </div>
        </form>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH?>/js/qwin/form.js"></script>