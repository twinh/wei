<?php
/**
 * updatelog
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
 * @since       2011-01-09 09:45:11
 */
?>
<style type="text/css">
.ui-updatelog{
    margin: 10px;
}
.ui-updatelog li{
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    font-size: 14px;
    margin-top: 10px;
}
.ui-updatelog ul li{
    font-weight: normal;
    height: 24px;
    line-height: 24px;
    font-size: 12px;
    margin: 0 0 0 30px;
    list-style: decimal;
}
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php $this->loadWidget('Common_Widget_Header') ?>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field"> 
        </div>
        <ul class="ui-updatelog">
            <li>v1.0.0 2011.01.05</li>
            <ul>
                <li>[应用]完成基本的程序架构设计和模块</li>
            </ul>
            <li>v1.0.1 2011.01.09</li>
            <ul>
                <li>[应用]增加更新记录</li>
                <li>[应用]调整多个模块列表和页眉显示问题</li>
                <li>[应用]更新配置模块,表单域和分组允许启用和禁用</li>
                <li>[框架]调整应用目录结构配置,进行标准化控制</li>
            </ul>
            <li class="ui-updatelog-preview">v1.0.2 预计更新内容</li>
            <ul>
                <li>[应用]回收站</li>
                <li>[应用]模块互联</li>
            </ul>
        </ul>
    </div>
    </form>
</div>