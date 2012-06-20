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
 * Apc
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-5-30 20:28:38
 */
class Qwin_Apc extends Qwin_Widget implements Qwin_Storable
{
    protected $_apcOptions = array(
        'expire' => 0,
    );

    public function __construct(array $options = array())
    {
        if (!extension_loaded('apc')) {
            throw new Qwin_Exception('Extension "apc" is not loaded.');
        }

        $options = $options + $this->options;
        parent::__construct($options);
    }

    /**
     *  Get cache
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * Set cache
     *
     * @param string $key
     * @param mixed $value
     * @param array $options
     * @return bool
     */
    public function set($key, $value, $options = array())
    {
        $options = $options + $this->_apcOptions;
        return apc_store($key, $value, $options['expire']);
    }

    /**
     * Remove cache
     *
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        return apc_delete($key);
    }

    public function add($key, $value, $options = array())
    {
        $options = $options + $this->_apcOptions;
        return apc_add($key, $value, $options['expire']);
    }

    public function replace($key, $value, $options = array())
    {
        apc_fetch($key, $success);
        if ($success) {
            $options = $options + $this->_apcOptions;
            return apc_store($key, $value, $options['expire']);
        } else {
            return false;
        }
    }

    public function increment($key, $offset = 1)
    {
        return apc_inc($key, $offset);
    }

    public function decrement($key, $offset = 1)
    {
        return apc_dec($key, $offset);
    }

    public function clear()
    {
        return apc_clear_cache('user');
    }
}