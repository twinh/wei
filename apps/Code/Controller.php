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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Controller
 * 
 * @category    Qwin
 * @package     Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-09-20 15:13:38
 */
class Ide_Code_Controller extends Qwin_Controller
{  
    public function indexAction()
    {
        $request = $this->request;
        $value = htmlspecialchars($request->get('value'));
        $type = Qwin_Util_Array::forceInArray($request->get('type'), array('object', 'file'));
        
        if (empty($value)) {
            $value = __CLASS__;
        }
        
        if ('object' === $type) {
            if (class_exists($value) || interface_exists($value)) {
                $object = new Qwin_Reflection_Class($value);
            } elseif (function_exists($value)) {
                $object = new Qwin_Reflection_Function($value);
            } else {
                return $this->_view->alert('Class, interface or function "' . $value . '" not found.');
            }
            
            if (false != ($file = $object->getFileName())) {
                $data = file_get_contents($file);
            } else {
                // TODO toSource ?
                $data = $object->__toString();
            }
        } else {
            // It's not allowed to show config file.
            if (strripos($value, 'config.php')) {
                return $this->view->alert(sprintf('File "%s" not found.', htmlspecialchars($value)));
            }

            foreach ($this->app->option('paths') as $path) {
                $file = dirname($path) . '/' . $value;
                if (is_file($file)) {
                    $data = file_get_contents($file);
                }
            }
            
            if (!isset($data)) {
                return $this->view->alert(sprintf('File "%s" not found.', htmlspecialchars($value)));
            }
        }
        
        // 初始化CodeMirror目录
        $codeMirrorDir = $this->codeMirror->getDir();
        
        return $this->view->assign(get_defined_vars());
    }
}
