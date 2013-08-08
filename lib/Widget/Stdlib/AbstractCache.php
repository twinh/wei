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
        foreach ($items as $key => $value) {
            $results[$key] = $this->set($key, $value, $expire);
        }
        return $results;
    }

    /**
     * Retrieve an item
     *
     * @param  string      $key The name of item
     * @return mixed
     */
    abstract protected function doGet($key);

    /**
     * Retrieve an item
     *
     * ```php
     * $cache = widget()->cache;
     *
     * $cache->get('key'); // => false
     *
     * $cache->get('key', function($widget){
    *      return 'value';
     * }); // => "value"
     *
     * $cache->get('key'); // => "value"
     * ```
     *
     * @param string $key The name of item
     * @param int $expire The expire seconds of callback return value
     * @param callback $fn The callback to execute when cache not found
     * @return mixed
     */
    public function get($key, $expire = null, $fn = null)
    {
        $result = $this->doGet($key);

        if (false === $result && null !== $expire) {
            if (is_callable($expire)) {
                $fn = $expire;
                $expire = 0;
            }
            $result = call_user_func($fn, $this->widget);
            $this->set($key, $result, $expire);
        }

        return $result;
    }

    /**
     * Get file content with cache

     * @param string $file The path of file
     * @param callback $fn The callback to get and parse file content
     * @return mixed
     */
    public function getFileContent($file, $fn)
    {
        $modifiedTimeKey = $file . '-modifiedTime';
        $contentKey      = $file . '-content';
        $modifiedTime = filemtime($file);

        if ($modifiedTime > $this->get($modifiedTimeKey)) {
            $content = call_user_func($fn, $file, $this->widget);
            $this->setMulti(array(
                $modifiedTimeKey => $modifiedTime,
                $contentKey      => $content,
            ));
        }  else {
            $content = $this->get($contentKey);
        }
        return $content;
    }

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
    abstract public function inc($key, $offset = 1);

    /**
     * Decrement an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function dec($key, $offset = 1)
    {
        return $this->inc($key, -$offset);
    }

    /**
     * Clear all items
     *
     * @return boolean
     */
    abstract public function clear();
}
