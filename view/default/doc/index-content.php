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
));
?>
<script type="text/javascript">
    jQuery(function($){
        $("#qw-docs-tabs").tabs();
        
        $('#qw-docs-tabs .qw-docs-option-name a,    \n\
           #qw-docs-tabs .qw-docs-event-name a,     \n\
           #qw-docs-tabs .qw-docs-result-message a, \n\
           #qw-docs-tabs .qw-docs-property-name a,  \n\
           #qw-docs-tabs .qw-docs-parameter-name a, \n\
           #qw-docs-tabs .qw-docs-method-name a,')
        .qui()
        .click(function(){
            $(this).parent().parent().next().toggle();
        });
        $('#qw-docs-overview h3 a').qui();
        $('#qw-docs-summary a').qui();
        $('#qw-doc-inheritence-details, #qw-doc-inheritence-toggle').click(function(){
            $('#qw-doc-inheritence-tree1').toggle(600);
            $('#qw-doc-inheritence-tree').toggle(600);
        });
    });
</script>
<style type="text/css">
    #qw-docs-summary a {
        border: 0;
        font-weight: normal;
        text-decoration: underline;
    }
    #qw-doc-inheritence-tree dd {
        margin: 0;
        padding: 2px;
    }
    #qw-doc-inheritence-tree a {
        padding:  0 2px;
    }
    #qw-docs-overview h3 {
        margin-bottom: 10px;
    }
    #qw-docs-overview h3 a {
        border-top: 0;
        border-right: 0;
        border-left: 0;
        border-bottom-width: 3px;
        padding-bottom: 2px;
        background: none;
    }
    #qw-docs-overview p {
        line-height: 18px;
    }
    #qw-docs-summary th {
        width: 180px;
    }
