<?php
/**
 * Resource
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
 * @subpackage  Resource
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-19 14:48:05
 */

class Qwin_Resource
{
    /**
     * 根路径
     * @var string
     */
    protected $_rootPath;

    protected $_typePath;

    public function __construct()
    {
        $this->_rootPath = QWIN_RESOURCE_PATH;
    }

    public function setRootPath($path)
    {
        $this->_rootPath = $path;
        return $this;
    }

    public function setTypePath($path)
    {
        $this->_typePath = $path;
        return $this;
    }

    public function wrapCode($code)
    {
        return false;
    }
}