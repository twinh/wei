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
        $('div.qw-meta-fields-left ul li').not('.ui-state-default').qui();
    });
</script>
<style type="text/css">
    .qw-meta-fields {
        width: 200px;
        vertical-align: top;
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
        margin: 2px;
    }
    
    .qw-meta-fields-left ul li.ui-state-hover {
        border: none;
    }
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <div class="ui-form-content ui-box-content ui-widget-content">
    <table class="qw-meta-fields-table" width="100%">
        <tr>
            <td class="qw-meta-fields">
                <div class="qw-meta-fields-left ui-widget-content ui-corner-all">
                    <ul>
                        <li class="ui-state-default ui-corner-top"><a href="#">域列表</a></li>
                        <li><a href="#">编号(id)</a></li>
                        <li><a href="#">分组(group_id)</a></li>
                        <li><a href="#">用户名(username)</a></li>
                        <li><a href="#">密码(password)</a></li>
                        <li><a href="#">邮箱(email)</a></li>
                        <li><a href="#">名(first_name)</a></li>
                        <li><a href="#">姓(last_name)</a></li>
                    </ul>
                </div>
            </td>
            <td class="qw-meta-fields-detail">
                <div class="qw-meta-fields-right ui-widget-content ui-corner-all">
                <?php
                $formWidget = Qwin::call('-widget')->get('Form');
                $formOptions = array(
                    'form'  => Com_Meta::getByModule('ide/meta')->getForm(),
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
