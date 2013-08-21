<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A simple implementation of Cache\CacheInterface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class BaseCache extends Base
{
    /**
     * The string prepend to the cache key
     *
     * @var string
     */
    protected $keyPrefix = '';

    /**
     * Retrieve or store an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return mixed
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
     * @throws \RuntimeException When set cache return false
     * @return mixed
     */
    public function get($key, $expire = null, $fn = null)
    {
        $key = $this->getKeyWithPrefix($key);
        $result = $this->doGet($key);

        if (false === $result && null !== $expire) {
            if (is_callable($expire)) {
                $fn = $expire;
                $expire = 0;
            }
            $result = call_user_func($fn, $this->widget);

            $setResult = $this->doSet($key, $result, $expire);
            if (false === $setResult) {
                throw new \RuntimeException('Fail to store cache from callback');
            }
        }

        return $result;
    }

    /**
     * Store an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->doSet($this->getKeyWithPrefix($key), $value, $expire);
    }

    /**
     * Remove an item
     *
     * @param  string $key The name of item
     * @return bool
     */
    public function remove($key)
    {
        return $this->doRemove($this->getKeyWithPrefix($key));
    }

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->doExists($this->getKeyWithPrefix($key));
    }

    /**
     * Add an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function add($key, $value, $expire = 0)
    {
        return $this->doAdd($this->getKeyWithPrefix($key), $value, $expire);
    }

    /**
     * Replace an existing item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    public function replace($key, $value, $expire = 0)
    {
        return $this->doReplace($this->getKeyWithPrefix($key), $value, $expire);
    }

    /**
     * Increment an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function incr($key, $offset = 1)
    {
        return $this->doIncr($this->getKeyWithPrefix($key), $offset);
    }

    /**
     * Decrement an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     */
    public function decr($key, $offset = 1)
    {
        return $this->incr($key, -$offset);
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
     * Get file content with cache

     * @param string $file The path of file
     * @param callback $fn The callback to get and parse file content
     * @return mixed
     */
    public function getFileContent($file, $fn)
    {
        $modifiedTimeKey = $file . '-modifiedTime';
        $contentKey      = $file . '-content';
        $modifiedTime    = filemtime($file);

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
     * Returns the key prefix
     *
     * @return string
     */
    public function getKeyPrefix()
    {
        return $this->keyPrefix;
    }

    /**
     * Set the cache key prefix
     *
     * @param string $keyPrefix
     * @return $this
     */
    public function setKeyPrefix($keyPrefix)
    {
        $this->keyPrefix = $keyPrefix;
        return $this;
    }

    /**
     * Returns the cache key with key prefix
     *
     * @param string $key
     * @return string
     */
    protected function getKeyWithPrefix($key)
    {
        return $this->keyPrefix . $key;
    }

    /**
     * Retrieve an item
     *
     * @param  string      $key The name of item
     * @return mixed
     */
    abstract protected function doGet($key);

    /**
     * Store an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract protected function doSet($key, $value, $expire = 0);

    /**
     * Remove an item
     *
     * @param  string $key The name of item
     * @return bool
     */
    abstract protected function doRemove($key);

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     */
    abstract protected function doExists($key);

    /**
     * Add an item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract protected function doAdd($key, $value, $expire = 0);

    /**
     * Replace an existing item
     *
     * @param  string $key    The name of item
     * @param  mixed  $value  The value of item
     * @param  int    $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     */
    abstract protected function doReplace($key, $value, $expire = 0);

    /**
     * Increment an item
     *
     * @param  string    $key    The name of item
     * @param  int       $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     */
    abstract protected function doIncr($key, $offset = 1);

    /**
     * Clear all items
     *
     * @return boolean
     */
    abstract public function clear();
}
