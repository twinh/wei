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
 * XCache
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-06-07 21:26:00
 */
class Qwin_Xcache extends Qwin_Widget implements Qwin_Storable
{
    public $options = array(
        'user' => null,
        'pass' => null,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if (!(extension_loaded('xcache'))) {
            throw new Qwin_Exception('Extension "xcache" is not loaded.');
        }
    }

    public function add($key, $value, $expore = 0, array $options = array())
    {
        if (xcache_isset($key)) {
            return false;
        }

        return xcache_set($key, $value, $expore);
    }

    public function get($key, $options = null)
    {
        return xcache_get($key);
    }

    public function increment($key, $offset = 1)
    {
        return xcache_inc($key, $offset);
    }

    public function decrement($key, $offset = 1)
    {
        return xcache_dec($key, $offset);
    }

    public function remove($key)
    {
        return xcache_unset($key);
    }

    public function replace($key, $value, $expore = 0, array $options = array())
    {
        if (!xcache_isset($key)) {
            return false;
        }

        return xcache_set($key, $value, $expore);
    }

    public function set($key, $value, $expire = 0, array $options = array())
    {
        return xcache_set($key, $value, $expire);
    }

    public function clear()
    {
        return xcache_clear_cache(XC_TYPE_VAR, 0);
    }
}