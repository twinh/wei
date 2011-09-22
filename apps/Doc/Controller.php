<?php
/**
 * Controller
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
 * @since       2011-08-22 23:44:15
 */

class Doc_Controller extends Controller_Widget
{
    protected $_ignoreTags = array(
        'nternal', 'event',
    );
    
    public function actionIndex()
    {        
        $request = $this->_request;
        $type = $request->get('type');
        $name = $request->get('name');

        $type = Qwin_Util_Array::forceInArray($type, array('class', 'function'));
        if (empty($name)) {
            $name = __CLASS__;
        }

        if ('class' == $type) {
            if (class_exists($name)) {
                $type = 'Class';
            } elseif (interface_exists($name)) {
                $type = 'Interface';
            } else {
                $this->_view->alert('Class or interface "' . $name . '" not found.');
            }
            $reflection = new Qwin_Reflection_Class($name);
        } elseif (function_exists($name)) {
            $type = 'Function';
            $reflection = new Qwin_Reflection_Function($name);
        } else {
            $this->_view->alert('Function "' . $name . '" not found.');
        }
        
        // get the relative file name
        $relativeFile = false;
        $file = $reflection->getFileName();
        foreach ($this->_app->getOption('paths') as $path) {
            $path = realpath(dirname($path) . '/');
            if (false !== ($pos = strpos($file, $path))) {
                $relativeFile = substr($file, strlen($path));
            }
        }
        
        $data = $this->_getDocs($name, $type, $reflection->toArray());
        $tags = $data['tags'];
        $ignoreTags = $this->_ignoreTags;

        $this->_view->assign(get_defined_vars());
    }
    
    public function actionPackage()
    {
        return true;
        // scanf all php file in autoload paths
        $files = array();
        foreach (Qwin::getAutoloadPaths() as $path) {
            $files = array_merge($files, $this->_scanf($path));
        }
        
        foreach ($files as $file) {
            require $file;
            $object = new Qwin_Reflection_File($file);
            qw_p($object->getClasses(), 1);
        }
    }
    
    protected function _scanf($path)
    {
        $found = array();
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            $file2 = $path . '/' . $file;
            if (is_file($file2)) {
                if (64 < ord($file[0]) && 91 > ord($file[0]) && '.php' == substr($file2, -4)) {
                    $found[] = $file2;
                }
            } elseif (is_dir($file2)) {
                $found = array_merge($found, $this->_scanf($file2));
            }
        }
        return $found;
    }
    
    /**
     * Get object's docs defined in docs folders
     * 
     * @param string $name Object name
     * @param string $type Class, Interface or Function
     * @param type $appendData data to append
     * @return array 
     */
    protected function _getDocs($name, $type, $appendData = array())
    {
        $data = array();
        $type == 'Interface' && $type = 'class';
        
        foreach ($this->_app->getOption('paths') as $path) {
            $file = dirname($path) . '/docs/' . $this->_lang->getName() . '/' . strtolower($type) . '.' . strtolower($name) . '.php';
            if (is_file($file)) {
                $data = require $file;
            }
        }
        $data = $data + $appendData;
        return $data;
    }
}
class NClosureFix
{
	static $vars = array();

	static function uses($args)
	{
		self::$vars[] = $args;
		return count(self::$vars)-1;
	}
}