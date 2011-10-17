<?php
/**
 * Qwin Framework
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
 */
/**
 * index-content
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-14 15:15:10
 */
?>
<script type="text/javascript">
jQuery(function($){
    /*$('table.qw-form-table tr').click(function(){
        $(this).slideUp();
    })*/
        //
        //$('.qw-elem-text input').qui();
    $('input.qw-form-text, textarea.qw-form-textarea').qui();
    $('legend .qw-form-legend-toggle-icon').qui();
    
    $('#elem1').hover(function(){
        $('#text1').addClass('ui-state-hover');
        $('#button1').addClass('ui-state-hover');
    }, function(){
        $('#text1').removeClass('ui-state-hover');
        $('#button1').removeClass('ui-state-hover');
    });
    $('#elem2').hover(function(){
        $('#text2').addClass('ui-state-hover');
        $('#button2').addClass('ui-state-hover');
        $('#button3').addClass('ui-state-hover');
    }, function(){
        $('#text2').removeClass('ui-state-hover');
        $('#button2').removeClass('ui-state-hover');
        $('#button3').removeClass('ui-state-hover');
    });
});
</script>
<style type="text/css">  
    .qw-corner-all {
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
    }
    .qw-corner-all-2 {
        -moz-border-radius: 22px;
        -webkit-border-radius: 22px;
        border-radius: 22px;
    }
    .qw-clear {
        clear: both;
    }
    .qw-form {
        min-width: 500px;
        width: 1000px;
        padding: 5px;
    }
    .qw-form .qw-form-cell {
        padding: 0 0 5px;
        float: left;
    }
    .qw-form .qw-form-label {
        width: 75px;
        float: left;
        text-align: right;
        padding: 5px 5px 5px 0;
        /*display: block;
        float: none;
        text-align: left;*/
    }
    /* 标签只读标志的图标位置 */
    .qw-form-label-readonly {
        display: inline;
        background-position: -196px -98px;
    }
    .qw-form-label-required {
        color: orange;
        padding: 0 2px;
    }
    .qw-form .qw-form-elem {
        float: left;
        /* ? line-height: 20px;*/
/*        width: 380px;*/
    }
    .qw-form .qw-form-text {
        padding: 1px 3px;
        height: 20px;
        font-weight: normal;
    }
    .qw-form .qw-form-plain {
        padding: 5px 0 0 5px;
        float: left;
    }
    .qw-form .qw-form-select {
        padding: 0;
        line-height: 24px;
        height: 24px;
        padding-top: 3px;
        float: left;
    }
    .qw-form .qw-form-textarea {
        padding: 4px 3px;
        font-weight: normal;
        float: left;
    }
    .qw-form .qw-form-checkbox-group {
        padding: 1px 3px;
        float: left;
    }
    .qw-form .qw-form-checkbox-input {
        vertical-align: -3px;
    }
    .qw-form .qw-form-checkbox-elem {
        margin: 4px 5px 0 2px;
        float: left;
    }
    .qw-form .qw-form-checkbox-label {
        margin: 0 0 0 4px;
    }
    .qw-form .qw-form-fieldset {
        background: none repeat scroll 0 0 transparent;
        margin: 5px;
        padding-left: 5px;
    }
    .qw-form .qw-form-legend {
        background: none repeat scroll 0 0 transparent;
        border: 0 none;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        line-height: 120%;
        padding: 0 3px;
    }
    .qw-form-legend-toggle {
        float: left;
        margin-top: 1px;
    }
    .qw-form-legend-title {
        float: left;
        margin-left: 3px;
        
    }
    .qw-form-legend-toggle-icon {
        width: 14px;
        height: 14px;
        /* ? */
        margin-top: -1px;
        
    }
    .qw-form-legend-toggle-icon span {
        background-position: -65px -17px;
    }
    .qw-form-text {
        float: left;
    }
    .qw-form .qw-form-button-0 {
        height: 22px;
        width: 22px;
        float: left;
        cursor: pointer;
        margin-left: 1px;
    }
    .qw-form .qw-form-button-0 .ui-icon {
        margin: 3px;
    }
    .qw-form .qw-form-button {
        height: 22px;
        width: 22px;
        float: left;
        border-left: 0;
        cursor: pointer;
        -moz-border-radius-topleft: 0;
        -webkit-border-top-left-radius: 0;
        border-top-left-radius: 0;
        -moz-border-radius-bottomleft: 0;
        -webkit-border-bottom-left-radius:0; 
        border-bottom-left-radius: 0;
    }
    .qw-form .qw-form-button-2 {
        -moz-border-radius-topright: 0;
        -webkit-border-top-right-radius: 0;
        border-top-right-radius: 0;
        -moz-border-radius-bottomright: 0;
        -webkit-border-bottom-right-radius:0; 
        border-bottom-right-radius: 0;
    }
    .qw-form .qw-form-button .ui-icon {
        margin: 3px;
    }
    .qw-form .qw-form-text-2 {
        -moz-border-radius-topright: 0;
        -webkit-border-top-right-radius: 0;
        border-top-right-radius: 0;
        -moz-border-radius-bottomright: 0;
        -webkit-border-bottom-right-radius:0; 
        border-bottom-right-radius: 0;
    }
    .qw-form-button2 {
        margin: 1px 0 0 4px;
        text-align: right;
        text-align: left;
    }
    .qw-form-button2 a {
        
    }
</style>
<div id="qw-form-test" class="qw-form ui-widget-content">
    <form action="" method="get">
<?php
function renderItem($items, $in = true)
{
    foreach ($items as $item) {

        if (isset($item['_items'])) {
            echo renderItem($item['_items'], false);
            if ($in) {
            echo '<div class="qw-clear"></div>';
        }
            continue;
        }

        
        echo '<div class="qw-form-cell">
        <label class="qw-form-label" for="first_name">' . $item['_label'] . ':</label>
        <div class="qw-form-elem" id="elem2">
            <input id="text2" class="qw-form-text ui-widget-content qw-corner-all" type="text" value="' . $item['_type'] . '" name="first_name" />
        </div>
        <div class="qw-clear"></div>
    </div>';
        
        //echo $item['_label'] . ':' . '<input value="' . $item['_type'] . '" />';
        if ($in) {
            echo '<div class="qw-clear"></div>';
        }
    }
    
}
$panelAttr = array();
foreach ($form as $key => $row) {
    if ('_' != $key[0]) {
        $panelAttr[] = $key . '="' . (string)$row . '" ';
    }
}

echo '<div ' . implode(' ', $panelAttr) . '>';

echo renderItem($form['_items']);

echo '</div>';
?>
<?php
if ($form['_buttons']) :
?>
<div class="qw-p5">
    <?php
    foreach ($form['_buttons'] as $button) :
    ?>
    <button type="<?php echo $button['type'] ?>" hidefocus="true" class="qw-button" data="{icons:{primary:'<?php echo $button['_icon'] ?>'}}"><?php echo $lang[$button['_label']] ?></button>
    <?php
    endforeach;
    ?>
</div>
<?php
endif;
?>
</form>
</div>