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

namespace Qwin;

/**
 * Apc
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-05-30
 */
class Apc extends Widget implements Storable
{
    public function __construct(array $options = array())
    {
        if (!extension_loaded('apc')) {
            $this->exception('Extension "apc" is not loaded.');
        }

        $options = $options + $this->options;
        parent::__construct($options);
    }

    /**
     * Get or set cache
     *
     * @param  string   $key    the name of cache
     * @param  mixed    $value
     * @param  int      $expire expire time for set cache
     * @return Qwin_Apc
     */
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * Get cache
     *
     * @param  string      $key the name of cache
     * @return mixed|false
     */
    public function get($key, $options = null)
    {
        return apc_fetch($key, $options);
    }

    /**
     * Set cache
     *
     * @param  string $key    the name of cache
     * @param  value  $value  the value of cache
     * @param  int    $expire expire time, 0 means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        return apc_store($key, $value, $expire);
    }

    /**
     * Remove cache by key
     *
     * @param  string $key the name of cache
     * @return bool
     */
    public function remove($key)
    {
        return apc_delete($key);
    }

    /**
     * Add cache, if cache is exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire time
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return apc_add($key, $value, $expire);
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire time
     * @return bool
     * @todo lock ? atom ?
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        apc_fetch($key, $success);
        if ($success) {
            return apc_store($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * Increase a numerical cache
     *
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
     * @return int|false
     */
    public function increment($key, $offset = 1)
    {
        return apc_inc($key, $offset);
    }

    /**
     * Decrease a numerical cache
     *
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
     * @return int|false
     */
    public function decrement($key, $offset = 1)
    {
        return apc_dec($key, $offset);
    }

    /**
     * Clear all user apc cache
     *
     * @return boolean
     */
    public function clear()
    {
        return apc_clear_cache('user');
    }
}
