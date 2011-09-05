<?php
/**
 * content-index
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
 * @since       2011-8-23 0:19:44
 */
$minify->add(array(
    $jQuery->loadUi('tabs'),
    $jQuery->loadUi('accordion'),
));
?>
<script type="text/javascript">
    jQuery(function($){
        $("#qw-docs-tabs").tabs({
            
        });
        
        $('#qw-docs-tabs .qw-docs-option-name a, \n\
           #qw-docs-tabs .qw-docs-event-name a, \n\
           #qw-docs-tabs .qw-docs-result-code a, \n\
           #qw-docs-tabs .qw-docs-property-name a,\n\
           #qw-docs-tabs .qw-docs-method-name a,')
        .qui()
        .click(function(){
            $(this).parent().parent().next().toggle();
        });
    });
</script>
<div class="qw-p5">
    <h2 class="qw-docs-title">微件:Lottery</h2>
    <div id="qw-docs-tabs">
        <ul>
            <li><a href="#qw-docs-overview">概述</a></li>
            <li><a href="#qw-docs-options">选项</a></li>
            <li><a href="#qw-docs-events">事件</a></li>
            <li><a href="#qw-docs-result">结果</a></li>
            <li><a href="#qw-docs-properties">属性</a></li>
            <li><a href="#qw-docs-methods">方法</a></li>
        </ul>
        <div id="qw-docs-overview">
            <table class="ui-table ui-widget-content qw-docs-summary ui-corner-all">
        <tbody>
            <tr class="ui-widget-content ">
                <th class="qw-docs-overview-title ui-corner-tl">名称</th>
                <td><?php echo $data['overview']['name'] ?></td>
            </tr>
            <tr class="ui-widget-content">
                <th class="">版本</th>
                <td><?php echo $data['overview']['version'] ?></td>
            </tr>
            <tr class="ui-widget-content">
                <th class="">介绍</th>
                <td><?php echo $data['overview']['description'] ?></td>
            </tr>
            <!--
            <tr class="ui-widget-content">
                <th class="">使用方法</th>
                <td>
                    <ul>
                        <li>1. 在ide.qq.com下载抽奖微件的类库</li>
                        <li>2. 解压文件,将Qwin目录复制到src/library目录下</li>
                        <li>3. 在程序代码中使用如下方式调用:
                            <pre>
  // 设定选项,详细选项查看文档"选项"一栏
  $options = array(
      'once' => true
  );
  // 加载抽奖微件并渲染
  Qwin::widget('lottery')->render();</pre>
                        </li>
                    </ul>
                </td>
            </tr>
            -->
        </tbody>
    </table>
        </div>
        <div id="qw-docs-options">
            <ul class="qw-docs-option-list">
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-option-type ui-priority-secondary qw-docs-cell">类型</div>
                    <div class="qw-docs-option-default ui-priority-secondary qw-docs-cell">默认值</div>
                </li>
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-header">
                        <h3 class="qw-docs-option-name qw-docs-cell">
                            <a href="#widget-lottery-times">times</a>
                        </h3>
                        <div class="qw-docs-option-type qw-docs-cell">Array</div>
                        <div class="qw-docs-option-default qw-docs-cell">array()</div>
                    </div>
                    <div class="qw-docs-option-content">
                        <div class="qw-docs-option-descrip">
                            允许抽奖的时间段,支持多个时间段,每两个值作为一个时间段
                        </div>
                        <div class="qw-docs-option-example">
                            <pre>如:$lottery->setOption('times', array(
        '2011-08-20', '2011-08-30',
        '2011-09-01', '2011-09-02',
    );</pre>
                        </div>
                    </div>
                </li>
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-header">
                        <h3 class="qw-docs-option-name qw-docs-cell">
                            <a href="#widget-lottery-type">type</a>
                        </h3>
                        <div class="qw-docs-option-type qw-docs-cell">int</div>
                        <div class="qw-docs-option-default qw-docs-cell">null</div>
                    </div>
                    <div class="qw-docs-option-content">
                        <div class="qw-docs-option-descrip">
                            设抽奖奖品的类型,对应数据表的"FAwardType"字段
                        </div>
                        <div class="qw-docs-option-example">
                            
                        </div>
                    </div>
                </li>
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-header">
                        <h3 class="qw-docs-option-name qw-docs-cell">
                            <a href="#widget-lottery-once">once</a>
                        </h3>
                        <div class="qw-docs-option-type qw-docs-cell">Boolen</div>
                        <div class="qw-docs-option-default qw-docs-cell">true</div>
                    </div>
                    <div class="qw-docs-option-content">
                        <div class="qw-docs-option-descrip">
                            是否只允许每个用户获奖一次
                        </div>
                        <div class="qw-docs-option-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div id="qw-docs-events">
            <ul class="qw-docs-event-list">
                <li class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-event-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-event-params ui-priority-secondary qw-docs-cell">参数</div>
                    <div class="qw-docs-event-return ui-priority-secondary qw-docs-cell">返回值</div>
                </li>
                <li class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-event-header">
                        <h3 class="qw-docs-event-name qw-docs-cell">
                            <a href="#widget-lottery-disable">beforeRequest</a>
                        </h3>
                        <div class="qw-docs-event-params qw-docs-cell">(Object(ArrayObject) $widget, Array $list, String $code)</div>
                        <div class="qw-docs-event-return qw-docs-cell">Boolen</div>
                    </div>
                    <div class="qw-docs-event-content">
                        <div class="qw-docs-event-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-event-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
                <li class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-event-header">
                        <h3 class="qw-docs-event-name qw-docs-cell">
                            <a href="#widget-lottery-disable">beforeRequest</a>
                        </h3>
                        <div class="qw-docs-event-params qw-docs-cell">(Object(ArrayObject) $widget, Array $list, String $code)</div>
                        <div class="qw-docs-event-return qw-docs-cell">Boolen</div>
                    </div>
                    <div class="qw-docs-event-content">
                        <div class="qw-docs-event-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-event-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div id="qw-docs-result">
            <ul class="qw-docs-result-list">
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-code ui-priority-secondary qw-docs-cell">代码</div>
                    <div class="qw-docs-result-message ui-priority-secondary qw-docs-cell">信息</div>
                    <div class="qw-docs-result-memo ui-priority-secondary qw-docs-cell">备注</div>
                </li>
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-header">
                        <h3 class="qw-docs-result-code qw-docs-cell">
                            <a href="#">1</a>
                        </h3>
                        <div class="qw-docs-result-message qw-docs-cell">温馨提示:抽奖成功!</div>
                        <div class="qw-docs-result-memo qw-docs-cell">-</div>
                    </div>
                    <div class="qw-docs-result-content">
                        <div class="qw-docs-result-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-result-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-header">
                        <h3 class="qw-docs-result-code qw-docs-cell">
                            <a href="#">2</a>
                        </h3>
                        <div class="qw-docs-result-message qw-docs-cell">信息</div>
                        <div class="qw-docs-result-memo qw-docs-cell">备注</div>
                    </div>
                    <div class="qw-docs-result-content">
                        <div class="qw-docs-result-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-result-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div id="qw-docs-properties">
            <ul class="qw-docs-property-list">
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-property-type ui-priority-secondary qw-docs-cell">类型</div>
                    <div class="qw-docs-property-default ui-priority-secondary qw-docs-cell">默认值</div>
                    <div class="qw-docs-property-extra ui-priority-secondary qw-docs-cell">其他</div>
                </li>
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-header">
                        <h3 class="qw-docs-property-name qw-docs-cell">
                            <a href="#widget-lottery-disable">disabled</a>
                        </h3>
                        <div class="qw-docs-property-type qw-docs-cell">Boolen</div>
                        <div class="qw-docs-property-default qw-docs-cell">false</div>
                        <div class="qw-docs-property-extra qw-docs-cell">Abstract</div>
                    </div>
                    <div class="qw-docs-property-content">
                        <div class="qw-docs-property-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-property-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-header">
                        <h3 class="qw-docs-property-name qw-docs-cell">
                            <a href="#widget-lottery-disable">disabled</a>
                        </h3>
                        <div class="qw-docs-property-type qw-docs-cell">Boolen</div>
                        <div class="qw-docs-property-default qw-docs-cell">false</div>
                        <div class="qw-docs-property-extra qw-docs-cell">Static, Abstract, Final</div>
                    </div>
                    <div class="qw-docs-property-content">
                        <div class="qw-docs-property-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-property-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-header">
                        <h3 class="qw-docs-property-name qw-docs-cell">
                            <a href="#widget-lottery-disable">disabled</a>
                        </h3>
                        <div class="qw-docs-property-type qw-docs-cell">Boolen</div>
                        <div class="qw-docs-property-default qw-docs-cell">false</div>
                        <div class="qw-docs-property-extra qw-docs-cell">Abstract</div>
                    </div>
                    <div class="qw-docs-property-content">
                        <div class="qw-docs-property-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-property-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div id="qw-docs-methods">
            <ul class="qw-docs-method-list">
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-method-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-method-params ui-priority-secondary qw-docs-cell">参数</div>
                    <div class="qw-docs-method-return ui-priority-secondary qw-docs-cell">返回值</div>
                </li>
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-method-header">
                        <h3 class="qw-docs-method-name qw-docs-cell">
                            <a href="#widget-lottery-disable">beforeRequest</a>
                        </h3>
                        <div class="qw-docs-method-params qw-docs-cell">(Object(ArrayObject) $widget, Array $list, String $code)</div>
                        <div class="qw-docs-method-return qw-docs-cell">Boolen</div>
                    </div>
                    <div class="qw-docs-method-content">
                        <div class="qw-docs-method-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-method-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-method-header">
                        <h3 class="qw-docs-method-name qw-docs-cell">
                            <a href="#widget-lottery-disable">beforeRequest</a>
                        </h3>
                        <div class="qw-docs-method-params qw-docs-cell">(Object(ArrayObject) $widget, Array $list, String $code)</div>
                        <div class="qw-docs-method-return qw-docs-cell">Boolen</div>
                    </div>
                    <div class="qw-docs-method-content">
                        <div class="qw-docs-method-descrip">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                        <div class="qw-docs-method-example">
                            Disables (true) or enables (false) the tabs. Can be set when initialising (first creating) the tabs.
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>