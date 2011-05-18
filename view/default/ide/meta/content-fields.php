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
?>
<script type="text/javascript">
    jQuery(function($){
        var preLi = null;
        var field;
        $('div.qw-meta-fields-left ul li').qui().click(function(){
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
    });
</script>
<style type="text/css">
    .qw-meta-fields {
        width: 200px;
        vertical-align: top;
    }
    .qw-meta-fields-left {
        width: 200px;
        height: 400px;
        overflow: hidden;
        overflow-y: scroll;
    }
    .qw-meta-fields-detail {
        vertical-align: top;
    }
    .qw-meta-fields-table {
        margin-left: 5px;
        margin-top: 3px;
    }
    .qw-meta-fields-left ul li {
        height: 28px;
        line-height: 28px;
        padding-left: 10px;
        margin: 1px;
    }
    .qw-meta-fields-left ul li.ui-state-hover, .qw-meta-fields-left ul li.ui-state-active {
        border: none;
    }
    .qw-meta-fields-right form fieldset {
        display: none;
    }
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
    <table class="qw-meta-fields-table" width="100%">
        <tr>
            <td colspan="2">
                <div class="ui-operation-field qw-meta-oper-field">
                    <a class="ui-state-default" href="#">xxx模块, xx wenjian ...</a>
                </div>
            </td>
        </tr>
        <tr>
            <td class="qw-meta-fields">
                <div class="qw-meta-fields-left ui-widget-content ui-corner-all">
                    <ul>
                    <?php
                    foreach ($meta['fields'] as $field) :
                    ?>
                        <li id="qw-fieldset-li-<?php echo $field['name'] ?>"><a href="#"><?php echo $field['title'] ?>(<?php echo $field['name'] ?>)</a></li>
                    <?php
                    endforeach;
                    ?>
                    </ul>
                </div>
            </td>
            <td class="qw-meta-fields-detail">
                <div class="qw-meta-fields-right ui-widget-content ui-corner-all">
                <?php
                $formWidget = Qwin::call('-widget')->get('Form');
                $formOptions = array(
                    'form'  => $meta[$formName],
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
