<?php
/**
 * CKEditor
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
 * @since       2010-08-20 13:41:54
 */

class Qwin_Widget_Editor_CKEditor
{
    public function __construct()
    {
        
    }

    public function render($meta)
    {
        $code = '<script type="text/javascript" src="' . QWIN_RESOURCE_PATH . '/js/ckeditor/ckeditor.js"></script>
                 <script type="text/javascript" src="' . QWIN_RESOURCE_PATH . '/js/ckfinder/ckfinder.js"></script>
                 <script type="text/javascript">
                    var ckeditor = CKEDITOR.replace("' . $meta['id'] . '");
                    CKFinder.setupCKEditor(ckeditor, "' . QWIN_RESOURCE_PATH . '/js/ckfinder/" );
                 </script>
        ';

        // 配置 CKFinder
        require_once QWIN_RESOURCE_PATH . DS . 'js/ckfinder/qwin_interface.php';
        $qwin_interface = new Qwin_CKFinder_Interface();
        // TODO param 2 登陆的标准
        $path = dirname($_SERVER['SCRIPT_NAME']) . '/upload/';
        // $path = '/' . basename(QWIN_ROOT_PATH) . '/public/upload/';
        $qwin_interface->setInterface($path, true);

        return $code;
    }
}
