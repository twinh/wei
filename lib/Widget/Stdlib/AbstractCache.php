<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\AbstractWidget;

/**
 * A simple implementation of Cache\CacheInterface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractCache extends AbstractWidget
{
    /**
     * {@inheritdoc}
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
     * Retrieve multiple items
     *
     * @param array $keys The name of items
     * @return array
     */
    public function getMulti(array $keys)
    {
        $results = array();
        foreach ($keys as $key) {
            $results[$key] = $this->get($key);
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function setMulti(array $items, $expire = 0)
    {
        $results = array();
        foreach ($items as $key => $value)
        {
            $results[$key] = $this->set($key, $value, $expire);
        }
        return $results;
    }

    /**
     * Store callback result in cache with specified times
     *
     * @param callback $fn
     * @param int $expire
     * @param string $key
     * @return mixed
     */
    public function cached($fn, $expire, $key = null)
    {
        if ($key === null) {
            $key = md5(new \ReflectionFunction($fn));
        }

        if (false === $result = $this->get($key)) {
            $result = call_user_func($fn);
            $this->set($key, $result, $expire);
        }

        return $result;
    }

    /**
     * Retrieve an item
     *
     * @param  string      $key The name of item
     * @return mixed
     */
    abstract public function get($key);

    /**
     * Store an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract public function set($key, $value, $expire = 0);

    /**
     * Remove an item
     *
     * @param  string $key The name of item
     * @return bool
     */
    abstract public function remove($key);

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     */
    abstract public function exists($key);

    /**
     * Add an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract public function add($key, $value, $expire = 0);

    /**
     * Replace an existing item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract public function replace($key, $value, $expire = 0);

    /**
     * Increment an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     */
    abstract public function increment($key, $offset = 1);

    /**
     * Decrement an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * Clear all items
     *
     * @return boolean
     */
    abstract public function clear();
}
