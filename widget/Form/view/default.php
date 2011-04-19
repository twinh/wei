<?php
/**
 * default
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @since       2011-02-16 14:14:21
 */
$minify->add($this->_rootPath . 'script/js.js');
if ($options['validate']) {
    $minify->add($this->_rootPath . 'script/style.css')
           ->add($this->_rootPath . 'source/jquery.validate.qwin.js');
?>
<script type="text/javascript">
validateCode['<?php echo $options['id'] ?>'] = <?php echo $validateCode ?>;
</script>
<?php
}
?>
<form id="<?php echo $options['id'] ?>" name="form" method="post" action="<?php echo qw_url()?>">
<div class="ui-operation-field">
    <?php echo Qwin_Util_JQuery::button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
    <?php echo Qwin_Util_JQuery::button('reset', qw_lang('ACT_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
</div>
<div class="ui-helper-hidden">
    <?php
    foreach($form['hidden'] as $value) {
        echo $this->renderElement($value);
    }
    ?>
</div>
<?php foreach($form['element'] as $groupKey => $fieldGroup): ?>
<fieldset id="ui-fieldset-<?php echo $groupKey ?>" class="ui-widget-content ui-corner-all">
    <legend><?php echo qw_lang($group[$groupKey]) ?></legend>
    <table class="ui-form-table" id="ui-form-table-<?php echo $groupKey ?>" width="100%">
        <colgroup width="12.5%"></colgroup>
        <colgroup width="37.5%"></colgroup>
        <colgroup width="12.5%"></colgroup>
        <colgroup width="37.5%"></colgroup>
        <?php
        foreach($fieldGroup as $row):
        ?>
        <tr>
            <?php
            if(1 == count($row)):
                $colspan = ' colspan="3"';
            else:
                $colspan = '';
            endif;
            foreach($row as $cell):
                if('' == $cell):
            ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?php
                else:
            ?>
            <td class="ui-label-common"><label for="<?php echo $cell[1]['id'] ?>"><?php echo $lang[$cell[0]] ?>:</label></td>
            <td class="ui-field-common ui-field-<?php echo $cell[1]['_type'] ?>"<?php echo $colspan ?>>
              <?php echo $this->renderElement($cell[1]) , $this->renderElementWidget($cell[1]) ?>
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
    <input type="hidden" name="_page" value="<?php echo $refererPage ?>" />
    <?php echo Qwin_Util_JQuery::button('submit', qw_lang('ACT_SUBMIT'), 'ui-icon-check') ?>
    <?php echo Qwin_Util_JQuery::button('reset', qw_lang('ACT_RESET'), 'ui-icon-arrowreturnthick-1-w') ?>
</div>
</form>
