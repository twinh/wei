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
 * Cookie
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-02 00:45:14
 */
class Qwin_Cookie extends Qwin_ArrayWidget
{
    /**
     * @var array Options
     * @see http://php.net/manual/en/function.setcookie.php
     */
    public $options = array(
        'expire' => 86400,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httpOnly' => false,
        'raw' => false,
    );

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->_data = &$_COOKIE;
    }

    /**
     * Get or set cookie
     *
     * @param string $key the name of cookie
     * @param mixed $value the value of cookie
     * @param array $options options for set cookie
     * @return Qwin_Cookie
     */
    public function call($key, $value = null, array $options = array())
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $options);
        }
    }

    /**
     * Get cookie
     *
     * @param string $key
     * @param mixed $default default value
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($_COOKIE[$key]) ? @unserialize($_COOKIE[$key]) : $default;
    }

    /**
     * Set cookie
     *
     * @param string $key the name of cookie
     * @param mixed $value the value of cookie
     * @param array $options
     */
    public function set($key, $value, array $options = array())
    {
        $_COOKIE[$key] = serialize($value);

        $o = $options + $this->options;

        $fn = $o['raw'] ? 'setrawcookie' : 'setcookie';
        call_user_func_array($fn, array(
            $key, $_COOKIE[$key], time() + $o['expire'], $o['path'], $o['domain'], $o['secure'], $o['httpOnly']
        ));

        if (0 >= $o['expire']) {
            unset($_COOKIE[$key]);
        }

        return $this;
    }

    /**
     * Remove cookie
     *
     * @param string $key the name of cookie
     */
    public function remove($key)
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            setcookie($key, null, -1);
        }
        return $this;
    }

    /**
     * Remove cookie
     *
     * @param string $key the name of cookie
     * @return Qwin_Cookie
     */
    public function offsetUnset($key)
    {
        return $this->remove($key);
    }
}
