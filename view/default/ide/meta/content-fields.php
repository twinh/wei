<?php

/**
 * content-fields
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
 * @since       2011-05-17 11:28:30
 */
$widget->get('Minify')->addArray(array(
    $jQuery->loadUi('position', false),
    $jQuery->loadUi('dialog', false),
));
?>
<script type="text/javascript">
    jQuery(function($){
        var preLi = null;
        var field;
        $('div.qw-fields-left ul li').qui().click(function(){
            // 隐藏上一个表单组,显示点击的表单组
            field && $('#ui-fieldset-' + field).hide();
            field = $(this).attr('id').substr(15);
            $('#ui-fieldset-' + field).show();
            
            // 移除上一个域的激活效果,激活当前域
            preLi && preLi.removeClass('ui-state-active');
            $(this).addClass('ui-state-active');
            preLi = $(this);
        // 激活第一项
        }).first().click();
        
        // 使左栏跟右栏高度一致
        $('div.qw-fields-left').height($('div.qw-fields-right').height());
        
        // 标题内容更改时,更改对应左栏标题
        $('input.qw-fields-title').change(function(){
            var id = $(this).attr('name');
            var name = id.substr(0, id.indexOf('['));
            $('#ui-fieldset-' + name).find('legend').html($(this).val());
            $('#qw-fieldset-li-' + name + ' a').html($(this).val() + '(' + name + ')');
        });
        
        $('div.qw-fields-oper').buttonset();
        $('div.qw-fields-oper a.qw-fields-oper-add').click(function(){
            
        })
        function addField(name)
        {
            var uname = name.toUpperCase();
            preLi.after('<li id="qw-fieldset-li-' + name + '" class="ui-corner-left"><a href="javascript:;">FLD_' + uname + '(' + name + ')</a></li>');
            $('#qw-fieldset-li-' + name).click();
        }
        function delField($name)
        {
            
        }
    });
</script>
<style type="text/css">
    .qw-fields {
        width: 200px;
        vertical-align: top;
    }
    .qw-fields-left {
        width: 200px;
        height: 400px;
        overflow: hidden;
        overflow-y: scroll;
    }
    .qw-fields-detail {
        vertical-align: top;
    }
    .qw-fields-table {
        margin-left: 5px;
        margin-top: 3px;
    }
    .qw-fields-left ul li {
        height: 28px;
        line-height: 28px;
        padding-left: 10px;
        margin: 1px;
    }
    .qw-fields-left ul li.ui-state-hover, .qw-fields-left ul li.ui-state-active {
        border: none;
    }
    .qw-fields-right form fieldset {
/*        display: none;*/
    }
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
    <table class="qw-fields-table" width="100%">
        <tr>
            <td colspan="2">
<!--                <div class="ui-operation-field qw-meta-oper-field">
                    <a class="ui-state-default" href="#">xxx模块, xx wenjian ...</a>
                </div>-->
                <div class="qw-fields-oper">
                        <a class="qw-fields-oper-add" href="javascript:;"><?php echo $lang['ACT_ADD'] ?></a>
                        <a class="qw-fields-oper-delete" href="javascript:;"><?php echo $lang['ACT_DELETE'] ?></a>
                </div>
            </td>
        </tr>
        <tr>
            <td class="qw-fields">
                <div class="qw-fields-left ui-widget-content ui-corner-left">
                    <ul>
                    <?php
                    foreach ($fields as $name => $field) :
                    ?>
                        <li class="ui-corner-left" id="qw-fieldset-li-<?php echo $name ?>"><a href="javascript:;"><?php echo $lang->f($name) ?>(<?php echo $name ?>)</a></li>
                    <?php
                    endforeach;
                    ?>
                    </ul>
                </div>
            </td>
            <td class="qw-fields-detail">
                <div class="qw-fields-right ui-widget-content ui-corner-right">
                <?php
                $formWidget = Qwin::call('-widget')->get('Form');
                $formOptions = array(
                    'meta' => $meta,
                    'form'  => $formName,
                    'action' => 'edit',
                    'data'  => $data,
                );
                $formWidget->render($formOptions);
                ?>
                </div>
            </td>
        </tr>
    </table>
    </div>
</div>
