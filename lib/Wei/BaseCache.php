<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

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
    protected $prefix = '';

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
     * $cache = wei()->cache;
     *
     * $cache->get('key'); // => false
     *
     * $cache->get('key', function($wei){
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
    abstract public function get($key, $expire = null, $fn = null);

    protected function processGetResult($key, $result, $expire, $fn)
    {
        if (false === $result && null !== $expire) {
            if (is_callable($expire)) {
                $fn = $expire;
                $expire = 0;
            }
            $result = call_user_func($fn, $this->wei);

            $setResult = $this->set($key, $result, $expire);
            if (false === $setResult) {
                throw new \RuntimeException('Fail to store cache from callback', 1020);
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
    abstract public function incr($key, $offset = 1);

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
            $content = call_user_func($fn, $file, $this->wei);
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
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the cache key prefix
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Clear all items
     *
     * @return boolean
     */
    abstract public function clear();
}
