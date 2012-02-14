<?php
/**
 * Qwin Framework
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
 * @category    Qwin
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @todo        width 100%
 */
$minify->add(array(
    $codeMirrorDir . '/lib/codemirror.css',
    $codeMirrorDir . '/lib/codemirror.js',
    $codeMirrorDir . '/mode/xml/xml.js',
    $codeMirrorDir . '/mode/javascript/javascript.js',
    $codeMirrorDir . '/mode/css/css.js',
    $codeMirrorDir . '/mode/clike/clike.js',
    $codeMirrorDir . '/mode/php/php.js',
    $codeMirrorDir . '/theme/default.css',
));
?>
<style type="text/css">
    #qw-code .CodeMirror-scroll {
        height: auto;
        width: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<script type="text/javascript">
    jQuery(function($){
        var editor = CodeMirror.fromTextArea(document.getElementById("qw-code-content"), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "application/x-httpd-php",
            indentUnit: 8,
            indentWithTabs: true,
            enterMode: "keep",
            tabMode: "shift",
            onHighlightComplete: function(){
                var hash = window.location.hash
                if (hash) {
                    hash = hash.substring(1, hash.length) - 1;
                    if (!hash || hash < 0) {
                        return false;
                    }
                    var line = $('#qw-code .CodeMirror-gutter-text pre').eq(hash);
                    $('html').animate({scrollTop:line.offset().top}, 0);
                }
                return true;
            }
        });
    });
</script>
<div id="qw-code">
    <textarea id="qw-code-content"><?php echo $data ?></textarea>
</div>
