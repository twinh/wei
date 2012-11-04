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
 * Redis
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-06-07
 */
class Redis extends WidgetProvider implements Storable
{
    /**
     * Redis object
     *
     * @var Redis
     */
    protected $_object;

    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 0.0,
        'object' => null,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $options = $this->options;

        if ($options['object'] && $options['object'] instanceof Redis) {
            $this->_object = $options['object'];
        } else {
            $this->_object = new Redis();
            $this->_object->connect($options['host'], $options['port'], $options['timeout']);
        }
    }

    /**
     * Get or set cache
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value
     * @param  int    $expire expire time for set cache
     * @return mixed
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
        return $this->_object->get($key);
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
        return $this->_object->set($key, $value, $expire);
    }

    /**
     * Add cache, if cache is exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire is not supported by redis when add new cache
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return $this->_object->setnx($key, $value);
        /*$this->_object->watch($key);

        if ($this->_object->exists($key)) {
            $this->_object->unwatch();

            return false;
        } else {
            $result = $this->_object
                ->multi()
                ->set($key, $value, $expire)
                ->exec();

            $this->_object->unwatch();

            return $result[0];
        }*/
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param  string $key    the name of cache
     * @param  mixed  $value  the value of cache
     * @param  int    $expire expire is not supported by redis when replace cache
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        return $this->_object->getSet($key, $value);
    }

    /**
     * Clear all cache
     *
     * @return boolean
     */
    public function clear()
    {
        return $this->_object->flushAll();
    }

    /**
     * Increase a cache, if cache is not exists, redis will automatic creat it,
     * this is different with other cache driver
     *
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
     * @return int|false
     */
    public function increment($key, $offset = 1)
    {
        return $this->_object->incrBy($key, $offset);
    }

    /**
     * Decrease a cache
     *
     * @param  string    $key    the name of cache
     * @param  int       $offset the value to decrease
     * @return int|false
     * @see Qwin_Redis::increment
     */
    public function decrement($key, $offset = 1)
    {
        return $this->_object->decrBy($key, $offset);
    }

    /**
     * Remove cache by key
     *
     * @param  string $key the name of cache
     * @return bool
     */
    public function remove($key)
    {
        return $this->_object->del($key);
    }

    /**
     * Get redis object
     *
     * @return Redis
     */
    public function getObject()
    {
        return $this->_object;
    }
}
