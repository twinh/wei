<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Storage;

/**
 * The base storage interface
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface StorageInterface
{
    /**
     * Get or store an item
     *
     * @param  string      $key    The name of item
     * @param  mixed       $value  The value of item
     * @param  int         $expire The expire seconds, 0 means never expired
     * @return mixed
     */
    public function __invoke($key, $value = null, $expire = 0);

    /**
     * Get an item
     *
     * @param  string      $key The name of item
     * @param  bool        $success
     * @return mixed
     */
    public function get($key, &$success = null);

    /**
     * Store an item
     *
     * @param  string $key    The name of item
     * @param  value  $value  The value of item
     * @param  int    $expire The expire seconds, 0 means never expired
     * @param  array  $options
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array());

    /**
     * Remove an item
     *
     * @param  string $key The name of item
     * @return bool
     */
    public function remove($key);

    /**
     * Add an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, 0 means never expired
     * @param  array  $options
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array());

    /**
     * Replace an existing item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, 0 means never expired
     * @param  array  $options
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array());

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
