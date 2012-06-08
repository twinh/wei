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
 * Redis
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-06-07 21:38:16
 */
class Qwin_Redis extends Qwin_Widget implements Qwin_Storable
{
    /**
     * Redis object
     *
     * @var Redis
     */
    protected $_object;

    public $options = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 0,
    );

    protected $_redisOptions = array(
        'expire' => 0,
    );

    public function __construct(array $options = array())
    {
        $options = $options + $this->options;
        parent::__construct($options);

        $this->_object = new Redis();
        $this->_object->connect($options['host'], $options['port'], $options['timeout']);
    }

    public function __invoke()
    {
        return $this;
    }

    public function get($key)
    {
        return $this->_object->get($key);
    }

    public function set($key, $value, $options = array())
    {
        $o = $options + $this->_redisOptions;
        return $this->_object->set($key, $value, $o['expire']);
    }

    public function add($key, $value, $options = array())
    {
        if ($this->_object->exists($key)) {
            return false;
        }

        $o = $options + $this->_redisOptions;
        return $this->_object->set($key, $value, $o['expire']);
    }

    public function clear()
    {
        return $this->_object->flushAll();
    }

    public function increment($key, $offset = 1)
    {
        return $this->_object->incrBy($key, $offset);
    }

    public function decrement($key, $offset = 1)
    {
        return $this->_object->decrBy($key, $offset);
    }

    public function remove($key)
    {
        return $this->_object->del($key);
    }

    public function replace($key, $value, $options = array())
    {
        if (!$this->_object->exists($key)) {
            return false;
        }

        $o = $options + $this->_redisOptions;
        return $this->_object->set($key, $value, $o['expire']);
    }
}