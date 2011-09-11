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
           #qw-docs-tabs .qw-docs-result-message a, \n\
           #qw-docs-tabs .qw-docs-property-name a,\n\
           #qw-docs-tabs .qw-docs-method-name a,')
        .qui()
        .click(function(){
            $(this).parent().parent().next().toggle();
        });
    });
</script>
<div class="qw-p5">
    <h2 class="qw-docs-title"><?php echo $lang[$type] ?>:<?php echo $data['name'] ?></h2>
    <div id="qw-docs-tabs">
        <ul>
            <li><a href="#qw-docs-overview">概述</a></li>
            <?php if (isset($data['options'])) : ?>
            <li><a href="#qw-docs-options">选项</a></li>
            <?php endif; ?>
            <?php if (isset($data['events'])) : ?>
            <li><a href="#qw-docs-events">事件</a></li>
            <?php endif; ?>
            <?php if (isset($data['results'])) : ?>
            <li><a href="#qw-docs-result">结果</a></li>
            <?php endif; ?>
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
                <td><pre><?php echo $data['overview']['description'] ?></pre></td>
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
        <?php
        if (isset($data['options'])) :
        ?>
        <div id="qw-docs-options">
            <ul class="qw-docs-option-list">
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-option-type ui-priority-secondary qw-docs-cell">类型</div>
                    <div class="qw-docs-option-default ui-priority-secondary qw-docs-cell">默认值</div>
                </li>
                <?php
                if (!empty($data['options'])) :
                foreach ($data['options'] as $options) :
                ?>
                <li id="option-<?php echo $options[0] ?>" class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-header">
                        <h3 class="qw-docs-option-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $options[0] ?></a>
                        </h3>
                        <div class="qw-docs-option-type qw-docs-cell"><?php echo $options[1] ?></div>
                        <div class="qw-docs-option-default qw-docs-cell"><?php echo $options[3] ?></div>
                    </div>
                    <div class="qw-docs-option-content">
                        <div class="qw-docs-option-descrip"><?php echo $options[2] ?></div>
                        <div class="qw-docs-option-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-cell">
                        该微件没有选项.
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
        <?php
        endif;
        ?>
        <?php
        if (isset($data['events'])) : 
        ?>
        <div id="qw-docs-events">
            <ul class="qw-docs-event-list">
                <li class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-event-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-event-params ui-priority-secondary qw-docs-cell">参数</div>
                    <div class="qw-docs-event-return ui-priority-secondary qw-docs-cell">返回值</div>
                </li>
                <?php
                if (!empty($data['events'])) :
                foreach ($data['events'] as $event) :
                ?>
                <li id="event-<?php echo $event['name'] ?>" class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-event-header">
                        <h3 class="qw-docs-event-name qw-docs-cell">
                            <a href="#event-<?php echo $event['name'] ?>"><?php echo $event['name'] ?></a>
                        </h3>
                        <div class="qw-docs-event-params qw-docs-cell"><?php echo $event['param'] ?></div>
                        <div class="qw-docs-event-return qw-docs-cell"><?php echo $event['return'] ?></div>
                    </div>
                    <div class="qw-docs-event-content">
                        <div class="qw-docs-event-descrip"><?php echo $event['description'] ?></div>
                        <div class="qw-docs-event-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-event ui-widget-content">
                    <div class="qw-docs-cell">
                        该微件没有回调事件.
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
        <?php 
        endif;
        ?>
        <?php
        if (isset($data['results'])) : 
        ?>
        <div id="qw-docs-result">
            <ul class="qw-docs-result-list">
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-code ui-priority-secondary qw-docs-cell">代码</div>
                    <div class="qw-docs-result-message ui-priority-secondary qw-docs-cell">信息</div>
                    <div class="qw-docs-result-memo ui-priority-secondary qw-docs-cell">备注</div>
                </li>
                <?php
                if (!empty($data['results'])) :
                foreach ($data['results'] as $result) :
                ?>
                <li id="result-<?php echo $result[0] ?>" class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-header">
                        <div class="qw-docs-result-code qw-docs-cell"><?php echo $result[0] ?></div>
                        <h3 class="qw-docs-result-message qw-docs-cell">
                            <a href="javascript:;"><?php echo $result[1] ?></a>
                        </h3>
                        <div class="qw-docs-result-memo qw-docs-cell"><?php echo $result[2] ?></div>
                    </div>
                    <div class="qw-docs-result-content">
                        <div class="qw-docs-result-descrip"></div>
                        <div class="qw-docs-result-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-cell">
                        该微件没有操作结果.
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
        <?php
        endif;
        ?>
        <div id="qw-docs-properties">
            <ul class="qw-docs-property-list">
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-modifiers ui-priority-secondary qw-docs-cell">修饰符</div>
                    <div class="qw-docs-property-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-property-type ui-priority-secondary qw-docs-cell">类型</div>
                    <div class="qw-docs-property-default ui-priority-secondary qw-docs-cell">默认值</div>
                </li>
                <?php
                if (!empty($data['properties'])) :
                foreach ($data['properties'] as $property) :
                ?>
                <li id="property-<?php echo $property['name'] ?>" class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-header">
                        <div class="qw-docs-property-modifiers qw-docs-cell"><?php echo $property['modifiers'] ?></div>
                        <h3 class="qw-docs-property-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $property['name'] ?></a>
                        </h3>
                        <div class="qw-docs-property-type qw-docs-cell"><?php echo $property['type'] ?></div>
                        <div class="qw-docs-property-default qw-docs-cell"><?php echo $property['value'] ?></div>
                    </div>
                    <div class="qw-docs-property-content">
                        <div class="qw-docs-property-descrip"></div>
                        <div class="qw-docs-property-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-cell">
                        该微件没有属性.
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
        <div id="qw-docs-methods">
            <ul class="qw-docs-method-list">
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-method-modifiers ui-priority-secondary qw-docs-cell">修饰符</div>
                    <div class="qw-docs-method-name ui-priority-secondary qw-docs-cell">名称</div>
                    <div class="qw-docs-method-params ui-priority-secondary qw-docs-cell">参数</div>
                    <div class="qw-docs-method-return ui-priority-secondary qw-docs-cell">返回值</div>
                </li>
                <?php
                if (!empty($data['methods'])) :
                foreach ($data['methods'] as $method) :
                ?>
                <li id="method-<?php echo $method['name'] ?>" class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-method-header">
                        <div class="qw-docs-method-modifiers qw-docs-cell"><?php echo $method['modifiers'] ?></div>
                        <h3 class="qw-docs-method-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $method['name'] ?></a>
                        </h3>
                        <div class="qw-docs-method-params qw-docs-cell"><?php echo $method['param'] ?></div>
                        <div class="qw-docs-method-return qw-docs-cell"><?php echo $method['return'] ?></div>
                    </div>
                    <div class="qw-docs-method-content">
                        <div class="qw-docs-method-descrip"><?php echo $method['description'] ?></div>
                        <div class="qw-docs-method-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-cell">
                        该微件没有方法.
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
    </div>
</div>