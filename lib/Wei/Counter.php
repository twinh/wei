<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A counter widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Cache $cache A cache service
 */
class Counter extends Base
{
    /**
     * Increment an item
     *
     * @param string $key The name of item
     * @param int $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function incr($key, $offset = 1)
    {
        return $this->cache->incr($key, $offset);
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function decr($key, $offset = 1)
    {
        return $this->cache->decr($key, $offset);
    }

    /**
     * Retrieve an item
     *
     * @param string $key The name of item
     * @return mixed
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * Store an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->cache->set($key, $value);
    }
}