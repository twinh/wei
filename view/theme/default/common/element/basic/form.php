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
$jQueryFile['validate'] = $jquery->loadPlugin('validate', 'qwin', false);
$jsPacker
    ->add($jQueryFile['validate']['js']);
?>
<script type="text/javascript">
var jQueryValidateCode = <?php echo $jQueryValidateCode?>;
</script>
        <form id="post-form" name="form" method="post" action="<?php echo qw_url()?>">
        <div class="ui-operation-field">
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_button('reset', qw_lang('LBL_ACTION_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
        </div>
        <div class="ui-helper-hidden">
            <?php
            // TODO 逻辑分离
            // 将隐藏域单独分开
            if(isset($layout[-1])):
                foreach($layout[-1] as $field):
                    if(null == $field[0]):
                        $tempMeta = $meta;
                        $tempData = $data;
                        $formSet = $tempMeta['field'][$field[1]]['form'];
                        $formSet['_value'] = $tempData[$formSet['name']];
                    else:
                        $tempMeta = $meta['metadata'][$field[0]];
                        $tempData = $data[$field[0]];
                        $formSet = $tempMeta['field'][$field[1]]['form'];

                        $formSet['_value'] = $tempData[$formSet['name']];
                        $formSet['id'] = $field[0] . '_' . $formSet['name'];
                        $formSet['name'] = $field[0] . '[' . $formSet['name'] . ']';
                    endif;
                    echo qw_form($formSet, $tempData);
                endforeach;
                unset($layout[-1]);
            endif;
            ?>
        </div>
        <?php foreach($layout as $groupKey => $fieldGroup): ?>
        <fieldset id="ui-fieldset-<?php echo $groupKey ?>" class="ui-widget-content ui-corner-all">
            <legend><?php echo qw_lang($group[$groupKey]) ?></legend>
            <table class="ui-form-table" id="ui-form-table-<?php echo $groupKey ?>" width="100%">
                <tr>
                  <td width="12.5%"></td>
                  <td width="37.5%"></td>
                  <td width="12.5%"></td>
                  <td width="37.5%"></td>
                </tr>
                <?php
                foreach($fieldGroup as $fieldRow):
                ?>
                <tr>
                    <?php
                    if(1 == count($fieldRow)):
                        $colspan = ' colspan="3"';
                    else:
                        $colspan = '';
                    endif;
                    foreach($fieldRow as $fieldCell):
                        if('' == $fieldCell):
                    ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php
                        else:
                            if(null == $fieldCell[0]):
                                $tempMeta = $meta;
                                $tempData = $data;
                                $formSet = $tempMeta['field'][$fieldCell[1]]['form'];
                                $formSet['_value'] = isset($tempData[$formSet['name']]) ? $tempData[$formSet['name']] : null;
                            else:
                                $tempMeta = $meta['metadata'][$fieldCell[0]];
                                $tempData = $data[$fieldCell[0]];
                                $formSet = $tempMeta['field'][$fieldCell[1]]['form'];

                                $formSet['_value'] = isset($tempData[$formSet['name']]) ? $tempData[$formSet['name']] : null;
                                $formSet['id'] = $fieldCell[0] . '_' . $formSet['name'];
                                $formSet['name'] = $fieldCell[0] . '[' . $formSet['name'] . ']';
                            endif;
                            $formSet['class'] .= ' ui-widget-content ui-corner-all';
                            $type = $tempMeta['field'][$fieldCell[1]]['form']['_type'];
                    ?>
                    <td class="ui-label-common"><label for="<?php echo $tempMeta['field'][$fieldCell[1]]['form']['id'] ?>"><?php echo qw_lang($tempMeta['field'][$fieldCell[1]]['basic']['title']) ?>:</label></td>
                    <td class="ui-field-common ui-field-<?php echo $type ?>"<?php echo $colspan ?>>
                      <?php echo qw_form($formSet, $tempData) , qw_widget($formSet, $tempData) ?>
                    </td>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tr>
                <?php
                endforeach;
                ?>
            </table>
        </fieldset>
        <?php endforeach ?>
        <div class="ui-operation-field">
            <input type="hidden" name="_page" value="<?php echo qw_referer_page() ?>" />
            <?php echo qw_jquery_button('submit', qw_lang('LBL_ACTION_SUBMIT'), 'ui-icon-check') ?>
            <?php echo qw_jquery_button('reset', qw_lang('LBL_ACTION_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
        </div>
        </form>
<?php
$jsPacker->add(QWIN_RESOURCE_PATH . '/js/qwin/form.js');
?>