<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The base class for cache services
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
    protected $namespace = '';

    /**
     * @var array<string, bool>
     */
    protected $hits = [];

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
     * Retrieve multiple items
     *
     * @param array $keys The name of items
     * @return array
     * @deprecated use getMultiple instead
     */
    public function getMulti(array $keys)
    {
        return (array) $this->getMultiple($keys);
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int $expire
     * @return array
     * @deprecated use setMultiple instead
     */
    public function setMulti(array $keys, $expire = 0)
    {
        $results = [];
        foreach ($keys as $key => $value) {
            $results[$key] = $this->set($key, $value, $expire);
        }
        return $results;
    }

    /**
     * Use the file modify time as cache key to store an item from a callback
     *
     * @param string $file The path of file
     * @param callable $fn The callback to get and parse file content
     * @return mixed
     */
    public function getFileContent($file, $fn)
    {
        $key = $file . filemtime($file);
        return $this->remember($key, function ($cache) use ($file, $fn) {
            return $fn($file, $cache);
        });
    }

    /**
     * Returns the key prefix
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the cache key prefix
     *
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     * @deprecated use has instead
     */
    public function exists($key)
    {
        return $this->has($key);
    }

    /**
     * Remove an item
     *
     * @param string $key The name of item
     * @return bool
     * @deprecated use delete instead
     */
    public function remove($key)
    {
        return $this->delete($key);
    }

    /**
     * Retrieve an item
     *
     * ```php
     * $cache = wei()->cache;
     *
     * // Retrieve cache by key
     * $cache->get('key');
     *
     * // Custom default value
     * $cache->get('key', 'default value');
     *
     * @param string $key The name of item
     * @param mixed $default The default value to return when cache not exists
     * @return mixed
     * @svc
     */
    protected function get($key, $default = null)
    {
        [$value, $isHit] = $this->doGet($key);
        $this->hits = [$key => $isHit];
        return $isHit ? $value : $this->getDefault($default);
    }

    /**
     * Store an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     * @svc
     */
    abstract protected function set($key, $value, $expire = 0);

    /**
     * Remove an item
     *
     * @param string $key The name of item
     * @return bool
     * @svc
     */
    abstract protected function delete(string $key): bool;

    /**
     * Clear all items
     *
     * @return bool
     * @svc
     */
    abstract protected function clear();

    /**
     * Check if an item is exists
     *
     * @param string $key
     * @return bool
     * @svc
     */
    abstract protected function has(string $key): bool;

    /**
     * Add an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     * @svc
     */
    abstract protected function add($key, $value, $expire = 0);

    /**
     * Replace an existing item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return bool
     * @svc
     */
    abstract protected function replace($key, $value, $expire = 0);

    /**
     * Increment an item
     *
     * @param string $key The name of item
     * @param int $offset The value to increased
     * @return int|false Returns the new value on success, or false on failure
     * @svc
     */
    abstract protected function incr($key, $offset = 1);

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @svc
     */
    protected function decr($key, $offset = 1)
    {
        return $this->incr($key, -$offset);
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @svc
     */
    protected function getMultiple(iterable $keys, $default = null): iterable
    {
        $hits = [];
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
            // $this->hits will be reset after get
            $hits[$key] = $this->hits[$key];
        }
        $this->hits = $hits;
        return $results;
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @svc
     */
    protected function setMultiple(iterable $keys, $ttl = null): bool
    {
        $success = true;
        foreach ($keys as $key => $value) {
            $success = $this->set($key, $value, $ttl ?: 0) && $success;
        }
        return $success;
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @svc
     */
    protected function remember(string $key, $expireOrFn, callable $fn = null)
    {
        $value = $this->get($key);
        if ($this->isHit()) {
            return $value;
        }

        // Avoid using null as expire second, for null will be convert to 0
        // which means that store the cache forever, and make it hart to debug.
        if (!is_int($expireOrFn) && $fn) {
            throw new \InvalidArgumentException(sprintf(
                'Expire time for cache "%s" must be int, %s given',
                $key,
                is_object($expireOrFn) ? get_class($expireOrFn) : gettype($expireOrFn)
            ));
        }

        // Example: remember($key, function(){});
        if ($expireOrFn && !$fn) {
            $fn = $expireOrFn;
            $expire = 0;
        } else {
            $expire = $expireOrFn;
        }

        $result = call_user_func($fn, $this);
        $this->set($key, $result, $expire);

        return $result;
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @svc
     */
    protected function isHit(string $key = null): bool
    {
        if (null !== $key) {
            return $this->hits[$key] ?? false;
        }
        return end($this->hits);
    }

    /**
     * @param string $key
     * @return array
     */
    protected function doGet(string $key): array
    {
        return [false, null];
    }

    /**
     * Call and return if the argument is a closure, otherwise return the argument directly
     *
     * @param mixed $default
     * @return mixed
     */
    protected function getDefault($default)
    {
        return $default instanceof \Closure ? $default() : $default;
    }
}
