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
 * Cache
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-06-03 22:11:39
 */
class Qwin_Cache extends Qwin_Widget implements Qwin_Storable
{
    public $options = array(
        'master' => 'memcache',
        'slave' => 'fCache',
        'masterOptions' => array(),
        'slaverOptions' => array(),
        'time' => 10,
    );

    protected $_master;

    protected $_slave;

    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $options = $this->options;

        $this->_master = $this->$options['master'];

        $this->_slave = $this->$options['slave'];
    }

    public function get($key)
    {
        $value = $this->_master->get($key);

        if (!$value) {
            return $this->_slave->get($key);
        }
    }

    public function set($key, $value, $options = array())
    {
        $value = $this->_master->set($key, $value, $options);

        if (true) {
            $this->_slave->set($key, $value, $options);
        }
    }

    public function add($key, $value, $options = array())
    {
        $result = $this->_master->add($key, $value, $options);

        if ($result) {
            $this->_slave->set($key, $value, $options);
        }
    }

    public function delete($key)
    {
        $this->_master->delete($key);

        $this->_slave->delete($key);
    }

    public function increment($key, $offset)
    {
        $this->_master->increment($key, $offset);

        if (true) {
            $this->_slave->_set($key, $this->_master->get($key));
        }
    }

    public function decrement($key, $offset)
    {
        $this->_master->decrement($key, $offset);

        if (true) {
            $this->_slave->set($key, $this->_master->get($key));
        }
    }

    public function replace($key, $value, $options = array())
    {
        $this->_master->replace($key, $value, $options);

        if (true) {
            $this->_slave->set($key, $value);
        }
    }

    public function remove()
    {

    }

    public function clear()
    {
        
    }

    public function getMasterObject()
    {
        return $this->_master;
    }

    public function getSlaveObject()
    {
        return $this->_slave;
    }
}