<?php
/**
 * Widget Framework
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
 * @version     $Id: Memcache.php 1282 2012-07-06 07:38:44Z itwinh@gmail.com $
 */

namespace Widget;

/**
 * Memcache
 *
 * @package     Widget
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-05-30
 */
class Memcache extends WidgetProvider implements Storable
{
    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'servers' => array(),
        'object' => null,
        'flag' => MEMCACHE_COMPRESSED,
    );

    protected $_serverOptions = array(
        'host' => '127.0.0.1',
        'port' => 11211,
        'persistent' => true,
        'weight' => 0,
        'timeout' => 0,
        'retryInterval' => 0,
        'failureCallback' => null,
    );

    /**
     * Memcache object
     *
     * @var Memcache
     */
    protected $_object;

    /**
     * Init
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $options = $options + $this->options;
        parent::__construct($options);

        $this->_object = new Memcache;
        $this->_object->addserver($options['host'], $options['port']);
    }

    public function get($key, $options = null)
    {
        return $this->_object->get($key, $options);
    }

    public function set($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_objectOptions;

        return $this->_object->set($key, $value, $o['flag'], $expire);
    }

    public function remove($key)
    {
        return $this->_object->delete($key);
    }

    public function add($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_objectOptions;

        return $this->_object->add($key, $value, $o['flag'], $expire);
    }

    public function replace($key, $value, $expire = 0, array $options = array())
    {
        $o = $options + $this->_objectOptions;

        return $this->_object->replace($key, $value, $o['flag'], $expire);
    }

    public function increment($key, $offset = 1)
    {
        return $this->_object->increment($key, $offset);
    }

    public function decrement($key, $offset = 1)
    {
        return $this->_object->decrement($key, $offset);
    }

    public function clear()
    {
        return $this->_object->flush();
    }

    /**
     * Get memcache object
     *
     * @return Memcache
     */
    public function getObject()
    {
        return $this->_object;
    }
}
