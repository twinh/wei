<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Storable
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Storable
{
    public function get($key, $options = null);

    public function set($key, $value, $expire = 0, array $options = array());

    public function remove($key);

    public function add($key, $value, $expire = 0, array $options = array());

    public function replace($key, $value, $expire = 0, array $options = array());

    public function increment($key, $step = 1);

    public function decrement($key, $step = 1);

    public function clear();
}
