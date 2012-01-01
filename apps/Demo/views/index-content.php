<?php
/**
 * index-content
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2011-9-19 15:02:48
 */
$path = dirname($minify->getPath());
$minify->add(array(
    $jQuery->loadUi('tabs'),
    $path . '/CodeMirror/lib/codemirror.css',
    $path . '/CodeMirror/lib/codemirror.js',
    $path . '/CodeMirror/mode/xml/xml.js',
    $path . '/CodeMirror/mode/javascript/javascript.js',
    $path . '/CodeMirror/mode/css/css.js',
    $path . '/CodeMirror/mode/clike/clike.js',
    $path . '/CodeMirror/mode/php/php.js',
    $path . '/CodeMirror/theme/default.css',
));
?>
<script type="text/javascript">
    jQuery(function($) {
        var editors = {};
        $("#qw-demo").tabs({
            show: function(event, ui){
                setTimeout(function(){
                    var id = $(ui.panel).find('textarea')[0].id;
                    if (undefined == editors[id]) {
                        editors[id] = CodeMirror.fromTextArea($(ui.panel).find('textarea')[0], {
                            lineNumbers: true,
                            readOnly: false,
                            mode: "application/x-httpd-php"
                        });
                    }
                }, 100);
            }
        }).addClass('ui-tabs-vertical ui-helper-clearfix');
        $("#qw-demo li").removeClass('ui-corner-top').addClass('ui-corner-left');
        
        // 调整代码容器的高度和宽度
        var panel = $("#qw-demo .ui-tabs-panel");
        panel.width(panel.width() - $('#qw-demo .ui-tabs-nav').width() - 5);
    });
</script>
<div class="qw-p5">
    <h2 class="qw-demo-title"><?php echo $lang['Tag'] ?>:<?php echo $tag ?></h2>
</div>
<div id="qw-demo">
    <ul>
        <?php
        foreach ($data as $row):
            ?>
            <li><a href="#tabs-<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></li>
            <?php
        endforeach;
        ?>
    </ul>
    <?php
    foreach ($data as $row):
        ?>
        <div id="tabs-<?php echo $row['id'] ?>">
            <textarea id="code-<?php echo $row['id'] ?>" class="qw-demo-code"><?php echo $row['code'] ?></textarea>
        </div>
        <?php
    endforeach;
    ?>
</div>
