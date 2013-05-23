<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

/**
 * The base cache interface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
interface CacheInterface
{
    /**
     * Retrieve or store an item
     *
     * @param  string      $key    The name of item
     * @param  mixed       $value  The value of item
     * @param  int         $expire The expire seconds, 0 means never expired
     * @return mixed
     */
    public function __invoke($key, $value = null, $expire = 0);

    /**
     * Retrieve an item
     *
     * @param  string      $key The name of item
     * @return mixed
     */
    public function get($key);

    /**
     * Retrieve multiple items
     *
     * @param array $keys The name of items
     */
    public function getMulti(array $keys);

    /**
     * Store an item
     *
     * @param  string $key    The name of item
     * @param  value  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0);

    /**
     * Store multiple items
     *
     * @param array $items An array of key/value pairs to store
     * @param int $expire The expire seconds, defaults to 0, means never expired
     */
    public function setMulti(array $items, $expire = 0);

    /**
     * Remove an item
     *
     * @param  string $key The name of item
     * @return bool
     */
    public function remove($key);

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     */
    public function exists($key);

    /**
     * Add an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function add($key, $value, $expire = 0);

    /**
     * Replace an existing item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function replace($key, $value, $expire = 0);

    /**
     * Increment an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function increment($key, $offset = 1);

    /**
     * Decrement an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function decrement($key, $offset = 1);

    /**
     * Clear all items
     *
     * @return boolean
     */
    public function clear();
}
