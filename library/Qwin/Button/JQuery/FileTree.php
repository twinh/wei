<?php
/**
 * FileTree
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
 * @subpackage  Button
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-19 14:03:50
 */

class Qwin_Button_JQuery_FileTree
{
    public function __construct()
    {
        
    }

    public function render($meta)
    {
        $jquery = Qwin::run('Qwin_Resource_JQuery');
        $buttonId = 'ui-button-filetree-' . $meta['name'];

        $ajaxButtonId = 'ui-button-ajaxupload-' . $meta['name'];

        $code = $jquery->loadUi('position')
            . $jquery->loadUi('dialog')
            . $jquery->loadPlugin('qfiletree')
            . '<button id="' . $buttonId . '" type="button"><span class="ui-icon ui-icon-image">' . $meta['name'] . '</span></button>
              <script type="text/javascript">jQuery(function($){
                $("#' . $buttonId . '").QFileTree({
                    input : "#' . $meta['name'] . '",
                    filetree : {
                        dblClickFolder : function(path){
                            $("#qfiletree_dialog").dialog("close");
                            $("#' . $ajaxButtonId . '").ajaxUpload({
                                input : "#' . $meta['name'] . '",
                                path : path
                            });
                            $("#' . $ajaxButtonId . '").click();
                        }
                    }
                })});
              </script>';
        return $code;
    }
}
/**
$js = Qwin::run('-js');
        $rsc = Qwin::run('-rsc')
            ->load('jquery/ui/dialog')
            ->load('jquery/plugin/qfiletree');
        $id = 'icon-button-filetree-' . $set['id'];

        $html = '<a class="ui-state-default ui-corner-all icon-button-common" id="' . $id . '" href="javascript:void(0)">
                <span class="ui-icon ui-icon-info"></span>
                </a>';
        $code = '$("#' . $id . '").QFileTree({
        input : "#' . $set['name'] . '",
        filetree : {
            dblClickFolder : function(path){
                $("#qfiletree_dialog").dialog("close");
                $("#ajax_upload_button_' . $set['name'] . '").ajaxUpload({
                    input : "#' . $set['name'] . '",
                    path : path
                });
                $("#ajax_upload_button_' . $set['name'] . '").click();
            }
        }
    });
                    ';
        $js->addJq($set['name'], $code);

        return $html;
 */