</style>
<div class="qw-p5">
    <h2 class="qw-docs-title"><?php echo $lang[$type] ?>:<?php echo $data['name'] ?></h2>
    <div id="qw-docs-tabs">
        <?php
        if ('Function' != $type) :
        ?>
        <ul>
            <li><a href="#qw-docs-overview"><?php echo $lang['Overview'] ?></a></li>
            <?php if (isset($data['options'])) : ?>
            <li><a href="#qw-docs-options"><?php echo $lang['Options'] ?></a></li>
            <?php endif; ?>
            <?php if (isset($data['events'])) : ?>
            <li><a href="#qw-docs-events"><?php echo $lang['Events'] ?></a></li>
            <?php endif; ?>
            <?php if (isset($data['results'])) : ?>
            <li><a href="#qw-docs-results"><?php echo $lang['Results'] ?></a></li>
            <?php endif; ?>
            <li><a href="#qw-docs-properties"><?php echo $lang['Properties'] ?></a></li>
            <li><a href="#qw-docs-methods"><?php echo $lang['Methods'] ?></a></li>
        </ul>
        <div id="qw-docs-overview">
            <h3><a id="short-description" class="ui-state-default" href="#short-description"><?php echo $lang['Short Description'] ?></a></h3>
            <p><?php echo nl2br($data['shortDescription']) ?></p>
            <p><?php echo nl2br($data['longDescription']) ?></p>
            <br />
            <h3><a id="detailed-description" class="ui-state-default" href="#detailed-description"><?php echo $lang['Detailed Description'] ?></a></h3>
            <table id="qw-docs-summary" class="ui-table ui-table-noui ui-widget-content qw-docs-summary">
            <tbody>
                <?php
                if (isset($tags['package'])) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Package'] ?></th>
                    <td><?php echo $tags['package'][0]['description'] ?></td>
                </tr>
                <?php
                    unset($tags['package']);
                endif;
                ?>
                <?php
                if (isset($tags['subpackage'])) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Subpackage'] ?></th>
                    <td><?php echo $tags['subpackage'][0]['description'] ?></td>
                </tr>
                <?php
                    unset($tags['subpackage']);
                endif;
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Inheritence'] ?></th>
                    <td>
                        <div id="qw-doc-inheritence-tree1">
                        <a href="?module=doc&name=<?php echo $data['name'] ?>"><?php echo $data['name'] ?></a>
                        <?php
                        foreach ($data['parents'] as $parent) :
                        ?>
                        » <a href="?module=doc&name=<?php echo $parent ?>"><?php echo $parent ?></a>
                        <?php
                        endforeach;
                        ?>
                        <a id="qw-doc-inheritence-details" href="javascript:;">(<?php echo $lang['Details'] ?>)</a>
                        </div>
                        <div id="qw-doc-inheritence-tree" class="ui-helper-hidden">
                            <dl>
                            <?php
                            $leftPx = 0;
                            foreach (array_reverse($data['inheritence']) as $class => $interfaces) :
                            ?>
                                <dd style="padding-left: <?php echo $leftPx ?>px;">
                                <?php
                                if (0 != $leftPx) :
                                ?>
                                    <img alt="extended by" src="<?php echo $root ?>images/inheritence.gif" />
                                <?php
                                endif;
                                ?>
                               
                                <a href="?module=doc&name=<?php echo $class ?>"><?php echo $class ?></a>
                                    
                                <?php
                                if (!empty($interfaces)) :
                                ?>
                                implements
                                <?php
                                    foreach ($interfaces as $interface) :
                                ?>
                                    <a href="?module=doc&name=<?php echo $interface ?>"><?php echo $interface ?></a> 
                                <?php
                                    endforeach;
                                endif;
                                ?>
                                        
                                <?php
                                if ($data['name'] == $class) :
                                ?>
                                    <a id="qw-doc-inheritence-toggle" href="javascript:;">(<?php echo $lang['Slide up'] ?>)</a>
                                <?php
                                endif;
                                ?>
                                </dd>
                            <?php
                            $leftPx += 30;
                            endforeach;
                            ?>
                            </dl>
                        </div>
                    </td>
                </tr>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Link'] ?></th>
                    <td>
                        <a href="?module=demo&tag=<?php echo $data['name'] ?>"><?php echo $lang['Demo'] ?></a>,
                        <a href="?module=ide/code&value=<?php echo $data['name'] ?>"><?php echo $lang['Source code'] ?></a>
                    </td>
                </tr>
                <?php
                if (isset($tags['author'])) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Author'] ?></th>
                    <td><?php echo $tags['author'][0]['description'] ?></td>
                </tr>
                <?php
                    unset($tags['author']);
                endif;
                ?>
                <?php
                if (isset($tags['since'])) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Since'] ?></th>
                    <td><?php echo $tags['since'][0]['description'] ?></td>
                </tr>
                <?php
                    unset($tags['since']);
                endif;
                ?>
                <?php
                if (isset($tags['version'])) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang['Version'] ?></th>
                    <td><?php echo $tags['version'][0]['description'] ?></td>
                </tr>
                <?php
                    unset($tags['version']);
                endif;
                ?>
                <?php
                foreach ($tags as $name => $tag) :
                    if (in_array($name, $ignoreTags)) :
                        continue;
                    endif;
                    foreach ($tag as $row) :
                ?>
                <tr class="ui-widget-content">
                    <th><?php echo $lang[ucfirst($row['name'])] ?></th>
                    <td class="qw-doc-tag-<?php $row['name'] ?>"><?php echo nl2br($row['description']) ?></td>
                </tr>
                <?php
                    endforeach;
                endforeach;
                ?>
            </tbody>
            </table>
            <br />
            <!--<h3><a class="ui-state-default" href="#">自定义信息</a></h3>
            <p>抽奖微件提供了安全的抽奖操作.</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <p>除了基本的抽奖功能之外,还包括了登陆检查,行为监测,时间段限制,回调事件等功能</p>
            <br />-->
        </div>
        <?php
        if (isset($data['options'])) :
        ?>
        <div id="qw-docs-options">
            <ul class="qw-docs-option-list">
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-name ui-priority-secondary qw-docs-cell"><?php echo $lang['Name'] ?></div>
                    <div class="qw-docs-option-type ui-priority-secondary qw-docs-cell"><?php echo $lang['Type'] ?></div>
                    <div class="qw-docs-option-default ui-priority-secondary qw-docs-cell"><?php echo $lang['Default value'] ?></div>
                </li>
                <?php
                if (!empty($data['options'])) :
                foreach ($data['options'] as $options) :
                ?>
                <li id="option-<?php echo $options['name'] ?>" class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-option-header">
                        <h3 class="qw-docs-option-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $options['name'] ?></a>
                        </h3>
                        <div class="qw-docs-option-type qw-docs-cell"><?php echo $options['type'] ?></div>
                        <div class="qw-docs-option-default qw-docs-cell"><?php echo $options['value'] ?></div>
                    </div>
                    <div class="qw-docs-option-content">
                        <div class="qw-docs-option-descrip"><?php echo $options['description'] ?></div>
                        <div class="qw-docs-option-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-option ui-widget-content">
                    <div class="qw-docs-cell">
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['class'], $lang['options']) ?>
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
                    <div class="qw-docs-event-name ui-priority-secondary qw-docs-cell"><?php echo $lang['Name'] ?></div>
                    <div class="qw-docs-event-params ui-priority-secondary qw-docs-cell"><?php echo $lang['Parameters'] ?></div>
                    <div class="qw-docs-event-return ui-priority-secondary qw-docs-cell"><?php echo $lang['Return'] ?></div>
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
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['class'], $lang['events']) ?>
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
        <div id="qw-docs-results">
            <ul class="qw-docs-result-list">
                <li class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-code ui-priority-secondary qw-docs-cell"><?php echo $lang['Code'] ?></div>
                    <div class="qw-docs-result-message ui-priority-secondary qw-docs-cell"><?php echo $lang['Message'] ?></div>
                    <div class="qw-docs-result-memo ui-priority-secondary qw-docs-cell"><?php echo $lang['Description'] ?></div>
                </li>
                <?php
                if (!empty($data['results'])) :
                foreach ($data['results'] as $result) :
                ?>
                <li id="result-<?php echo $result['code'] ?>" class="qw-docs-result ui-widget-content">
                    <div class="qw-docs-result-header">
                        <div class="qw-docs-result-code qw-docs-cell"><?php echo $result['code'] ?></div>
                        <h3 class="qw-docs-result-message qw-docs-cell">
                            <a href="javascript:;"><?php echo $result['message'] ?></a>
                        </h3>
                        <div class="qw-docs-result-memo qw-docs-cell"><?php echo $result['description'] ?></div>
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
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['class'], $lang['results']) ?>
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
                    <div class="qw-docs-property-modifiers ui-priority-secondary qw-docs-cell"><?php echo $lang['Modifiers'] ?></div>
                    <div class="qw-docs-property-name ui-priority-secondary qw-docs-cell"><?php echo $lang['Name'] ?></div>
                    <div class="qw-docs-property-type ui-priority-secondary qw-docs-cell"><?php echo $lang['Type'] ?></div>
                    <div class="qw-docs-property-default ui-priority-secondary qw-docs-cell"><?php echo $lang['Default value'] ?></div>
                </li>
                <?php
                if (!empty($data['properties'])) :
                foreach ($data['constants'] + $data['properties'] as $property) :
                ?>
                <li id="property-<?php echo $property['name'] ?>" class="qw-docs-property ui-widget-content">
                    <div class="qw-docs-property-header">
                        <div class="qw-docs-property-modifiers qw-docs-cell"><?php echo $property['modifiers'] ?></div>
                        <h3 class="qw-docs-property-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $property['varName'] ?></a>
                        </h3>
                        <div class="qw-docs-property-type qw-docs-cell"><?php echo $property['type'] ?></div>
                        <div class="qw-docs-property-default qw-docs-cell"><?php echo $property['valueText'] ?></div>
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
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['class'], $lang['properties']) ?>
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
                    <div class="qw-docs-method-modifiers ui-priority-secondary qw-docs-cell"><?php echo $lang['Modifiers'] ?></div>
                    <div class="qw-docs-method-name ui-priority-secondary qw-docs-cell"><?php echo $lang['Name'] ?></div>
                    <div class="qw-docs-method-params ui-priority-secondary qw-docs-cell"><?php echo $lang['Parameters'] ?></div>
                    <div class="qw-docs-method-return ui-priority-secondary qw-docs-cell"><?php echo $lang['Return'] ?></div>
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
                        <div class="qw-docs-method-params qw-docs-cell"><?php echo $method['parameterText'] ?></div>
                        <div class="qw-docs-method-return qw-docs-cell"><?php echo $method['return'] ?></div>
                    </div>
                    <div class="qw-docs-method-content">
                        <div class="qw-docs-method-descrip"><?php echo $method['shortDescription'] ?></div>
                        <div class="qw-docs-method-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-method ui-widget-content">
                    <div class="qw-docs-cell">
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['class'], $lang['methods']) ?>
                    </div>
                </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
        <?php
        else:
        ?>
        <ul>
            <li><a href="#qw-docs-overview"><?php echo $lang['Overview'] ?></a></li>
            <li><a href="#qw-docs-parameters"><?php echo $lang['Parameters'] ?></a></li>
        </ul>
        <div id="qw-docs-overview">
            <table class="ui-table ui-widget-content qw-docs-summary ui-corner-all">
            <tbody>
                <tr class="ui-widget-content ">
                    <th class="qw-docs-overview-title ui-corner-tl"><?php echo $lang['Name'] ?></th>
                    <td><?php echo $data['name'] ?></td>
                </tr>
                <tr class="ui-widget-content">
                    <th class=""><?php echo $lang['How to use'] ?></th>
                    <td><?php echo $data['name'] . $data['parameterText'] ?></td>
                </tr>
                <tr class="ui-widget-content">
                    <th class=""><?php echo $lang['Short description'] ?></th>
                    <td><?php echo $data['shortDescription'] ?></td>
                </tr>
            </tbody>
            </table>
        </div>
        <div id="qw-docs-parameters">
            <ul class="qw-docs-parameter-list">
                <li class="qw-docs-parameter ui-widget-content">
                    <div class="qw-docs-parameter-name ui-priority-secondary qw-docs-cell"><?php echo $lang['Name'] ?></div>
                    <div class="qw-docs-parameter-type ui-priority-secondary qw-docs-cell"><?php echo $lang['Type'] ?></div>
                    <div class="qw-docs-parameter-default ui-priority-secondary qw-docs-cell"><?php echo $lang['Default value'] ?></div>
                </li>
                <?php
                if (!empty($data['parameters'])) :
                foreach ($data['parameters'] as $parameter) :
                ?>
                <li id="parameter-<?php echo $parameter['name'] ?>" class="qw-docs-parameter ui-widget-content">
                    <div class="qw-docs-parameter-header">
                        <h3 class="qw-docs-parameter-name qw-docs-cell">
                            <a href="javascript:;"><?php echo $parameter['varName'] ?></a>
                        </h3>
                        <div class="qw-docs-parameter-type qw-docs-cell"><?php echo $parameter['type'] ? $parameter['type'] : '-' ?></div>
                        <div class="qw-docs-parameter-default qw-docs-cell"><?php echo $parameter['value'] ? $parameter['value'] : '-' ?></div>
                    </div>
                    <div class="qw-docs-parameter-content">
                        <div class="qw-docs-parameter-descrip"></div>
                        <div class="qw-docs-parameter-example"></div>
                    </div>
                </li>
                <?php
                endforeach;
                else :
                ?>
                <li class="qw-docs-parameter ui-widget-content">
                    <div class="qw-docs-cell">
                        <?php echo sprintf($lang['The %s does not defined any %s.'], $lang['function'], $lang['parameters']) ?>
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
    </div>
</div>