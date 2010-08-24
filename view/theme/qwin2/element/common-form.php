<?php
/**
 * form 的名称
 *
 * form 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-10-31 01:19:12
 * @since     2009-11-24 18:47:32
 */

// 防止直接访问导致错误
!defined('QWIN_PATH') && exit('Forbidden');
//qw('-rsc')->load('jquery/plugin/tip');
//qw('-rsc')->load('jquery/plugin/validate');
// TODO
!isset($validator_rule) && $validator_rule = '""';
?>
<script type="text/javascript">
var validator_rule = <?php echo $validator_rule?>;
</script>
  <div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-titlebar ui-widget-header ui-helper-clearfix"> <span class="ui-box-title"> <a href="<?php echo qw_url($set, array('action' => 'Default')) ?>"><?php echo qw_lang($meta['page']['title']) ?></a></span> <a class="ui-box-title-icon ui-corner-all" name=".ui-form-content" href="javascript:void(0)"> <span class="ui-icon  ui-icon-circle-triangle-n">open/close</span> </a> </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
        <form id="post-form" name="form" method="post" action="<?php echo qw_url()?>">
        <div class="ui-operation-field">
            <a class="ui-action-list" href="<?php echo qw_url($set, array('action' => 'Index')) ?>"><?php echo qw_lang('LBL_ACTION_LIST') ?></a>
            <a class="ui-action-add" href="<?php echo qw_url($set, array('action' => 'Add')) ?>"><?php echo qw_lang('LBL_ACTION_ADD') ?></a>
            <?php if(isset($data[$primaryKey])): ?>
            <a class="ui-action-edit" href="<?php echo qw_url($set, array('action' => 'Edit', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_EDIT') ?></a>
            <a class="ui-action-show" href="<?php echo qw_url($set, array('action' => 'View', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_VIEW') ?></a>
            <a class="ui-action-copy" href="<?php echo qw_url($set, array('action' => 'Add', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_COPY') ?></a>
            <!--<a class="ui-action-restore" href="<?php echo qw_url($set, array('action' => 'Restore', $primaryKey => $data[$primaryKey])) ?>" onclick="alert(Qwin.Lang.MSG_FUNCTION_DEVELOPTING);return false;"><?php echo qw_lang('LBL_ACTION_RESTORE') ?></a>-->
            <a class="ui-action-delete" href="<?php echo qw_url($set, array('action' => 'Delete', $primaryKey => $data[$primaryKey])) ?>" onclick="javascript:return confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE);"><?php echo qw_lang('LBL_ACTION_DELETE') ?></a>
            <?php endif ?>
            <a class="ui-action-return" href="#" onclick="javascript:return history.go(-1);"><?php echo qw_lang('LBL_ACTION_RETURN') ?></a>
            <br />
            <br />
            <button class="ui-action-submit" type="submit"><?php echo qw_lang('LBL_ACTION_SUBMIT') ?></button>
            <button class="ui-action-reset" type="reset"><?php echo qw_lang('LBL_ACTION_RESET') ?></button>
        </div>
        <?php foreach($groupList as $group => $fieldList): ?>
        <fieldset id="ui-fieldset-3" class="ui-widget-content ui-corner-all">
          <legend><?php echo qw_lang($group) ?></legend>
          <table class="ui-table" width="100%">
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
            <input type="hidden" name="_page" value="<?php echo qw_referer_page()?>" />
            <button class="ui-action-submit" type="submit"><?php echo qw_lang('LBL_ACTION_SUBMIT') ?></button>
            <button class="ui-action-reset" type="reset"><?php echo qw_lang('LBL_ACTION_RESET') ?></button>
            <br />
            <br />
            <a class="ui-action-list" href="<?php echo qw_url($set, array('action' => 'Index')) ?>"><?php echo qw_lang('LBL_ACTION_LIST') ?></a>
            <a class="ui-action-add" href="<?php echo qw_url($set, array('action' => 'Add')) ?>"><?php echo qw_lang('LBL_ACTION_ADD') ?></a>
            <?php if(isset($data[$primaryKey])): ?>
            <a class="ui-action-edit" href="<?php echo qw_url($set, array('action' => 'Edit', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_EDIT') ?></a>
            <a class="ui-action-show" href="<?php echo qw_url($set, array('action' => 'View', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_VIEW') ?></a>
            <a class="ui-action-copy" href="<?php echo qw_url($set, array('action' => 'Add', $primaryKey => $data[$primaryKey])) ?>"><?php echo qw_lang('LBL_ACTION_COPY') ?></a>
            <!--<a class="ui-action-restore" href="<?php echo qw_url($set, array('action' => 'Restore', $primaryKey => $data[$primaryKey])) ?>" onclick="alert(Qwin.Lang.MSG_FUNCTION_DEVELOPTING);return false;"><?php echo qw_lang('LBL_ACTION_RESTORE') ?></a>-->
            <a class="ui-action-delete" href="<?php echo qw_url($set, array('action' => 'Delete', $primaryKey => $data[$primaryKey])) ?>" onclick="javascript:return confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE);"><?php echo qw_lang('LBL_ACTION_DELETE') ?></a>
            <?php endif ?>
            <a class="ui-action-return" href="#" onclick="javascript:return history.go(-1);"><?php echo qw_lang('LBL_ACTION_RETURN') ?></a>
        </div>
        </form>
    </div>
  </div>
<script type="text/javascript" src="<?php echo QWIN_RESOURCE_PATH?>/js/other/form.js"></script>
