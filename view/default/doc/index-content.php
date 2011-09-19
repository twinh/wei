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
    });
</script>
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
            <table class="ui-table ui-widget-content qw-docs-summary ui-corner-all">
            <tbody>
                <tr class="ui-widget-content ">
                    <th class="qw-docs-overview-title ui-corner-tl"><?php echo $lang['Name'] ?></th>
                    <td><?php echo $data['name'] ?></td>
                </tr>
                <tr class="ui-widget-content">
                    <th class=""><?php echo $lang['Version'] ?></th>
                    <td><?php echo $data['version'] ?></td>
                </tr>
                <tr class="ui-widget-content">
                    <th class=""><?php echo $lang['Short description'] ?></th>
                    <td><pre><?php echo $data['shortDescription'] ?></pre></td>
                </tr>
                <tr class="ui-widget-content ">
                    <th class="qw-docs-overview-title ui-corner-tl">父类</th>
                    <td>
                    <?php
                    if ($data['extends']) :
                    foreach ($data['extends'] as $class) : 
                    ?>
                        <a href="?module=doc&name=<?php echo $class ?>"><?php echo $class ?></a>
                    <?php
                    endforeach;
                    else :
                    ?>
                        -
                    <?php
                    endif;
                    ?>
                    </td>
                </tr>
                <tr class="ui-widget-content ">
                    <th class="qw-docs-overview-title ui-corner-tl">接口</th>
                    <td>
                    <?php
                    if ($data['interfaces']) :
                    foreach ($data['interfaces'] as $interface) : 
                    ?>
                        <a href="?module=doc&name=<?php echo $interface ?>"><?php echo $interface ?></a>
                    <?php
                    endforeach;
                    else :
                    ?>
                        -
                    <?php
                    endif;
                    ?>
                    </td>
                </tr>
                <tr class="ui-widget-content">
                    <th class=""><?php echo $lang['Demo'] ?></th>
                    <td><a target="_blank" href="?module=demo&tag=<?php echo $data['name'] ?>"><?php echo $lang['Demo'] ?></a></td>
                </tr>
            </tbody>
            </table>
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