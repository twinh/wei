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
 * @subpackage  Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-19 14:03:50
 */

class Qwin_Widget_JQuery_FileTree
{
    /**
     * 生成文件树
     *
     * @param array $meta 域的元数据
     * @return string 文件树代码
     */
    public function render($meta)
    {
        $jquery = Qwin::run('Qwin_Resource_JQuery');
        $cssPacker = Qwin::run('Qwin_Packer_Css');
        $jsPacker = Qwin::run('Qwin_Packer_Js');

        $positionFile = $jquery->loadUi('position', false);
        $dialogFile = $jquery->loadUi('dialog', false);
        $qFileTreeFile = $jquery->loadPlugin('qfiletree', null, false);

        $cssPacker
            ->add($positionFile['css'])
            ->add($dialogFile['css'])
            ->add($qFileTreeFile['css']);
        $jsPacker
            ->add($positionFile['js'])
            ->add($dialogFile['js'])
            ->add($qFileTreeFile['js']);

        $buttonId = 'ui-button-filetree-' . $meta['id'];
        $ajaxButtonId = 'ui-button-ajaxupload-' . $meta['id'];

        $code = '<button id="' . $buttonId . '" type="button"><span class="ui-icon ui-icon-image">' . $meta['id'] . '</span></button>
              <script type="text/javascript">jQuery(function($){
                $("#' . $buttonId . '").QFileTree({
                    input : "#' . $meta['id'] . '",
                    filetree : {
                        dblClickFolder : function(path){
                            $("#qfiletree_dialog").dialog("close");
                            $("#' . $ajaxButtonId . '").ajaxUpload({
                                input : "#' . $meta['id'] . '",
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
