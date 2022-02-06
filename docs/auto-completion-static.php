<?php

namespace Wei;

class Apc
{
    /**
     * {@inheritdoc}
     * @see Apc::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apc::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class App
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class ArrayCache
{
    /**
     * {@inheritdoc}
     * @see ArrayCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see ArrayCache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Asset
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Bicache
{
    /**
     * {@inheritdoc}
     * @see Bicache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Bicache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Block
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Cache
{
    /**
     * {@inheritdoc}
     * @see Cache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::clear
     */
    public static function clear()
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Cache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class ClassMap
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Config
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Cookie
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Counter
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Db
{
    /**
     * Execute a function in a transaction
     *
     * @param callable $fn
     * @throws \Exception
     * @see Db::transactional
     */
    public static function transactional(callable $fn)
    {
    }

    /**
     * Create a raw value instance
     *
     * @param mixed $value
     * @return Raw
     * @see Db::raw
     */
    public static function raw($value): Db\Raw
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class DbCache
{
    /**
     * {@inheritdoc}
     * @see DbCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see DbCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see DbCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see DbCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see DbCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     * @see DbCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see DbCache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class E
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Env
{
    /**
     * Check if in specified environment
     *
     * @param string $env
     * @return bool
     * @see Env::is
     */
    public static function is($env)
    {
    }

    /**
     * Check if in the development environment
     *
     * @return bool
     * @see Env::isDev
     */
    public static function isDev()
    {
    }

    /**
     * Check if is the test environment
     *
     * @return bool
     * @see Env::isTest
     */
    public static function isTest()
    {
    }

    /**
     * Check if in the production environment
     *
     * @return bool
     * @see Env::isProd
     */
    public static function isProd()
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Error
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Event
{
    /**
     * Trigger an event
     *
     * @param  string $name The name of event
     * @param  array $args The arguments pass to the handle
     * @param bool $halt
     * @return array|mixed
     * @see Event::trigger
     */
    public static function trigger($name, $args = [], $halt = false)
    {
    }

    /**
     * Trigger an event until the first non-null response is returned
     *
     * @param string $name
     * @param array $args
     * @return mixed
     * @link https://github.com/laravel/framework/blob/5.1/src/Illuminate/Events/Dispatcher.php#L161
     * @see Event::until
     */
    public static function until($name, $args = [])
    {
    }

    /**
     * Attach a handler to an event
     *
     * @param string|array $name The name of event, or an associative array contains event name and event handler pairs
     * @param callable $fn The event handler
     * @param int $priority The event priority
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     * @see Event::on
     */
    public static function on($name, $fn = null, $priority = 0)
    {
    }

    /**
     * Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     * @see Event::off
     */
    public static function off($name)
    {
    }

    /**
     * Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     * @see Event::has
     */
    public static function has($name)
    {
    }

    /**
     * Returns the name of last triggered event
     *
     * @return string
     * @see Event::getCurName
     */
    public static function getCurName()
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class FileCache
{
    /**
     * {@inheritdoc}
     * @see FileCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Gravatar
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Http
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAll
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAllOf
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAllowEmpty
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAlnum
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAlpha
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsAnyDateTime
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsArray
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsBetween
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsBigInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsBlank
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsBool
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsBoolable
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsCallback
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsChar
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsChildren
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsChinese
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsColor
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsContains
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsCreditCard
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDate
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDateTime
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDecimal
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDefaultInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDigit
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDir
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDivisibleBy
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsDoubleByte
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsEach
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsEmail
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsEmpty
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsEndsWith
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsEqualTo
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsExists
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsFieldExists
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsFile
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsFloat
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsGreaterThan
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsGreaterThanOrEqual
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsGt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsGte
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIdCardCn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIdCardHk
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIdCardMo
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIdCardTw
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIdenticalTo
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsImage
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsIp
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLength
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLessThan
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLessThanOrEqual
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLowercase
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLte
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsLuhn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMaxAccuracy
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMaxCharLength
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMaxLength
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMediumInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMediumText
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMinCharLength
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMinLength
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsMobileCn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsNaturalNumber
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsNoneOf
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsNullType
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsNumber
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsObject
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsOneOf
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPassword
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPhone
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPhoneCn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPlateNumberCn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPositiveInteger
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPostcodeCn
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsPresent
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsQQ
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsRecordExists
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsRegex
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsRequired
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsSmallInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsSomeOf
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsStartsWith
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsString
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsText
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsTime
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsTinyChar
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsTinyInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsTld
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsType
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUBigInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUDefaultInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUMediumInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUNumber
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUSmallInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUTinyInt
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUppercase
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUrl
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class IsUuid
{
    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Lock
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Logger
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Memcache
{
    /**
     * {@inheritdoc}
     * @see Memcache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Memcached
{
    /**
     * {@inheritdoc}
     * @see Memcached::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::clear
     */
    public static function clear()
    {
    }

    /**
     * {@inheritdoc}
     * @see Memcached::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Migration
{
    /**
     * @param OutputInterface $output
     * @return $this
     * @see Migration::setOutput
     */
    public static function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
    }

    /**
     * @see Migration::migrate
     */
    public static function migrate()
    {
    }

    /**
     * Rollback the last migration or to the specified target migration ID
     *
     * @param array $options
     * @see Migration::rollback
     */
    public static function rollback($options = [])
    {
    }

    /**
     * Rollback all migrations
     *
     * @see Migration::reset
     */
    public static function reset()
    {
    }

    /**
     * @param array $options
     * @throws \ReflectionException
     * @throws \Exception
     * @see Migration::create
     */
    public static function create($options)
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class MongoCache
{
    /**
     * {@inheritdoc}
     * @see MongoCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see MongoCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see MongoCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     * @see MongoCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see MongoCache::clear
     */
    public static function clear()
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class NearCache
{
    /**
     * First write data to front cache (eg local cache), then write to back cache (eg memcache)
     *
     * {@inheritdoc}
     * @see NearCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see NearCache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class NullCache
{
    /**
     * {@inheritdoc}
     * @see NullCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::clear
     */
    public static function clear()
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see NullCache::incr
     */
    public static function incr($key, $offset = 1)
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Password
{
    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @return string|false the hashed password, or false on error
     * @throws \InvalidArgumentException
     * @see Password::hash
     */
    public static function hash($password)
    {
    }

    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => 10,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     * @return array the array of information about the hash
     * @see Password::getInfo
     */
    public static function getInfo($hash)
    {
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash The hash to test
     * @return bool true if the password needs to be rehashed
     * @see Password::needsRehash
     */
    public static function needsRehash($hash)
    {
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash The hash to verify against
     * @return bool If the password matches the hash
     * @see Password::verify
     */
    public static function verify($password, $hash)
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class PhpError
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class PhpFileCache
{
    /**
     * {@inheritdoc}
     * @see FileCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see FileCache::clear
     */
    public static function clear()
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Retrieve multiple items
     *
     * @param iterable $keys The name of items
     * @param mixed $default
     * @return iterable<string, mixed>
     * @see BaseCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * Store multiple items
     *
     * @param array $keys The name of items
     * @param int|null $ttl
     * @return bool
     * @see BaseCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Pinyin
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Record
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Redis
{
    /**
     * {@inheritdoc}
     * @see Redis::doGet
     */
    public static function doGet(string $key): array
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     * @see Redis::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::clear
     */
    public static function clear()
    {
    }

    /**
     * {@inheritdoc}
     * @see Redis::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * {@inheritdoc}
     *
     * Note: The "$ttl" parameter is not support by redis MSET command
     *
     * @link https://stackoverflow.com/questions/16423342/redis-multi-set-with-a-ttl
     * @see Redis::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
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
     * @see BaseCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Check if the cache is exists
     *
     * @param string|null $key
     * @return bool
     * @see BaseCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Req
{
    /**
     * Check if the specified header is set
     *
     * @param string $name
     * @return bool
     * @see Req::hasHeader
     */
    public static function hasHeader(string $name): bool
    {
    }

    /**
     * Return the specified header value
     *
     * @param string $name
     * @return string|null
     * @see Req::getHeader
     */
    public static function getHeader(string $name): ?string
    {
    }

    /**
     * Check if current request is a preflight request
     *
     * @return bool
     * @link https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request
     * @see Req::isPreflight
     */
    public static function isPreflight(): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Request
{
    /**
     * Check if the specified header is set
     *
     * @param string $name
     * @return bool
     * @see Req::hasHeader
     */
    public static function hasHeader(string $name): bool
    {
    }

    /**
     * Return the specified header value
     *
     * @param string $name
     * @return string|null
     * @see Req::getHeader
     */
    public static function getHeader(string $name): ?string
    {
    }

    /**
     * Check if current request is a preflight request
     *
     * @return bool
     * @link https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request
     * @see Req::isPreflight
     */
    public static function isPreflight(): bool
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Res
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Response
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Ret
{
    /**
     * Return operation successful result
     *
     * ```php
     * // Specified message
     * $this->suc('Payment successful');
     *
     * // Format
     * $this->suc(['me%sage', 'ss']);
     *
     * // More data
     * $this->suc(['message' => 'Read successful', 'page' => 1, 'rows' => 123]);
     * ```
     *
     * @param array|string|null $message
     * @return $this
     * @see Ret::suc
     */
    public static function suc($message = null)
    {
    }

    /**
     * Return operation failed result, and logs with an info level
     *
     * @param array|string $message
     * @param int|null $code
     * @param string $level The log level, default to "info"
     * @return $this
     * @see Ret::err
     */
    public static function err($message, $code = null, $level = null)
    {
    }

    /**
     * Return operation failed result, and logs with a warning level
     *
     * @param string $message
     * @param int $code
     * @return $this
     * @see Ret::warning
     */
    public static function warning($message, $code = null)
    {
    }

    /**
     * Return operation failed result, and logs with an alert level
     *
     * @param string $message
     * @param int $code
     * @return $this
     * @see Ret::alert
     */
    public static function alert($message, $code = null)
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Router
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class SafeUrl
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Schema
{
    /**
     * Check if database exists
     *
     * @param string $database
     * @return bool
     * @see Schema::hasDatabase
     */
    public static function hasDatabase(string $database): bool
    {
    }

    /**
     * Create a database
     *
     * @param string $database
     * @return $this
     * @see Schema::createDatabase
     */
    public static function createDatabase(string $database): self
    {
    }

    /**
     * Drop a database
     *
     * @param string $database
     * @return $this
     * @see Schema::dropDatabase
     */
    public static function dropDatabase(string $database): self
    {
    }

    /**
     * Set user id type
     *
     * @param string $userIdType
     * @return $this
     * @see Schema::setUserIdType
     */
    public static function setUserIdType(string $userIdType): self
    {
    }

    /**
     * Get user id type
     *
     * @return string
     * @see Schema::getUserIdType
     */
    public static function getUserIdType(): string
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Session
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Share
{
    /**
     * @param string $title
     * @return $this
     * @see Share::setTitle
     */
    public static function setTitle($title)
    {
    }

    /**
     * @return string
     * @see Share::getTitle
     */
    public static function getTitle()
    {
    }

    /**
     * @param string $image
     * @return $this
     * @see Share::setImage
     */
    public static function setImage($image)
    {
    }

    /**
     * @return string
     * @see Share::getImage
     */
    public static function getImage()
    {
    }

    /**
     * @param string $description
     * @return Share
     * @see Share::setDescription
     */
    public static function setDescription($description)
    {
    }

    /**
     * @return string
     * @see Share::getDescription
     */
    public static function getDescription()
    {
    }

    /**
     * @param string $url
     * @return Share
     * @see Share::setUrl
     */
    public static function setUrl($url)
    {
    }

    /**
     * @return string
     * @see Share::getUrl
     */
    public static function getUrl()
    {
    }

    /**
     * Returns share data as JSON
     *
     * @return string
     * @see Share::toJson
     */
    public static function toJson()
    {
    }

    /**
     * Returns share data as JSON for WeChat
     *
     * @return string
     * @see Share::toWechatJson
     */
    public static function toWechatJson()
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Soap
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class StatsD
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class T
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class TagCache
{
    /**
     * {@inheritdoc}
     * @see TagCache::get
     */
    public static function get($key, $default = null)
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::getMultiple
     */
    public static function getMultiple(iterable $keys, $default = null): iterable
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::setMultiple
     */
    public static function setMultiple(iterable $keys, $ttl = null): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::clear
     */
    public static function clear()
    {
    }

    /**
     * {@inheritdoc}
     * @see TagCache::isHit
     */
    public static function isHit(string $key = null): bool
    {
    }

    /**
     * Decrement an item
     *
     * @param string $key The name of item
     * @param int $offset The value to be decreased
     * @return int|false Returns the new value on success, or false on failure
     * @see BaseCache::decr
     */
    public static function decr($key, $offset = 1)
    {
    }

    /**
     * Store data from callback to cache
     *
     * @param string $key
     * @param int|callable $expireOrFn
     * @param callable|null $fn
     * @return false|mixed
     * @see BaseCache::remember
     */
    public static function remember(string $key, $expireOrFn, callable $fn = null)
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Time
{
    /**
     * @return string
     * @see Time::now
     */
    public static function now()
    {
    }

    /**
     * @return string
     * @see Time::today
     */
    public static function today()
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Ua
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Upload
{
    /**
     * Upload a file, return a Ret object
     *
     * @param array $options
     * @return Ret
     * @see Upload::save
     */
    public static function save(array $options = []): Ret
    {
    }

    /**
     * Check the input value, return a Ret object
     *
     * @param mixed $input
     * @param string $name
     * @return Ret
     * @see BaseValidator::check
     */
    public static function check($input, string $name = '%name%'): Ret
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Url
{
    /**
     * Generate the URL by specified URL and parameters
     *
     * @param string $url
     * @param array $argsOrParams
     * @param array $params
     * @return string
     * @see Url::to
     */
    public static function to($url = '', $argsOrParams = [], $params = [])
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Uuid
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class V
{
    /**
     * Add name for current field
     *
     * @param string $label
     * @return $this
     * @see V::label
     */
    public static function label($label)
    {
    }

    /**
     * Add a new field
     *
     * @param string|array $name
     * @param string|null $label
     * @return $this
     * @see V::key
     */
    public static function key($name, $label = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::when
     */
    public static function when($value, $callback, callable $default = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::unless
     */
    public static function unless($value, callable $callback, callable $default = null)
    {
    }

    /**
     * @return $this
     * @see V::defaultOptional
     */
    public static function defaultOptional()
    {
    }

    /**
     * @return $this
     * @see V::defaultRequired
     */
    public static function defaultRequired()
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function all(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function notAll(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function allOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function notAllOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllowEmpty::__invoke
     */
    public static function allowEmpty()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllowEmpty::__invoke
     */
    public static function notAllowEmpty()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function alnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function notAlnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function alpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function notAlpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAnyDateTime::__invoke
     */
    public static function anyDateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAnyDateTime::__invoke
     */
    public static function notAnyDateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function array($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function notArray($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function between($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function notBetween($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function bigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function notBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function blank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function notBlank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function bool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function notBool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function boolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function notBoolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function callback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function notCallback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function char($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function notChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function children(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function notChildren(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function chinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function notChinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function color($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function notColor($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function contains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function notContains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function creditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function notCreditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function date($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function notDate($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function dateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function notDateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function decimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function notDecimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function defaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function notDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function digit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function notDigit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function dir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function notDir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function divisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function notDivisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function doubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function notDoubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function each($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function notEach($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function email()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function notEmail()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmpty::__invoke
     */
    public static function empty()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmpty::__invoke
     */
    public static function notEmpty()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function endsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function notEndsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function equalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function notEqualTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function exists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function notExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function fieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function notFieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function file($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function notFile($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function float()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function notFloat()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function greaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function notGreaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function greaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function notGreaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function gt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function notGt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function gte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function notGte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function idCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function notIdCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function idCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function notIdCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function idCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function notIdCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function idCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function notIdCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function identicalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function notIdenticalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function image($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function notImage($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function in($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function notIn($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function int($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function notInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function ip($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function notIp($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function length($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function notLength($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function lessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function notLessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function lessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function notLessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function lowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function notLowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function lt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function notLt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function lte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function notLte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function luhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function notLuhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function maxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function notMaxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function maxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function notMaxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function maxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function notMaxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function mediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function notMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function mediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function notMediumText(
        $name = null,
        string $label = null,
        int $minLength = null,
        int $maxLength = null
    ) {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function minCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function notMinCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function minLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function notMinLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function mobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function notMobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function naturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function notNaturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function noneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function notNoneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function nullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function notNullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function number($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function notNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsObject::__invoke
     */
    public static function object($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsObject::__invoke
     */
    public static function notObject($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function oneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function notOneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function password(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function notPassword(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function phone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function notPhone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function phoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function notPhoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function plateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function notPlateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function positiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function notPositiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function postcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function notPostcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function present()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function notPresent()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function qQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function notQQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function recordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function notRecordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function regex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function notRegex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function required($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function notRequired($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function smallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function notSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function someOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function notSomeOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function startsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function notStartsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function string($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function notString($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function text($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function notText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function time($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function notTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function tinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function notTinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function tinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function notTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function tld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function notTld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function type($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function notType($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function uBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function notUBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function uDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function notUDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function uMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function notUMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function uNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function notUNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function uSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function notUSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function uTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function notUTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function uppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function notUppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function url($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function notUrl($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function uuid($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function notUuid($pattern = null)
    {
    }
}

class Validate
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class View
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class WeChatApp
{
    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

class Wei
{
    /**
     * Set service's configuration
     *
     * @param string|array $name
     * @param mixed $value
     * @return $this
     * @see Wei::setConfig
     */
    public static function setConfig($name, $value = null)
    {
    }

    /**
     * Get services' configuration
     *
     * @param string $name The name of configuration
     * @param mixed $default The default value if configuration not found
     * @return mixed
     * @see Wei::getConfig
     */
    public static function getConfig($name = null, $default = null)
    {
    }

    /**
     * Remove services' configuration
     *
     * @param string $name
     * @return $this
     * @see Wei::removeConfig
     */
    public static function removeConfig(string $name): self
    {
    }

    /**
     * Get service by class name
     *
     * @template T
     * @param string|class-string<T> $class
     * @return Base|T
     * @see Wei::getBy
     */
    public static function getBy(string $class): Base
    {
    }

    /**
     * Return the current service object
     *
     * @return $this
     * @experimental
     * @see Base::instance
     */
    public static function instance(): self
    {
    }
}

namespace Wei;

if (0) {
    class Apc
    {
        /**
         * {@inheritdoc}
         * @see Apc::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apc::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class App
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class ArrayCache
    {
        /**
         * {@inheritdoc}
         * @see ArrayCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see ArrayCache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Asset
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Bicache
    {
        /**
         * {@inheritdoc}
         * @see Bicache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Bicache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Block
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Cache
    {
        /**
         * {@inheritdoc}
         * @see Cache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::clear
         */
        public function clear()
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Cache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class ClassMap
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Config
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Cookie
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Counter
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Db
    {
        /**
         * Execute a function in a transaction
         *
         * @param callable $fn
         * @throws \Exception
         * @see Db::transactional
         */
        public function transactional(callable $fn)
        {
        }

        /**
         * Create a raw value instance
         *
         * @param mixed $value
         * @return Raw
         * @see Db::raw
         */
        public function raw($value): Db\Raw
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class DbCache
    {
        /**
         * {@inheritdoc}
         * @see DbCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see DbCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see DbCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see DbCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see DbCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * Note: This method is not an atomic operation
         *
         * {@inheritdoc}
         * @see DbCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see DbCache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class E
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Env
    {
        /**
         * Check if in specified environment
         *
         * @param string $env
         * @return bool
         * @see Env::is
         */
        public function is($env)
        {
        }

        /**
         * Check if in the development environment
         *
         * @return bool
         * @see Env::isDev
         */
        public function isDev()
        {
        }

        /**
         * Check if is the test environment
         *
         * @return bool
         * @see Env::isTest
         */
        public function isTest()
        {
        }

        /**
         * Check if in the production environment
         *
         * @return bool
         * @see Env::isProd
         */
        public function isProd()
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Error
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Event
    {
        /**
         * Trigger an event
         *
         * @param  string $name The name of event
         * @param  array $args The arguments pass to the handle
         * @param bool $halt
         * @return array|mixed
         * @see Event::trigger
         */
        public function trigger($name, $args = [], $halt = false)
        {
        }

        /**
         * Trigger an event until the first non-null response is returned
         *
         * @param string $name
         * @param array $args
         * @return mixed
         * @link https://github.com/laravel/framework/blob/5.1/src/Illuminate/Events/Dispatcher.php#L161
         * @see Event::until
         */
        public function until($name, $args = [])
        {
        }

        /**
         * Attach a handler to an event
         *
         * @param string|array $name The name of event, or an associative array contains event name and event handler pairs
         * @param callable $fn The event handler
         * @param int $priority The event priority
         * @throws \InvalidArgumentException when the second argument is not callable
         * @return $this
         * @see Event::on
         */
        public function on($name, $fn = null, $priority = 0)
        {
        }

        /**
         * Remove event handlers by specified name
         *
         * @param string $name The name of event
         * @return $this
         * @see Event::off
         */
        public function off($name)
        {
        }

        /**
         * Check if has the given name of event handlers
         *
         * @param  string $name
         * @return bool
         * @see Event::has
         */
        public function has($name)
        {
        }

        /**
         * Returns the name of last triggered event
         *
         * @return string
         * @see Event::getCurName
         */
        public function getCurName()
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class FileCache
    {
        /**
         * {@inheritdoc}
         * @see FileCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Gravatar
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Http
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAll
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAllOf
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAllowEmpty
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAlnum
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAlpha
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsAnyDateTime
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsArray
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsBetween
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsBigInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsBlank
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsBool
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsBoolable
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsCallback
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsChar
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsChildren
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsChinese
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsColor
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsContains
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsCreditCard
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDate
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDateTime
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDecimal
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDefaultInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDigit
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDir
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDivisibleBy
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsDoubleByte
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsEach
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsEmail
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsEmpty
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsEndsWith
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsEqualTo
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsExists
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsFieldExists
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsFile
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsFloat
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsGreaterThan
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsGreaterThanOrEqual
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsGt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsGte
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIdCardCn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIdCardHk
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIdCardMo
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIdCardTw
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIdenticalTo
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsImage
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsIp
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLength
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLessThan
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLessThanOrEqual
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLowercase
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLte
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsLuhn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMaxAccuracy
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMaxCharLength
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMaxLength
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMediumInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMediumText
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMinCharLength
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMinLength
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsMobileCn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsNaturalNumber
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsNoneOf
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsNullType
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsNumber
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsObject
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsOneOf
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPassword
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPhone
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPhoneCn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPlateNumberCn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPositiveInteger
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPostcodeCn
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsPresent
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsQQ
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsRecordExists
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsRegex
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsRequired
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsSmallInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsSomeOf
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsStartsWith
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsString
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsText
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsTime
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsTinyChar
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsTinyInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsTld
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsType
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUBigInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUDefaultInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUMediumInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUNumber
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUSmallInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUTinyInt
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUppercase
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUrl
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class IsUuid
    {
        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Lock
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Logger
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Memcache
    {
        /**
         * {@inheritdoc}
         * @see Memcache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Memcached
    {
        /**
         * {@inheritdoc}
         * @see Memcached::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::clear
         */
        public function clear()
        {
        }

        /**
         * {@inheritdoc}
         * @see Memcached::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Migration
    {
        /**
         * @param OutputInterface $output
         * @return $this
         * @see Migration::setOutput
         */
        public function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
        {
        }

        /**
         * @see Migration::migrate
         */
        public function migrate()
        {
        }

        /**
         * Rollback the last migration or to the specified target migration ID
         *
         * @param array $options
         * @see Migration::rollback
         */
        public function rollback($options = [])
        {
        }

        /**
         * Rollback all migrations
         *
         * @see Migration::reset
         */
        public function reset()
        {
        }

        /**
         * @param array $options
         * @throws \ReflectionException
         * @throws \Exception
         * @see Migration::create
         */
        public function create($options)
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class MongoCache
    {
        /**
         * {@inheritdoc}
         * @see MongoCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see MongoCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see MongoCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * Note: This method is not an atomic operation
         *
         * {@inheritdoc}
         * @see MongoCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see MongoCache::clear
         */
        public function clear()
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class NearCache
    {
        /**
         * First write data to front cache (eg local cache), then write to back cache (eg memcache)
         *
         * {@inheritdoc}
         * @see NearCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see NearCache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class NullCache
    {
        /**
         * {@inheritdoc}
         * @see NullCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::clear
         */
        public function clear()
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see NullCache::incr
         */
        public function incr($key, $offset = 1)
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Password
    {
        /**
         * Hash the password using the specified algorithm
         *
         * @param string $password The password to hash
         * @return string|false the hashed password, or false on error
         * @throws \InvalidArgumentException
         * @see Password::hash
         */
        public function hash($password)
        {
        }

        /**
         * Get information about the password hash. Returns an array of the information
         * that was used to generate the password hash.
         *
         * array(
         *    'algo' => 1,
         *    'algoName' => 'bcrypt',
         *    'options' => array(
         *        'cost' => 10,
         *    ),
         * )
         *
         * @param string $hash The password hash to extract info from
         * @return array the array of information about the hash
         * @see Password::getInfo
         */
        public function getInfo($hash)
        {
        }

        /**
         * Determine if the password hash needs to be rehashed according to the options provided
         *
         * If the answer is true, after validating the password using password_verify, rehash it.
         *
         * @param string $hash The hash to test
         * @return bool true if the password needs to be rehashed
         * @see Password::needsRehash
         */
        public function needsRehash($hash)
        {
        }

        /**
         * Verify a password against a hash using a timing attack resistant approach
         *
         * @param string $password The password to verify
         * @param string $hash The hash to verify against
         * @return bool If the password matches the hash
         * @see Password::verify
         */
        public function verify($password, $hash)
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class PhpError
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class PhpFileCache
    {
        /**
         * {@inheritdoc}
         * @see FileCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see FileCache::clear
         */
        public function clear()
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Retrieve multiple items
         *
         * @param iterable $keys The name of items
         * @param mixed $default
         * @return iterable<string, mixed>
         * @see BaseCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * Store multiple items
         *
         * @param array $keys The name of items
         * @param int|null $ttl
         * @return bool
         * @see BaseCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Pinyin
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Record
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Redis
    {
        /**
         * {@inheritdoc}
         * @see Redis::doGet
         */
        public function doGet(string $key): array
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * Note: This method is not an atomic operation
         *
         * {@inheritdoc}
         * @see Redis::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::clear
         */
        public function clear()
        {
        }

        /**
         * {@inheritdoc}
         * @see Redis::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * {@inheritdoc}
         *
         * Note: The "$ttl" parameter is not support by redis MSET command
         *
         * @link https://stackoverflow.com/questions/16423342/redis-multi-set-with-a-ttl
         * @see Redis::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
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
         * @see BaseCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Check if the cache is exists
         *
         * @param string|null $key
         * @return bool
         * @see BaseCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Req
    {
        /**
         * Check if the specified header is set
         *
         * @param string $name
         * @return bool
         * @see Req::hasHeader
         */
        public function hasHeader(string $name): bool
        {
        }

        /**
         * Return the specified header value
         *
         * @param string $name
         * @return string|null
         * @see Req::getHeader
         */
        public function getHeader(string $name): ?string
        {
        }

        /**
         * Check if current request is a preflight request
         *
         * @return bool
         * @link https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request
         * @see Req::isPreflight
         */
        public function isPreflight(): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Request
    {
        /**
         * Check if the specified header is set
         *
         * @param string $name
         * @return bool
         * @see Req::hasHeader
         */
        public function hasHeader(string $name): bool
        {
        }

        /**
         * Return the specified header value
         *
         * @param string $name
         * @return string|null
         * @see Req::getHeader
         */
        public function getHeader(string $name): ?string
        {
        }

        /**
         * Check if current request is a preflight request
         *
         * @return bool
         * @link https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request
         * @see Req::isPreflight
         */
        public function isPreflight(): bool
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Res
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Response
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Ret
    {
        /**
         * Return operation successful result
         *
         * ```php
         * // Specified message
         * $this->suc('Payment successful');
         *
         * // Format
         * $this->suc(['me%sage', 'ss']);
         *
         * // More data
         * $this->suc(['message' => 'Read successful', 'page' => 1, 'rows' => 123]);
         * ```
         *
         * @param array|string|null $message
         * @return $this
         * @see Ret::suc
         */
        public function suc($message = null)
        {
        }

        /**
         * Return operation failed result, and logs with an info level
         *
         * @param array|string $message
         * @param int|null $code
         * @param string $level The log level, default to "info"
         * @return $this
         * @see Ret::err
         */
        public function err($message, $code = null, $level = null)
        {
        }

        /**
         * Return operation failed result, and logs with a warning level
         *
         * @param string $message
         * @param int $code
         * @return $this
         * @see Ret::warning
         */
        public function warning($message, $code = null)
        {
        }

        /**
         * Return operation failed result, and logs with an alert level
         *
         * @param string $message
         * @param int $code
         * @return $this
         * @see Ret::alert
         */
        public function alert($message, $code = null)
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Router
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class SafeUrl
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Schema
    {
        /**
         * Check if database exists
         *
         * @param string $database
         * @return bool
         * @see Schema::hasDatabase
         */
        public function hasDatabase(string $database): bool
        {
        }

        /**
         * Create a database
         *
         * @param string $database
         * @return $this
         * @see Schema::createDatabase
         */
        public function createDatabase(string $database): self
        {
        }

        /**
         * Drop a database
         *
         * @param string $database
         * @return $this
         * @see Schema::dropDatabase
         */
        public function dropDatabase(string $database): self
        {
        }

        /**
         * Set user id type
         *
         * @param string $userIdType
         * @return $this
         * @see Schema::setUserIdType
         */
        public function setUserIdType(string $userIdType): self
        {
        }

        /**
         * Get user id type
         *
         * @return string
         * @see Schema::getUserIdType
         */
        public function getUserIdType(): string
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Session
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Share
    {
        /**
         * @param string $title
         * @return $this
         * @see Share::setTitle
         */
        public function setTitle($title)
        {
        }

        /**
         * @return string
         * @see Share::getTitle
         */
        public function getTitle()
        {
        }

        /**
         * @param string $image
         * @return $this
         * @see Share::setImage
         */
        public function setImage($image)
        {
        }

        /**
         * @return string
         * @see Share::getImage
         */
        public function getImage()
        {
        }

        /**
         * @param string $description
         * @return Share
         * @see Share::setDescription
         */
        public function setDescription($description)
        {
        }

        /**
         * @return string
         * @see Share::getDescription
         */
        public function getDescription()
        {
        }

        /**
         * @param string $url
         * @return Share
         * @see Share::setUrl
         */
        public function setUrl($url)
        {
        }

        /**
         * @return string
         * @see Share::getUrl
         */
        public function getUrl()
        {
        }

        /**
         * Returns share data as JSON
         *
         * @return string
         * @see Share::toJson
         */
        public function toJson()
        {
        }

        /**
         * Returns share data as JSON for WeChat
         *
         * @return string
         * @see Share::toWechatJson
         */
        public function toWechatJson()
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Soap
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class StatsD
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class T
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class TagCache
    {
        /**
         * {@inheritdoc}
         * @see TagCache::get
         */
        public function get($key, $default = null)
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::getMultiple
         */
        public function getMultiple(iterable $keys, $default = null): iterable
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::setMultiple
         */
        public function setMultiple(iterable $keys, $ttl = null): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::clear
         */
        public function clear()
        {
        }

        /**
         * {@inheritdoc}
         * @see TagCache::isHit
         */
        public function isHit(string $key = null): bool
        {
        }

        /**
         * Decrement an item
         *
         * @param string $key The name of item
         * @param int $offset The value to be decreased
         * @return int|false Returns the new value on success, or false on failure
         * @see BaseCache::decr
         */
        public function decr($key, $offset = 1)
        {
        }

        /**
         * Store data from callback to cache
         *
         * @param string $key
         * @param int|callable $expireOrFn
         * @param callable|null $fn
         * @return false|mixed
         * @see BaseCache::remember
         */
        public function remember(string $key, $expireOrFn, callable $fn = null)
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Time
    {
        /**
         * @return string
         * @see Time::now
         */
        public function now()
        {
        }

        /**
         * @return string
         * @see Time::today
         */
        public function today()
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Ua
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Upload
    {
        /**
         * Upload a file, return a Ret object
         *
         * @param array $options
         * @return Ret
         * @see Upload::save
         */
        public function save(array $options = []): Ret
        {
        }

        /**
         * Check the input value, return a Ret object
         *
         * @param mixed $input
         * @param string $name
         * @return Ret
         * @see BaseValidator::check
         */
        public function check($input, string $name = '%name%'): Ret
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Url
    {
        /**
         * Generate the URL by specified URL and parameters
         *
         * @param string $url
         * @param array $argsOrParams
         * @param array $params
         * @return string
         * @see Url::to
         */
        public function to($url = '', $argsOrParams = [], $params = [])
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Uuid
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class V
    {
        /**
         * Add name for current field
         *
         * @param string $label
         * @return $this
         * @see V::label
         */
        public function label($label)
        {
        }

        /**
         * Add a new field
         *
         * @param string|array $name
         * @param string|null $label
         * @return $this
         * @see V::key
         */
        public function key($name, $label = null)
        {
        }

        /**
         * @param mixed $value
         * @param callable $callback
         * @param callable|null $default
         * @return $this
         * @see V::when
         */
        public function when($value, $callback, callable $default = null)
        {
        }

        /**
         * @param mixed $value
         * @param callable $callback
         * @param callable|null $default
         * @return $this
         * @see V::unless
         */
        public function unless($value, callable $callback, callable $default = null)
        {
        }

        /**
         * @return $this
         * @see V::defaultOptional
         */
        public function defaultOptional()
        {
        }

        /**
         * @return $this
         * @see V::defaultRequired
         */
        public function defaultRequired()
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAll::__invoke
         */
        public function all(array $rules = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAll::__invoke
         */
        public function notAll(array $rules = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllOf::__invoke
         */
        public function allOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllOf::__invoke
         */
        public function notAllOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllowEmpty::__invoke
         */
        public function allowEmpty()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllowEmpty::__invoke
         */
        public function notAllowEmpty()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlnum::__invoke
         */
        public function alnum($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlnum::__invoke
         */
        public function notAlnum($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlpha::__invoke
         */
        public function alpha($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlpha::__invoke
         */
        public function notAlpha($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAnyDateTime::__invoke
         */
        public function anyDateTime($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAnyDateTime::__invoke
         */
        public function notAnyDateTime($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsArray::__invoke
         */
        public function array($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsArray::__invoke
         */
        public function notArray($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBetween::__invoke
         */
        public function between($min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBetween::__invoke
         */
        public function notBetween($min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBigInt::__invoke
         */
        public function bigInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBigInt::__invoke
         */
        public function notBigInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBlank::__invoke
         */
        public function blank()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBlank::__invoke
         */
        public function notBlank()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBool::__invoke
         */
        public function bool($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBool::__invoke
         */
        public function notBool($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBoolable::__invoke
         */
        public function boolable()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBoolable::__invoke
         */
        public function notBoolable()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCallback::__invoke
         */
        public function callback($fn = null, $message = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCallback::__invoke
         */
        public function notCallback($fn = null, $message = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChar::__invoke
         */
        public function char($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChar::__invoke
         */
        public function notChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChildren::__invoke
         */
        public function children(V $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChildren::__invoke
         */
        public function notChildren(V $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChinese::__invoke
         */
        public function chinese($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChinese::__invoke
         */
        public function notChinese($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsColor::__invoke
         */
        public function color($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsColor::__invoke
         */
        public function notColor($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsContains::__invoke
         */
        public function contains($search = null, $regex = false)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsContains::__invoke
         */
        public function notContains($search = null, $regex = false)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCreditCard::__invoke
         */
        public function creditCard($type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCreditCard::__invoke
         */
        public function notCreditCard($type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDate::__invoke
         */
        public function date($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDate::__invoke
         */
        public function notDate($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDateTime::__invoke
         */
        public function dateTime($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDateTime::__invoke
         */
        public function notDateTime($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDecimal::__invoke
         */
        public function decimal($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDecimal::__invoke
         */
        public function notDecimal($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDefaultInt::__invoke
         */
        public function defaultInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDefaultInt::__invoke
         */
        public function notDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDigit::__invoke
         */
        public function digit($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDigit::__invoke
         */
        public function notDigit($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDir::__invoke
         */
        public function dir()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDir::__invoke
         */
        public function notDir()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDivisibleBy::__invoke
         */
        public function divisibleBy($divisor = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDivisibleBy::__invoke
         */
        public function notDivisibleBy($divisor = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDoubleByte::__invoke
         */
        public function doubleByte($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDoubleByte::__invoke
         */
        public function notDoubleByte($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEach::__invoke
         */
        public function each($v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEach::__invoke
         */
        public function notEach($v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmail::__invoke
         */
        public function email()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmail::__invoke
         */
        public function notEmail()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmpty::__invoke
         */
        public function empty()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmpty::__invoke
         */
        public function notEmpty()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEndsWith::__invoke
         */
        public function endsWith($findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEndsWith::__invoke
         */
        public function notEndsWith($findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEqualTo::__invoke
         */
        public function equalTo($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEqualTo::__invoke
         */
        public function notEqualTo($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsExists::__invoke
         */
        public function exists()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsExists::__invoke
         */
        public function notExists()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFieldExists::__invoke
         */
        public function fieldExists()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFieldExists::__invoke
         */
        public function notFieldExists()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFile::__invoke
         */
        public function file($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFile::__invoke
         */
        public function notFile($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFloat::__invoke
         */
        public function float()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFloat::__invoke
         */
        public function notFloat()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThan::__invoke
         */
        public function greaterThan($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThan::__invoke
         */
        public function notGreaterThan($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThanOrEqual::__invoke
         */
        public function greaterThanOrEqual($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThanOrEqual::__invoke
         */
        public function notGreaterThanOrEqual($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGt::__invoke
         */
        public function gt($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGt::__invoke
         */
        public function notGt($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGte::__invoke
         */
        public function gte($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGte::__invoke
         */
        public function notGte($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardCn::__invoke
         */
        public function idCardCn()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardCn::__invoke
         */
        public function notIdCardCn()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardHk::__invoke
         */
        public function idCardHk()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardHk::__invoke
         */
        public function notIdCardHk()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardMo::__invoke
         */
        public function idCardMo($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardMo::__invoke
         */
        public function notIdCardMo($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardTw::__invoke
         */
        public function idCardTw()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardTw::__invoke
         */
        public function notIdCardTw()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdenticalTo::__invoke
         */
        public function identicalTo($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdenticalTo::__invoke
         */
        public function notIdenticalTo($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsImage::__invoke
         */
        public function image($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsImage::__invoke
         */
        public function notImage($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIn::__invoke
         */
        public function in($array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIn::__invoke
         */
        public function notIn($array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsInt::__invoke
         */
        public function int($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsInt::__invoke
         */
        public function notInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIp::__invoke
         */
        public function ip($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIp::__invoke
         */
        public function notIp($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLength::__invoke
         */
        public function length($min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLength::__invoke
         */
        public function notLength($min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThan::__invoke
         */
        public function lessThan($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThan::__invoke
         */
        public function notLessThan($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThanOrEqual::__invoke
         */
        public function lessThanOrEqual($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThanOrEqual::__invoke
         */
        public function notLessThanOrEqual($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLowercase::__invoke
         */
        public function lowercase()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLowercase::__invoke
         */
        public function notLowercase()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLt::__invoke
         */
        public function lt($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLt::__invoke
         */
        public function notLt($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLte::__invoke
         */
        public function lte($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLte::__invoke
         */
        public function notLte($value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLuhn::__invoke
         */
        public function luhn()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLuhn::__invoke
         */
        public function notLuhn()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxAccuracy::__invoke
         */
        public function maxAccuracy($max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxAccuracy::__invoke
         */
        public function notMaxAccuracy($max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxCharLength::__invoke
         */
        public function maxCharLength($max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxCharLength::__invoke
         */
        public function notMaxCharLength($max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxLength::__invoke
         */
        public function maxLength($max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxLength::__invoke
         */
        public function notMaxLength($max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumInt::__invoke
         */
        public function mediumInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumInt::__invoke
         */
        public function notMediumInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumText::__invoke
         */
        public function mediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumText::__invoke
         */
        public function notMediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinCharLength::__invoke
         */
        public function minCharLength($min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinCharLength::__invoke
         */
        public function notMinCharLength($min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinLength::__invoke
         */
        public function minLength($min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinLength::__invoke
         */
        public function notMinLength($min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMobileCn::__invoke
         */
        public function mobileCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMobileCn::__invoke
         */
        public function notMobileCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNaturalNumber::__invoke
         */
        public function naturalNumber()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNaturalNumber::__invoke
         */
        public function notNaturalNumber()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNoneOf::__invoke
         */
        public function noneOf(array $rules = [], $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNoneOf::__invoke
         */
        public function notNoneOf(array $rules = [], $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNullType::__invoke
         */
        public function nullType()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNullType::__invoke
         */
        public function notNullType()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNumber::__invoke
         */
        public function number($name = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNumber::__invoke
         */
        public function notNumber($name = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsObject::__invoke
         */
        public function object($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsObject::__invoke
         */
        public function notObject($name = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsOneOf::__invoke
         */
        public function oneOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsOneOf::__invoke
         */
        public function notOneOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPassword::__invoke
         */
        public function password(array $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPassword::__invoke
         */
        public function notPassword(array $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhone::__invoke
         */
        public function phone($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhone::__invoke
         */
        public function notPhone($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhoneCn::__invoke
         */
        public function phoneCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhoneCn::__invoke
         */
        public function notPhoneCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPlateNumberCn::__invoke
         */
        public function plateNumberCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPlateNumberCn::__invoke
         */
        public function notPlateNumberCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPositiveInteger::__invoke
         */
        public function positiveInteger()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPositiveInteger::__invoke
         */
        public function notPositiveInteger()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPostcodeCn::__invoke
         */
        public function postcodeCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPostcodeCn::__invoke
         */
        public function notPostcodeCn($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPresent::__invoke
         */
        public function present()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPresent::__invoke
         */
        public function notPresent()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsQQ::__invoke
         */
        public function qQ($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsQQ::__invoke
         */
        public function notQQ($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRecordExists::__invoke
         */
        public function recordExists($table = null, $field = 'id')
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRecordExists::__invoke
         */
        public function notRecordExists($table = null, $field = 'id')
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRegex::__invoke
         */
        public function regex($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRegex::__invoke
         */
        public function notRegex($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRequired::__invoke
         */
        public function required($required = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRequired::__invoke
         */
        public function notRequired($required = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSmallInt::__invoke
         */
        public function smallInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSmallInt::__invoke
         */
        public function notSmallInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSomeOf::__invoke
         */
        public function someOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSomeOf::__invoke
         */
        public function notSomeOf(array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsStartsWith::__invoke
         */
        public function startsWith($findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsStartsWith::__invoke
         */
        public function notStartsWith($findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsString::__invoke
         */
        public function string($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsString::__invoke
         */
        public function notString($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsText::__invoke
         */
        public function text($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsText::__invoke
         */
        public function notText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTime::__invoke
         */
        public function time($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTime::__invoke
         */
        public function notTime($name = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyChar::__invoke
         */
        public function tinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyChar::__invoke
         */
        public function notTinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyInt::__invoke
         */
        public function tinyInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyInt::__invoke
         */
        public function notTinyInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTld::__invoke
         */
        public function tld($array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTld::__invoke
         */
        public function notTld($array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsType::__invoke
         */
        public function type($type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsType::__invoke
         */
        public function notType($type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUBigInt::__invoke
         */
        public function uBigInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUBigInt::__invoke
         */
        public function notUBigInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUDefaultInt::__invoke
         */
        public function uDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUDefaultInt::__invoke
         */
        public function notUDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUMediumInt::__invoke
         */
        public function uMediumInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUMediumInt::__invoke
         */
        public function notUMediumInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUNumber::__invoke
         */
        public function uNumber($name = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUNumber::__invoke
         */
        public function notUNumber($name = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUSmallInt::__invoke
         */
        public function uSmallInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUSmallInt::__invoke
         */
        public function notUSmallInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUTinyInt::__invoke
         */
        public function uTinyInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUTinyInt::__invoke
         */
        public function notUTinyInt($name = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUppercase::__invoke
         */
        public function uppercase()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUppercase::__invoke
         */
        public function notUppercase()
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUrl::__invoke
         */
        public function url($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUrl::__invoke
         */
        public function notUrl($options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUuid::__invoke
         */
        public function uuid($pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUuid::__invoke
         */
        public function notUuid($pattern = null)
        {
        }
    }

    class Validate
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class View
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class WeChatApp
    {
        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }

    class Wei
    {
        /**
         * Set service's configuration
         *
         * @param string|array $name
         * @param mixed $value
         * @return $this
         * @see Wei::setConfig
         */
        public function setConfig($name, $value = null)
        {
        }

        /**
         * Get services' configuration
         *
         * @param string $name The name of configuration
         * @param mixed $default The default value if configuration not found
         * @return mixed
         * @see Wei::getConfig
         */
        public function getConfig($name = null, $default = null)
        {
        }

        /**
         * Remove services' configuration
         *
         * @param string $name
         * @return $this
         * @see Wei::removeConfig
         */
        public function removeConfig(string $name): self
        {
        }

        /**
         * Get service by class name
         *
         * @template T
         * @param string|class-string<T> $class
         * @return Base|T
         * @see Wei::getBy
         */
        public function getBy(string $class): Base
        {
        }

        /**
         * Return the current service object
         *
         * @return $this
         * @experimental
         * @see Base::instance
         */
        public function instance(): self
        {
        }
    }
}
