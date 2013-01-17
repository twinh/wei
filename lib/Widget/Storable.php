<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Storable
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Storable
{
    /**
     * Get or set cache
     *
     * @param  string      $key    The name of cache
     * @param  mixed       $value  The value of cache
     * @param  int         $expire The expire time for set cache
     * @return mixed
     */
    public function __invoke($key, $value = null, $expire = 0);

    /**
     * Get cache
     *
     * @param  string      $key The name of cache
     * @return mixed|false
     */
    public function get($key, $options = null);

    /**
     * Set cache
     *
     * @param  string $key    The name of cache
     * @param  value  $value  The value of cache
     * @param  int    $expire The expire time, 0 means never expired
     * @param array $options
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array());

    /**
     * Remove cache by key
     *
     * @param  string $key the name of cache
     * @return bool
     */
    public function remove($key);

    /**
     * Add cache, if cache is exists, return false
     *
     * @param  string $key    The name of cache
     * @param  mixed  $value  The value of cache
     * @param  int    $expire The expire time (seconds)
     * @param array $options
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array());

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param  string $key    The name of cache
     * @param  mixed  $value  The value of cache
     * @param  int    $expire The expire time
     * @param array $options
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array());

    /**
     * Increase a numerical cache
     *
     * @param  string    $key    The name of cache
     * @param  int       $offset The value to decrease
     * @return int|false
     */
    public function increment($key, $step = 1);

    /**
     * Decrease a numerical cache
     *
     * @param  string    $key    The name of cache
     * @param  int       $offset The value to decrease
     * @return int|false
     */
    public function decrement($key, $step = 1);

    /**
     * Clear all cache
     *
     * @return boolean
     */
    public function clear();
}
