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
 * Request
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-02-13 23:02:00
 */
class Qwin_Request extends Qwin_ArrayWidget
{
    public function __construct($options = null)
    {
        $this->_data = $_REQUEST;
    }

    /**
     * Get request data as widget variable
     *
     * @param string $name
     * @param mixed $default
     * @return Qwin_Widget
     */
    public function __invoke($name = null, $default = null)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : $default;
    }

    /**
     * Add request data
     *
     * @param string|array $name
     * @param mixed $value
     * @return Qwin_Reqeust
     */
    public function add($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->_data[$key] = $value;
            }
        } else {
            $this->_data[$name] = $value;
        }
        return $this;
    }

    /**
     * Remove get data
     *
     * @param string $name
     * @return Qwin_Request
     */
    public function remove($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
        return $this;
    }
}
