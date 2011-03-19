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
            <li class="ui-updatelog-preview">
                v1.0.4 预计更新内容
                <ul>
                    <li>加强通用分类</li>
                    <li>个人中心</li>
                </ul>
            </li>
            <li>v1.0.3 2011.01.14
                <ul>
                    <li>[应用]规范化应用结构配置</li>
                    <li>[应用]规范化请求对象</li>
                    <li>[应用]完善各模块细节</li>
                    <li>[框架]增加命名风格模块</li>
                    <li>[资源]完善,优化风格</li>
                    <li>[整体]删除无用资源,,,模块,类库</li>
                    <li>[资源]修复弹出窗口视图</li>
                    <li>[资源]修复列表左栏宽度变小的问题</li>
                    <li>[资源]修复权限读取不正确的问题</li>
                    <li>[应用]修复提交表单时将空字符串转为0的问题</li>
                </ul>
            </li>
            <li>v1.0.2 2011.01.10
                <ul>
                    <li>[应用]增加回收站模块</li>
                </ul>
            </li>
            <li>v1.0.1 2011.01.09
                <ul>
                    <li>[应用]增加更新记录</li>
                    <li>[应用]调整多个模块列表和页眉显示问题</li>
                    <li>[应用]更新配置模块,表单域和分组允许启用和禁用</li>
                    <li>[框架]调整应用目录结构配置,进行标准化控制</li>
                </ul>
            </li>
            <li>v1.0.0 2011.01.05
                <ul>
                    <li>[应用]完成基本的程序架构设计和模块</li>
                </ul>
            </li>
        </ul>
    </div>
    </form>
</div>