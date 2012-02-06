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
 * Module
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-03-22 15:36:41
 */
class Qwin_Module extends Qwin_Widget
{
    protected $_name;

    public $options = array(
        'key' => 'module',
        'default' => 'index',
    );

    public function __construct($options = null)
    {
        parent::__construct($options);
        $options = &$this->options;

        $module = $this->request($options['key'])->toString();
        $this->_name = $module ? $module : $options['default'];
    }

    /**
     * Get module object or set module name
     *
     * @param string $name
     * @return Qwin_Module
     */
    public function call($name = null)
    {
        if ($name) {
            $this->_name = (string)$name;
        }
        return $this;
    }

    /**
     * Get module string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_name;
    }

    /**
     * Get module string
     *
     * @return string
     */
    public function toString()
    {
        return $this->_name;
    }
}