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
    font-size: 16px;
    margin-top: 10px;
}
.ui-updatelog ul li{
    font-weight: normal;
    height: 24px;
    line-height: 24px;
    font-size: 14px;
    margin: 0 0 0 30px;
}
</style>
<div class="ui-form ui-box ui-widget ui-widget-content ui-corner-all" id="ui-form">
    <div class="ui-box-header">
        <?php Qwin::hook('ViewContentHeader', $this) ?>
    </div>
    <form action="" method="post">
    <div class="ui-form-content ui-box-content ui-widget-content">
        <div class="ui-operation-field"> 
        </div>
        <ul class="ui-updatelog">
            <li class="ui-updatelog-preview">
                Qwin能为您做什么?
                <ul>
                    <li>Qwin为您提供了一套网站后台解决方案,提供实际有效的基础应用,为您后台开发节省大量的时间.</li>
                </ul>
            </li>
            <li>是什么,与框架的区别
                <ul>
                    <li>她与框架的区别在于,传统的PHP框架多是以其他语言,成熟框架为模板,为PHP引入太多概念,忽略了PHP是快速,高效的代名词;</li>
                    <li>Qwin是一个平台,由底层框架和基础应用组成,定位于实际应用和框架之间.</li>
                    <li>她只专注于后台这一领域,充分利用PHP的特性,如强大的数组,资源自由使用等,提供完善的可视化操作界面等等.</li>
                </ul>
            </li>
            <li>核心
                <ul>
                    <li>Qwin:资源注册器,类智能加载,全局配置;</li>
                    <li>Meta:元数据,应用的详细配置,是自动化智能化的基础;</li>
                    <li>Widget:可重复使用,自由组合的功能,也常被称为模块,组件,插件等等</li>
                </ul>
            </li>
            <li>发展历程
                <ul>
                    <li>Qwin起源于2008年底,起初只是一个用于偷懒的小程序,功能是根据数据库自动生成后台简单的增删查改操作.</li>
                    <li>后来经过多次重构,功能增强,不断发展,至今更新超过两年,初具体系,并已提出一系列独有的理念.</li>
                </ul>
            </li>
            <li>发展方向
                <ul>
                    <li>Qwin将趋向可视化,自动化,智能化发展.</li>
                </ul>
            </li>
        </ul>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </div>
    </form>
</div>
