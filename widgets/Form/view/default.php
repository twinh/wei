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
$this->_minify->addArray(array(
    $this->_path . 'view/default.css',
    $this->_path . 'script/js.js'
));
if ($options['validate']) :
    $this->_minify->addArray(array(
        $this->_path . 'script/style.css',
        $this->_path . 'source/jquery.validate.qwin.js'
    ));
?>
<script type="text/javascript">
validateCode['<?php echo $options['id'] ?>'] = <?php echo $validateCode ?>;
</script>
<?php
endif;
?>
<form id="<?php echo $options['id'] ?>" class="qw-form" action="<?php echo qw_url()?>" method="post">
<div class="qw-p5">
    <?php echo Qwin_Util_JQuery::button('submit', $lang['ACT_SUBMIT'], 'ui-icon-check') ?>
    <?php echo Qwin_Util_JQuery::button('reset', $lang['ACT_RESET'], 'ui-icon-arrowreturnthick-1-w') ?>
</div>
<div class="ui-helper-hidden">
    <?php
    foreach($form['hidden'] as $value) {
        echo $this->renderElement($form['fields'][$value]);
    }
    ?>
</div>
<?php
    foreach ($form['layout'] as $name => $fieldset) :
?>
    <fieldset class="ui-widget-content ui-corner-all">
    <legend><?php echo $lang[$name] ?></legend>
    <table class="qw-form-table">
        <tr>
        <?php foreach ($percent as $value) : ?>
            <td width="<?php echo $value?>%"></td>
        <?php endforeach; ?>
        </tr>
        <?php foreach ($fieldset as $fields) : ?>
        <tr>
        <?php
        $i = 0;
        foreach ($fields as $key => $field) :
            if (0 < $i) :
                $i--;
                continue;
            endif;
            if (is_null($field)) :
        ?>
            <td colspan="2">&nbsp;</td>
        <?php
            else :
                $i = $key;
                while(array_key_exists($i + 1, $fields) && is_null($fields[$i + 1])) :
                    $i++;
                endwhile;
                $i = $i - $key;
                if ('' === $field) :
        ?>
            <td colspan="<?php echo $i + 2 ?>">&nbsp;</td>
        <?php
                else :
        ?>
            <td class="qw-label-common qw-label-<?php echo $form['fields'][$field]['_type'] ?>"><label for="<?php echo $form['fields'][$field]['id'] ?>"><?php echo $lang->f($field) ?>:</label></td>
            <td class="qw-field-common qw-field-<?php echo $form['fields'][$field]['_type'] ?>" colspan="<?php echo $i * 2 + 1 ?>"><?php echo $this->renderElement($form['fields'][$field]) , $this->renderWidget($form['fields'][$field]) ?></td>
        <?php
                endif;
            endif;
        endforeach;
        ?>
        </tr>
        <?php endforeach; ?>
    </table>
    </fieldset>
<?php endforeach; ?>
<div class="qw-p5">
    <input type="hidden" name="_page" value="<?php echo $refererPage ?>" />
    <?php echo Qwin_Util_JQuery::button('submit', $lang['ACT_SUBMIT'], 'ui-icon-check') ?>
    <?php echo Qwin_Util_JQuery::button('reset', $lang['ACT_RESET'], 'ui-icon-arrowreturnthick-1-w') ?>
</div>
</form>
