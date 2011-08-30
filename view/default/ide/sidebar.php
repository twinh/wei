<?php
/**
 * sidebar
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
 * @since       2011-5-28 12:48:54
 */
$minify->add(array(
    $jQuery->loadPlugin('jstree'),
));
?>
<script type="text/javascript">
jQuery(function($){
    var moduleTree = $('div.qw-ide-module-tree').jstree({
        'plugins' : ['themes', 'json_data', 'ui', 'themeroller'],
        'json_data' :　{
            'ajax' : {
                'url' : qwin.url.createUrl({
                    'module' : 'ide',
                    'json' : true
                }),
                'data' : function (n) {
                    return {
                        folder : n.find ? n.find('a').attr('name') : '',
                        t : (new Date()).toString()
                    }; 
                }
            }
        }
    });
    $('div.ui-sidebar-header a').click(function(){
        moduleTree.jstree('refresh',-1);
    })
});
</script>
<div class="ui-sidebar-header ui-state-default">
    <a href="javascript:;" class="ui-iconx ui-iconx-briefcase-16"><?php echo $lang['MODULE'] ?></a>
</div>
<div class="qw-ide-module-tree">
</div>