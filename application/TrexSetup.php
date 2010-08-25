<?php
/**
 * TrexSetup
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-04 17:13:37
 */

class TrexSetup extends Qwin_Trex_Setup
{
    protected $_appPath;

    public function  __construct($config, $set)
    {
        parent::__construct($config, $set);
    }

    public function getAppPath()
    {
        if(isset($this->_appPath))
        {
            return $this->_appPath;
        }
        $this->_appPath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        return $this->_appPath;
    }

    public function autoload($className)
    {
       $classPath = $this->getAppPath() . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
       if(file_exists($classPath))
       {
           require $classPath;
           return true;
       }
       return false;
    }

    protected function _onNamespaceLoad()
    {
        spl_autoload_register(array($this, 'autoload'));
    }
}
