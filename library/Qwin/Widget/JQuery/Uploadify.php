<?php
/**
 * Uploadify
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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-11-27 00:08:01
 */

class Qwin_Widget_JQuery_Uploadify
{
    // TODO !!!
    public function render($meta)
    {
        $jquery = Qwin::call('Qwin_Resource_JQuery');
        $pluginPath = QWIN . '/js/jquery/plugin/uploadify';

        $addCode = '';
        if ('' != $meta['_value']) {
            $imageList = explode('|', $meta['_value']);
            foreach ($imageList as $image) {
                $addCode .= 'addQwUpload(\'' . $image . '\');';
            }
        }

        $code = '<script type="text/javascript" src="' . $pluginPath . '/swfobject.js' . '"></script>'
              . $jquery->loadPlugin('uploadify', 'v2.1.4')
              . '<script type="text/javascript">
                  jQuery(function($){
                  $("#' . $meta['id'] . '").before(\'<ul class="ui-upload-plane"></ul><div style="clear: both;"></div>\');
                  ' . $addCode . '
            function addQwUpload(url)
			{
				$("ul.ui-upload-plane")
					.append(\'<li class="ui-widget-content ui-corner-all"><a target="_blank" href="\' +
						url + \'"><img alt="" src="\' + url + \'" /></a><p><input type="hidden" name="' . $meta['id'] . '-upload[]" value="\' +
						url + \'" /><a class="ui-upload-delete" href="javascript:;">删除</a></p></li>\')
					.find("li")
					.qui()
					.find("a.ui-upload-delete")
					.click(function(){
						$(this).parent().parent().fadeOut(500, function(){
							$(this).remove();
						})
					});
			}
            $("#' . $meta['id'] . '").uploadify({
                "uploader"  : "' . $pluginPath . '/uploadify.swf",
                "script"    : "uploadify.php",
                "cancelImg" : "' . $pluginPath . '/cancel.png",
                "folder"    : "./upload",
                "auto"      : true,
                "multi"     : true,
				"fileExt"     : "*.jpg;*.gif;*.png",
				"fileDesc"    : "Image Files",
				"onComplete"  : function(event, ID, fileObj, response, data) {
					//addQwUpload(fileObj.filePath);
                    addQwUpload(response);
				}
            });
        });
    </script>';
        return $code;
    }
}
