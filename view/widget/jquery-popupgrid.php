<?php
/**
 * jquery-popupgrid
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
 * @since       2011-01-13 00:36:36
 */

$jQueryFile = array(
    'position' => $jquery->loadUi('position', false),
    'dialog' => $jquery->loadUi('dialog', false),
    'qwinPopup' => $jquery->loadPlugin('qwin-popup', null, false),
);
$cssPacker
    ->add($jQueryFile['position']['css'])
    ->add($jQueryFile['dialog']['css'])
    ->add($jQueryFile['qwinPopup']['css']);
$jsPacker
    ->add($jQueryFile['position']['js'])
    ->add($jQueryFile['dialog']['js'])
    ->add($jQueryFile['qwinPopup']['js']);

echo qw_form($meta);
?>
<button id="ui-button-qwin-popup-<?php echo $id ?>" type="button"><span class="ui-icon ui-icon-calculator"><?php echo $meta['name'] ?></span></button>
<button id="ui-button-qwin-popup-<?php echo $id ?>-clear" type="button"><span class="ui-icon ui-icon-arrowreturnthick-1-w"><?php echo $meta['name'] ?></span></button>
<script type="text/javascript">
jQuery(function($){
    $('#<?php echo $id ?>').hide();
    $('#ui-button-qwin-popup-<?php echo $id ?>').qwinPopup({
        title       : '<?php  echo $title ?>',
        url         : "<?php  echo qw_url($url) ?>",
        viewInput   : '#<?php echo $meta['id'] ?>',
        valueInput  : '#<?php echo $id ?>',
        viewColumn  : '<?php  echo $viewField[0] ?>',
        valueColumn : '<?php  echo $viewField[1] ?>'
    });
    $('#ui-button-qwin-popup-<?php echo $id ?>-clear').click(function(){
        $('#<?php echo $id ?>').val('');
        $('#<?php echo $meta['id'] ?>').val('(<?php echo qw_lang('LBL_NOT_SELECTED')?>,<?php echo qw_lang('LBL_READONLY') ?>)');
    });
})
</script>