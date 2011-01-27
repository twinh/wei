<?php
/**
 * 显示一条记录的视图
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
 * @since       2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
$jQueryFile['tabs'] = $jQuery->loadUi('tabs', false);
$cssPacker->add($jQueryFile['tabs']['css']);
$jsPacker
	->add($jQueryFile['tabs']['js'])
	->add(QWIN_RESOURCE_PATH . '/view/theme/default/common/script/form.js');
$operationField =  $this->loadWidget('Common_Widget_FormLink', array($data, $primaryKey));
?>
<script>
	jQuery(function($) {
		$( "#ui-box-tab" ).tabs();
	});
	</script>

  <div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <form id="post-form" name="form" method="post" action="">
        <div class="ui-operation-field">
            <?php echo $operationField ?>
        </div>
        <div class="ui-helper-hidden">
            <?php
            // TODO 逻辑分离
            // 将隐藏域单独分开
            if(isset($layout[-1])):
                unset($layout[-1]);
            endif;
            ?>
        </div>
        <div id="ui-box-tab" class="ui-box-tab">
        <ul>
            <li><a href="#ui-tab-1"><?php echo $lang[$meta['page']['title']] . $lang['LBL_TAB_DATA'] ?></a></li>
        <?php
        foreach($tabTitle as $alias => $title):
        ?>
            <li><a href="#ui-tab-<?php echo $alias?>"><?php echo $title ?></a></li>
        <?php
        endforeach;
        ?>
        </ul>
    <div id="ui-tab-1">
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
                      <?php echo qw_null_text($tempData[$fieldCell[1]]) ?>
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
        <div id="ui-buttom-operation-field" class="ui-operation-field">
            <?php echo $operationField ?>
        </div>
	</div>
    <?php
    foreach($jqGridList as $alias => $jqGrid):
        $jqGridJson = $jqGridListJson[$alias];
    ?>
    <div id="ui-tab-<?php echo $alias ?>">
        <?php require $this->decodePath('<resource><theme>/<defaultNamespace>/element/basic/jqgird<suffix>') ?>
    </div>
    <?php
    endforeach;
    ?>
</div>
        </form>
    </div>
  </div>
