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
 * Memcache
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-05-30 00:55:14
 */
class Qwin_Memcache extends Qwin_Widget implements Qwin_Storable
{
    public $options = array(
        'host' => 'localhost',
        'port' => 11211,
    );

    protected $_memcacheOptions = array(
        'flag' => MEMCACHE_COMPRESSED,
    );

    /**
     * Memcache object
     *
     * @var Memcache
     */
    protected  $_memcache;

    public function __construct(array $options = array())
    {
        $options = $options + $this->options;
        parent::__construct($options);

        $this->_memcache = new Memcache;
        $this->_memcache->connect($options['host'], $options['port']);
    }

    public function get($key, $options = null)
    {
        return $this->_memcache->get($key, $options);
    }

    public function set($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_memcacheOptions;
        return $this->_memcache->set($key, $value, $o['flag'], $expire);
    }

    public function remove($key)
    {
        return $this->_memcache->delete($key);
    }

    public function add($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_memcacheOptions;
        return $this->_memcache->add($key, $value, $o['flag'], $expire);
    }

    public function replace($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_memcacheOptions;
        return $this->_memcache->replace($key, $value, $o['flag'], $expire);
    }

    public function increment($key, $offset = 1)
    {
        return $this->_memcache->increment($key, $offset);
    }

    public function decrement($key, $offset = 1)
    {
        return $this->_memcache->decrement($key, $offset);
    }

    public function clear()
    {
        return $this->_memcache->flush();
    }

    public function getObject()
    {
        return $this->_memcache;
    }
}