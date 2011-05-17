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
<style type="text/css">
    .qw-meta-fields {
        width: 200px;
        vertical-align: top;
    }
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <table>
        <tr>
            <td class="qw-meta-fields">
                <div>
                    <ul>
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
            <td>
                <?php
                $formWidget = Qwin::call('-widget')->get('Form');
                $formOptions = array(
                    'form'  => Com_Meta::getByModule('com/member')->getForm(),
                    'action' => 'edit',
                    'data'  => $data,
                );
                $formWidget->render($formOptions);
                ?>
            </td>
        </tr>
    </table>
</div>
