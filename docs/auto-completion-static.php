<?php

namespace Wei;

class Apcu
{
    /**
     * {@inheritdoc}
     * @see Apcu::set
     */
    public static function set($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::delete
     */
    public static function delete(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::has
     */
    public static function has(string $key): bool
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::add
     */
    public static function add($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::replace
     */
    public static function replace($key, $value, $expire = 0)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::incr
     */
    public static function incr($key, $offset = 1)
    {
    }

    /**
     * {@inheritdoc}
     * @see Apcu::clear
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
}

class App
{
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
}

class Asset
{
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
}

class Block
{
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
}

class ClassMap
{
}

class Cls
{
    /**
     * Return the traits used by the given class, including those used by all parent classes and other traits
     *
     * @param string|object $class
     * @param bool $autoload
     * @return array
     * @see https://www.php.net/manual/en/function.class-uses.php#112671
     * @see Cls::usesDeep
     */
    public static function usesDeep($class, bool $autoload = true): array
    {
    }
}

class Config
{
}

class Cookie
{
}

class Counter
{
}

class Db
{
    /**
     * Set the prefix string of table name
     *
     * @param string $tablePrefix
     * @return $this
     * @see Db::setTablePrefix
     */
    public static function setTablePrefix(string $tablePrefix): self
    {
    }

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
}

class E
{
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
}

class Error
{
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
}

class Gravatar
{
}

class Http
{
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
}

class IsAllow
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
}

class Lock
{
}

class Logger
{
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
     * Return the front cache object
     *
     * @return BaseCache
     * @see NearCache::getFront
     */
    public static function getFront(): BaseCache
    {
    }

    /**
     * Return the back cache object
     *
     * @return BaseCache
     * @see NearCache::getBack
     */
    public static function getBack(): BaseCache
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
}

class PhpError
{
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
}

class Pinyin
{
}

class QueryBuilder
{
    /**
     * Set or remove cache time for the query
     *
     * @param int|null $seconds
     * @return $this
     * @see QueryBuilder::setCacheTime
     */
    public static function setCacheTime(?int $seconds): self
    {
    }

    /**
     * Return the record table name
     *
     * @return string|null
     * @see QueryBuilder::getTable
     */
    public static function getTable(): ?string
    {
    }

    /**
     * Returns the name of columns of current table
     *
     * @return array
     * @see QueryBuilder::getColumns
     */
    public static function getColumns(): array
    {
    }

    /**
     * Check if column name exists
     *
     * @param string|int|null $name
     * @return bool
     * @see QueryBuilder::hasColumn
     */
    public static function hasColumn($name): bool
    {
    }

    /**
     * Executes the generated query and returns the first array result
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return array|null
     * @see QueryBuilder::fetch
     */
    public static function fetch($column = null, $operator = null, $value = null): ?array
    {
    }

    /**
     * Executes the generated query and returns all array results
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return array
     * @see QueryBuilder::fetchAll
     */
    public static function fetchAll($column = null, $operator = null, $value = null): array
    {
    }

    /**
     * Executes the generated SQL and returns the found record object or null if not found
     *
     * @return array|null
     * @see QueryBuilder::first
     */
    public static function first(): ?array
    {
    }

    /**
     * @return array
     * @see QueryBuilder::all
     */
    public static function all(): array
    {
    }

    /**
     * @param string $column
     * @param string|null $index
     * @return array
     * @see QueryBuilder::pluck
     */
    public static function pluck(string $column, string $index = null): array
    {
    }

    /**
     * @param int $count
     * @param callable $callback
     * @return bool
     * @see QueryBuilder::chunk
     */
    public static function chunk(int $count, callable $callback): bool
    {
    }

    /**
     * Executes a COUNT query to receive the rows number
     *
     * @param string $column
     * @return int
     * @see QueryBuilder::cnt
     */
    public static function cnt($column = '*'): int
    {
    }

    /**
     * Executes a MAX query to receive the max value of column
     *
     * @param string $column
     * @return string|null
     * @see QueryBuilder::max
     */
    public static function max(string $column): ?string
    {
    }

    /**
     * Execute a update query with specified data
     *
     * @param array|string $set
     * @param mixed $value
     * @return int
     * @see QueryBuilder::update
     */
    public static function update($set = [], $value = null): int
    {
    }

    /**
     * Execute a delete query with specified conditions
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return int
     * @see QueryBuilder::delete
     */
    public static function delete($column = null, $operator = null, $value = null): int
    {
    }

    /**
     * Sets the position of the first result to retrieve (the "offset")
     *
     * @param int|float|string $offset The first result to return
     * @return $this
     * @see QueryBuilder::offset
     */
    public static function offset($offset): self
    {
    }

    /**
     * Sets the maximum number of results to retrieve (the "limit")
     *
     * @param int|float|string $limit The maximum number of results to retrieve
     * @return $this
     * @see QueryBuilder::limit
     */
    public static function limit($limit): self
    {
    }

    /**
     * Sets the page number, the "OFFSET" value is equals "($page - 1) * LIMIT"
     *
     * @param int $page The page number
     * @return $this
     * @see QueryBuilder::page
     */
    public static function page($page): self
    {
    }

    /**
     * Specifies an item that is to be returned in the query result.
     * Replaces any previously specified selections, if any.
     *
     * @param array|string $columns the selection expressions
     * @return $this
     * @see QueryBuilder::select
     */
    public static function select($columns = ['*']): self
    {
    }

    /**
     * @param array|string $columns
     * @return $this
     * @see QueryBuilder::selectDistinct
     */
    public static function selectDistinct($columns): self
    {
    }

    /**
     * @param string $expression
     * @return $this
     * @see QueryBuilder::selectRaw
     */
    public static function selectRaw($expression): self
    {
    }

    /**
     * Specifies columns that are not to be returned in the query result.
     * Replaces any previously specified selections, if any.
     *
     * @param array|string $columns
     * @return $this
     * @see QueryBuilder::selectExcept
     */
    public static function selectExcept($columns): self
    {
    }

    /**
     * Specifies an item of the main table that is to be returned in the query result.
     * Default to all columns of the main table
     *
     * @param string $column
     * @return $this
     * @see QueryBuilder::selectMain
     */
    public static function selectMain(string $column = '*'): self
    {
    }

    /**
     * Sets table for FROM query
     *
     * @param string $table
     * @param string|null $alias
     * @return $this
     * @see QueryBuilder::from
     */
    public static function from(string $table, $alias = null): self
    {
    }

    /**
     * @param string $table
     * @param mixed|null $alias
     * @return $this
     * @see QueryBuilder::table
     */
    public static function table(string $table, $alias = null): self
    {
    }

    /**
     * Adds a inner join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @param string $type
     * @return $this
     * @see QueryBuilder::join
     */
    public static function join(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null,
        string $type = 'INNER'
    ): self {
    }

    /**
     * Adds a inner join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @see QueryBuilder::innerJoin
     */
    public static function innerJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
    }

    /**
     * Adds a left join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @see QueryBuilder::leftJoin
     */
    public static function leftJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
    }

    /**
     * Adds a right join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @see QueryBuilder::rightJoin
     */
    public static function rightJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
    }

    /**
     * Specifies one or more restrictions to the query result.
     * Replaces any previously specified restrictions, if any.
     *
     * ```php
     * $user = wei()->db('user')->where('id = 1');
     * $user = wei()->db('user')->where('id = ?', 1);
     * $users = wei()->db('user')->where(array('id' => '1', 'username' => 'twin'));
     * $users = wei()->where(array('id' => array('1', '2', '3')));
     * ```
     *
     * @param array|Closure|string|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::where
     */
    public static function where($column = null, $operator = null, $value = null): self
    {
    }

    /**
     * @param scalar $expression
     * @param mixed $params
     * @return $this
     * @see QueryBuilder::whereRaw
     */
    public static function whereRaw($expression, $params = null): self
    {
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @see QueryBuilder::whereBetween
     */
    public static function whereBetween(string $column, array $params): self
    {
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @see QueryBuilder::whereNotBetween
     */
    public static function whereNotBetween(string $column, array $params): self
    {
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @see QueryBuilder::whereIn
     */
    public static function whereIn(string $column, array $params): self
    {
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @see QueryBuilder::whereNotIn
     */
    public static function whereNotIn(string $column, array $params): self
    {
    }

    /**
     * @param string $column
     * @return $this
     * @see QueryBuilder::whereNull
     */
    public static function whereNull(string $column): self
    {
    }

    /**
     * @param string $column
     * @return $this
     * @see QueryBuilder::whereNotNull
     */
    public static function whereNotNull(string $column): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::whereDate
     */
    public static function whereDate(string $column, $opOrValue, $value = null): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::whereMonth
     */
    public static function whereMonth(string $column, $opOrValue, $value = null): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::whereDay
     */
    public static function whereDay(string $column, $opOrValue, $value = null): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::whereYear
     */
    public static function whereYear(string $column, $opOrValue, $value = null): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @see QueryBuilder::whereTime
     */
    public static function whereTime(string $column, $opOrValue, $value = null): self
    {
    }

    /**
     * @param string $column
     * @param mixed $opOrColumn2
     * @param mixed|null $column2
     * @return $this
     * @see QueryBuilder::whereColumn
     */
    public static function whereColumn(string $column, $opOrColumn2, $column2 = null): self
    {
    }

    /**
     * 搜索字段是否包含某个值
     *
     * @param string $column
     * @param mixed $value
     * @param string $condition
     * @return $this
     * @see QueryBuilder::whereContains
     */
    public static function whereContains(string $column, $value, string $condition = 'AND'): self
    {
    }

    /**
     * @param string $column
     * @param mixed $value
     * @param string $condition
     * @return $this
     * @see QueryBuilder::whereNotContains
     */
    public static function whereNotContains(string $column, $value, string $condition = 'OR'): self
    {
    }

    /**
     * Search whether a column has a value other than the default value
     *
     * @param string $column
     * @param bool $has
     * @return $this
     * @see QueryBuilder::whereHas
     */
    public static function whereHas(string $column, bool $has = true): self
    {
    }

    /**
     * Search whether a column dont have a value other than the default value
     *
     * @param string $column
     * @return $this
     * @see QueryBuilder::whereNotHas
     */
    public static function whereNotHas(string $column): self
    {
    }

    /**
     * Specifies a grouping over the results of the query.
     * Replaces any previously specified groupings, if any.
     *
     * @param mixed $column the grouping column
     * @return $this
     * @see QueryBuilder::groupBy
     */
    public static function groupBy($column): self
    {
    }

    /**
     * Specifies a restriction over the groups of the query.
     * Replaces any previous having restrictions, if any.
     *
     * @param mixed $column
     * @param mixed $operator
     * @param mixed|null $value
     * @param mixed $condition
     * @return $this
     * @see QueryBuilder::having
     */
    public static function having($column, $operator, $value = null, $condition = 'AND'): self
    {
    }

    /**
     * Specifies an ordering for the query results.
     * Replaces any previously specified orderings, if any.
     *
     * @param string|Raw $column the ordering expression
     * @param string $order the ordering direction
     * @return $this
     * @see QueryBuilder::orderBy
     */
    public static function orderBy($column, $order = 'ASC'): self
    {
    }

    /**
     * @param scalar $expression
     * @return $this
     * @see QueryBuilder::orderByRaw
     */
    public static function orderByRaw($expression): self
    {
    }

    /**
     * Adds a DESC ordering to the query
     *
     * @param string $field The name of field
     * @return $this
     * @see QueryBuilder::desc
     */
    public static function desc(string $field): self
    {
    }

    /**
     * Add an ASC ordering to the query
     *
     * @param string $field The name of field
     * @return $this
     * @see QueryBuilder::asc
     */
    public static function asc(string $field): self
    {
    }

    /**
     * Specifies a field to be the key of the fetched array
     *
     * @param string $column
     * @return $this
     * @see QueryBuilder::indexBy
     */
    public static function indexBy(string $column): self
    {
    }

    /**
     * @return $this
     * @see QueryBuilder::forUpdate
     */
    public static function forUpdate(): self
    {
    }

    /**
     * @return $this
     * @see QueryBuilder::forShare
     */
    public static function forShare(): self
    {
    }

    /**
     * @param string|bool $lock
     * @return $this
     * @see QueryBuilder::lock
     */
    public static function lock($lock): self
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see QueryBuilder::when
     */
    public static function when($value, callable $callback, callable $default = null): self
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see QueryBuilder::unless
     */
    public static function unless($value, callable $callback, callable $default = null): self
    {
    }

    /**
     * @param callable|null $converter
     * @return $this
     * @see QueryBuilder::setDbKeyConverter
     */
    public static function setDbKeyConverter(callable $converter = null): self
    {
    }

    /**
     * @param callable|null $converter
     * @return $this
     * @see QueryBuilder::setPhpKeyConverter
     */
    public static function setPhpKeyConverter(callable $converter = null): self
    {
    }
}

class Record
{
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
}

class Res
{
}

class Response
{
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
}

class Router
{
}

class SafeUrl
{
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
}

class Session
{
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
}

class Soap
{
}

class StatsD
{
}

class Str
{
    /**
     * Returns a word in plural form
     *
     * @param string $word
     * @return string
     * @experimental will remove doctrine dependency
     * @see Str::pluralize
     */
    public static function pluralize(string $word): string
    {
    }

    /**
     * Returns a word in singular form
     *
     * @param string $word
     * @return string
     * @experimental will remove doctrine dependency
     * @see Str::singularize
     */
    public static function singularize(string $word): string
    {
    }

    /**
     * Convert a input to snake case
     *
     * @param string $input
     * @param string $delimiter
     * @return string
     * @see Str::snake
     */
    public static function snake(string $input, string $delimiter = '_'): string
    {
    }

    /**
     * Convert a input to camel case
     *
     * @param string $input
     * @return string
     * @see Str::camel
     */
    public static function camel(string $input): string
    {
    }

    /**
     * Convert a input to dash case
     *
     * @param string $input
     * @return string
     * @see Str::dash
     */
    public static function dash(string $input): string
    {
    }
}

class T
{
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
     * @return int
     * @see Time::timestamp
     */
    public static function timestamp()
    {
    }
}

class Ua
{
}

class Upload
{
    /**
     * Upload a file, return a Ret object
     *
     * @param array $options
     * @return Ret|array{file: string, name: string, size: int, mimeType: string}
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
}

class Uuid
{
}

class V
{
    /**
     * Add label for current validator
     *
     * @param string $label
     * @return self
     * @see V::label
     */
    public static function label(string $label): self
    {
    }

    /**
     * @return $this
     * @see V::defaultOptional
     */
    public static function defaultOptional(): self
    {
    }

    /**
     * @return $this
     * @see V::defaultRequired
     */
    public static function defaultRequired(): self
    {
    }

    /**
     * @return $this
     * @see V::defaultNotEmpty
     * @experimental
     */
    public static function defaultNotEmpty(): self
    {
    }

    /**
     * @return $this
     * @see V::defaultAllowEmpty
     */
    public static function defaultAllowEmpty(): self
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function all($input, array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function notAll($input, array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function allOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function notAllOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllow::__invoke
     */
    public static function allow($input, ...$values)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllow::__invoke
     */
    public static function notAllow($input, ...$values)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllowEmpty::__invoke
     */
    public static function allowEmpty($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllowEmpty::__invoke
     */
    public static function notAllowEmpty($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function alnum($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function notAlnum($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function alpha($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function notAlpha($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAnyDateTime::__invoke
     */
    public static function anyDateTime($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAnyDateTime::__invoke
     */
    public static function notAnyDateTime($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function array($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function notArray($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function between($input, $min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function notBetween($input, $min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function bigInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function notBigInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function blank($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function notBlank($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function bool($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function notBool($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function boolable($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function notBoolable($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function callback($input, $fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function notCallback($input, $fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function char($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function notChar($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function children($input, V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function notChildren($input, V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function chinese($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function notChinese($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function color($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function notColor($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function contains($input, $search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function notContains($input, $search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function creditCard($input, $type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function notCreditCard($input, $type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function date($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function notDate($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function dateTime($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function notDateTime($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function decimal($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function notDecimal($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function defaultInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function notDefaultInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function digit($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function notDigit($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function dir($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function notDir($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function divisibleBy($input, $divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function notDivisibleBy($input, $divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function doubleByte($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function notDoubleByte($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function each($input, $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function notEach($input, $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function email($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function notEmail($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmpty::__invoke
     */
    public static function empty($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmpty::__invoke
     */
    public static function notEmpty($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function endsWith($input, $findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function notEndsWith($input, $findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function equalTo($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function notEqualTo($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function exists($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function notExists($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function fieldExists($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function notFieldExists($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function file($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function notFile($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function float($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function notFloat($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function greaterThan($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function notGreaterThan($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function greaterThanOrEqual($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function notGreaterThanOrEqual($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function gt($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function notGt($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function gte($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function notGte($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function idCardCn($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function notIdCardCn($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function idCardHk($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function notIdCardHk($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function idCardMo($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function notIdCardMo($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function idCardTw($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function notIdCardTw($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function identicalTo($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function notIdenticalTo($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function image($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function notImage($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function in($input, $array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function notIn($input, $array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function int($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function notInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function ip($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function notIp($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function length($input, $min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function notLength($input, $min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function lessThan($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function notLessThan($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function lessThanOrEqual($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function notLessThanOrEqual($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function lowercase($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function notLowercase($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function lt($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function notLt($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function lte($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function notLte($input, $value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function luhn($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function notLuhn($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function maxAccuracy($input, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function notMaxAccuracy($input, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function maxCharLength($input, $max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function notMaxCharLength($input, $max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function maxLength($input, $max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function notMaxLength($input, $max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function mediumInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function notMediumInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function mediumText($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function notMediumText($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function minCharLength($input, $min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function notMinCharLength($input, $min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function minLength($input, $min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function notMinLength($input, $min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function mobileCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function notMobileCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function naturalNumber($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function notNaturalNumber($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function noneOf($input, array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function notNoneOf($input, array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function nullType($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function notNullType($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function number($input, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function notNumber($input, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsObject::__invoke
     */
    public static function object($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsObject::__invoke
     */
    public static function notObject($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function oneOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function notOneOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function password($input, array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function notPassword($input, array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function phone($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function notPhone($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function phoneCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function notPhoneCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function plateNumberCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function notPlateNumberCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function positiveInteger($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function notPositiveInteger($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function postcodeCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function notPostcodeCn($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function present($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function notPresent($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function qQ($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function notQQ($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function recordExists($input = null, $table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function notRecordExists($input = null, $table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function regex($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function notRegex($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function required($input, $required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function notRequired($input, $required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function smallInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function notSmallInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function someOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function notSomeOf($input, array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function startsWith($input, $findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function notStartsWith($input, $findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function string($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function notString($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function text($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function notText($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function time($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function notTime($input, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function tinyChar($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function notTinyChar($input, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function tinyInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function notTinyInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function tld($input, $array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function notTld($input, $array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function type($input, $type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function notType($input, $type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function uBigInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function notUBigInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function uDefaultInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function notUDefaultInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function uMediumInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function notUMediumInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function uNumber($input, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function notUNumber($input, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function uSmallInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function notUSmallInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function uTinyInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function notUTinyInt($input, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function uppercase($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function notUppercase($input)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function url($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function notUrl($input, $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function uuid($input, $pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function notUuid($input, $pattern = null)
    {
    }
}

class Validate
{
}

class View
{
}

class WeChatApp
{
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
}

namespace Wei;

if (0) {
    class Apcu
    {
        /**
         * {@inheritdoc}
         * @see Apcu::set
         */
        public function set($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::delete
         */
        public function delete(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::has
         */
        public function has(string $key): bool
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::add
         */
        public function add($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::replace
         */
        public function replace($key, $value, $expire = 0)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::incr
         */
        public function incr($key, $offset = 1)
        {
        }

        /**
         * {@inheritdoc}
         * @see Apcu::clear
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
    }

    class App
    {
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
    }

    class Asset
    {
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
    }

    class Block
    {
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
    }

    class ClassMap
    {
    }

    class Cls
    {
        /**
         * Return the traits used by the given class, including those used by all parent classes and other traits
         *
         * @param string|object $class
         * @param bool $autoload
         * @return array
         * @see https://www.php.net/manual/en/function.class-uses.php#112671
         * @see Cls::usesDeep
         */
        public function usesDeep($class, bool $autoload = true): array
        {
        }
    }

    class Config
    {
    }

    class Cookie
    {
    }

    class Counter
    {
    }

    class Db
    {
        /**
         * Set the prefix string of table name
         *
         * @param string $tablePrefix
         * @return $this
         * @see Db::setTablePrefix
         */
        public function setTablePrefix(string $tablePrefix): self
        {
        }

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
    }

    class E
    {
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
    }

    class Error
    {
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
    }

    class Gravatar
    {
    }

    class Http
    {
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
    }

    class IsAllow
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
    }

    class Lock
    {
    }

    class Logger
    {
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
         * Return the front cache object
         *
         * @return BaseCache
         * @see NearCache::getFront
         */
        public function getFront(): BaseCache
        {
        }

        /**
         * Return the back cache object
         *
         * @return BaseCache
         * @see NearCache::getBack
         */
        public function getBack(): BaseCache
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
    }

    class PhpError
    {
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
    }

    class Pinyin
    {
    }

    class QueryBuilder
    {
        /**
         * Set or remove cache time for the query
         *
         * @param int|null $seconds
         * @return $this
         * @see QueryBuilder::setCacheTime
         */
        public function setCacheTime(?int $seconds): self
        {
        }

        /**
         * Return the record table name
         *
         * @return string|null
         * @see QueryBuilder::getTable
         */
        public function getTable(): ?string
        {
        }

        /**
         * Returns the name of columns of current table
         *
         * @return array
         * @see QueryBuilder::getColumns
         */
        public function getColumns(): array
        {
        }

        /**
         * Check if column name exists
         *
         * @param string|int|null $name
         * @return bool
         * @see QueryBuilder::hasColumn
         */
        public function hasColumn($name): bool
        {
        }

        /**
         * Executes the generated query and returns the first array result
         *
         * @param mixed|null $column
         * @param mixed|null $operator
         * @param mixed|null $value
         * @return array|null
         * @see QueryBuilder::fetch
         */
        public function fetch($column = null, $operator = null, $value = null): ?array
        {
        }

        /**
         * Executes the generated query and returns all array results
         *
         * @param mixed|null $column
         * @param mixed|null $operator
         * @param mixed|null $value
         * @return array
         * @see QueryBuilder::fetchAll
         */
        public function fetchAll($column = null, $operator = null, $value = null): array
        {
        }

        /**
         * Executes the generated SQL and returns the found record object or null if not found
         *
         * @return array|null
         * @see QueryBuilder::first
         */
        public function first(): ?array
        {
        }

        /**
         * @return array
         * @see QueryBuilder::all
         */
        public function all(): array
        {
        }

        /**
         * @param string $column
         * @param string|null $index
         * @return array
         * @see QueryBuilder::pluck
         */
        public function pluck(string $column, string $index = null): array
        {
        }

        /**
         * @param int $count
         * @param callable $callback
         * @return bool
         * @see QueryBuilder::chunk
         */
        public function chunk(int $count, callable $callback): bool
        {
        }

        /**
         * Executes a COUNT query to receive the rows number
         *
         * @param string $column
         * @return int
         * @see QueryBuilder::cnt
         */
        public function cnt($column = '*'): int
        {
        }

        /**
         * Executes a MAX query to receive the max value of column
         *
         * @param string $column
         * @return string|null
         * @see QueryBuilder::max
         */
        public function max(string $column): ?string
        {
        }

        /**
         * Execute a update query with specified data
         *
         * @param array|string $set
         * @param mixed $value
         * @return int
         * @see QueryBuilder::update
         */
        public function update($set = [], $value = null): int
        {
        }

        /**
         * Execute a delete query with specified conditions
         *
         * @param mixed|null $column
         * @param mixed|null $operator
         * @param mixed|null $value
         * @return int
         * @see QueryBuilder::delete
         */
        public function delete($column = null, $operator = null, $value = null): int
        {
        }

        /**
         * Sets the position of the first result to retrieve (the "offset")
         *
         * @param int|float|string $offset The first result to return
         * @return $this
         * @see QueryBuilder::offset
         */
        public function offset($offset): self
        {
        }

        /**
         * Sets the maximum number of results to retrieve (the "limit")
         *
         * @param int|float|string $limit The maximum number of results to retrieve
         * @return $this
         * @see QueryBuilder::limit
         */
        public function limit($limit): self
        {
        }

        /**
         * Sets the page number, the "OFFSET" value is equals "($page - 1) * LIMIT"
         *
         * @param int $page The page number
         * @return $this
         * @see QueryBuilder::page
         */
        public function page($page): self
        {
        }

        /**
         * Specifies an item that is to be returned in the query result.
         * Replaces any previously specified selections, if any.
         *
         * @param array|string $columns the selection expressions
         * @return $this
         * @see QueryBuilder::select
         */
        public function select($columns = ['*']): self
        {
        }

        /**
         * @param array|string $columns
         * @return $this
         * @see QueryBuilder::selectDistinct
         */
        public function selectDistinct($columns): self
        {
        }

        /**
         * @param string $expression
         * @return $this
         * @see QueryBuilder::selectRaw
         */
        public function selectRaw($expression): self
        {
        }

        /**
         * Specifies columns that are not to be returned in the query result.
         * Replaces any previously specified selections, if any.
         *
         * @param array|string $columns
         * @return $this
         * @see QueryBuilder::selectExcept
         */
        public function selectExcept($columns): self
        {
        }

        /**
         * Specifies an item of the main table that is to be returned in the query result.
         * Default to all columns of the main table
         *
         * @param string $column
         * @return $this
         * @see QueryBuilder::selectMain
         */
        public function selectMain(string $column = '*'): self
        {
        }

        /**
         * Sets table for FROM query
         *
         * @param string $table
         * @param string|null $alias
         * @return $this
         * @see QueryBuilder::from
         */
        public function from(string $table, $alias = null): self
        {
        }

        /**
         * @param string $table
         * @param mixed|null $alias
         * @return $this
         * @see QueryBuilder::table
         */
        public function table(string $table, $alias = null): self
        {
        }

        /**
         * Adds a inner join to the query
         *
         * @param string $table The table name to join
         * @param string|null $first
         * @param string $operator
         * @param string|null $second
         * @param string $type
         * @return $this
         * @see QueryBuilder::join
         */
        public function join(
            string $table,
            string $first = null,
            string $operator = '=',
            string $second = null,
            string $type = 'INNER'
        ): self {
        }

        /**
         * Adds a inner join to the query
         *
         * @param string $table The table name to join
         * @param string|null $first
         * @param string $operator
         * @param string|null $second
         * @return $this
         * @see QueryBuilder::innerJoin
         */
        public function innerJoin(string $table, string $first = null, string $operator = '=', string $second = null): self
        {
        }

        /**
         * Adds a left join to the query
         *
         * @param string $table The table name to join
         * @param string|null $first
         * @param string $operator
         * @param string|null $second
         * @return $this
         * @see QueryBuilder::leftJoin
         */
        public function leftJoin(string $table, string $first = null, string $operator = '=', string $second = null): self
        {
        }

        /**
         * Adds a right join to the query
         *
         * @param string $table The table name to join
         * @param string|null $first
         * @param string $operator
         * @param string|null $second
         * @return $this
         * @see QueryBuilder::rightJoin
         */
        public function rightJoin(string $table, string $first = null, string $operator = '=', string $second = null): self
        {
        }

        /**
         * Specifies one or more restrictions to the query result.
         * Replaces any previously specified restrictions, if any.
         *
         * ```php
         * $user = wei()->db('user')->where('id = 1');
         * $user = wei()->db('user')->where('id = ?', 1);
         * $users = wei()->db('user')->where(array('id' => '1', 'username' => 'twin'));
         * $users = wei()->where(array('id' => array('1', '2', '3')));
         * ```
         *
         * @param array|Closure|string|null $column
         * @param mixed|null $operator
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::where
         */
        public function where($column = null, $operator = null, $value = null): self
        {
        }

        /**
         * @param scalar $expression
         * @param mixed $params
         * @return $this
         * @see QueryBuilder::whereRaw
         */
        public function whereRaw($expression, $params = null): self
        {
        }

        /**
         * @param string $column
         * @param array $params
         * @return $this
         * @see QueryBuilder::whereBetween
         */
        public function whereBetween(string $column, array $params): self
        {
        }

        /**
         * @param string $column
         * @param array $params
         * @return $this
         * @see QueryBuilder::whereNotBetween
         */
        public function whereNotBetween(string $column, array $params): self
        {
        }

        /**
         * @param string $column
         * @param array $params
         * @return $this
         * @see QueryBuilder::whereIn
         */
        public function whereIn(string $column, array $params): self
        {
        }

        /**
         * @param string $column
         * @param array $params
         * @return $this
         * @see QueryBuilder::whereNotIn
         */
        public function whereNotIn(string $column, array $params): self
        {
        }

        /**
         * @param string $column
         * @return $this
         * @see QueryBuilder::whereNull
         */
        public function whereNull(string $column): self
        {
        }

        /**
         * @param string $column
         * @return $this
         * @see QueryBuilder::whereNotNull
         */
        public function whereNotNull(string $column): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrValue
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::whereDate
         */
        public function whereDate(string $column, $opOrValue, $value = null): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrValue
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::whereMonth
         */
        public function whereMonth(string $column, $opOrValue, $value = null): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrValue
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::whereDay
         */
        public function whereDay(string $column, $opOrValue, $value = null): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrValue
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::whereYear
         */
        public function whereYear(string $column, $opOrValue, $value = null): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrValue
         * @param mixed|null $value
         * @return $this
         * @see QueryBuilder::whereTime
         */
        public function whereTime(string $column, $opOrValue, $value = null): self
        {
        }

        /**
         * @param string $column
         * @param mixed $opOrColumn2
         * @param mixed|null $column2
         * @return $this
         * @see QueryBuilder::whereColumn
         */
        public function whereColumn(string $column, $opOrColumn2, $column2 = null): self
        {
        }

        /**
         * 搜索字段是否包含某个值
         *
         * @param string $column
         * @param mixed $value
         * @param string $condition
         * @return $this
         * @see QueryBuilder::whereContains
         */
        public function whereContains(string $column, $value, string $condition = 'AND'): self
        {
        }

        /**
         * @param string $column
         * @param mixed $value
         * @param string $condition
         * @return $this
         * @see QueryBuilder::whereNotContains
         */
        public function whereNotContains(string $column, $value, string $condition = 'OR'): self
        {
        }

        /**
         * Search whether a column has a value other than the default value
         *
         * @param string $column
         * @param bool $has
         * @return $this
         * @see QueryBuilder::whereHas
         */
        public function whereHas(string $column, bool $has = true): self
        {
        }

        /**
         * Search whether a column dont have a value other than the default value
         *
         * @param string $column
         * @return $this
         * @see QueryBuilder::whereNotHas
         */
        public function whereNotHas(string $column): self
        {
        }

        /**
         * Specifies a grouping over the results of the query.
         * Replaces any previously specified groupings, if any.
         *
         * @param mixed $column the grouping column
         * @return $this
         * @see QueryBuilder::groupBy
         */
        public function groupBy($column): self
        {
        }

        /**
         * Specifies a restriction over the groups of the query.
         * Replaces any previous having restrictions, if any.
         *
         * @param mixed $column
         * @param mixed $operator
         * @param mixed|null $value
         * @param mixed $condition
         * @return $this
         * @see QueryBuilder::having
         */
        public function having($column, $operator, $value = null, $condition = 'AND'): self
        {
        }

        /**
         * Specifies an ordering for the query results.
         * Replaces any previously specified orderings, if any.
         *
         * @param string|Raw $column the ordering expression
         * @param string $order the ordering direction
         * @return $this
         * @see QueryBuilder::orderBy
         */
        public function orderBy($column, $order = 'ASC'): self
        {
        }

        /**
         * @param scalar $expression
         * @return $this
         * @see QueryBuilder::orderByRaw
         */
        public function orderByRaw($expression): self
        {
        }

        /**
         * Adds a DESC ordering to the query
         *
         * @param string $field The name of field
         * @return $this
         * @see QueryBuilder::desc
         */
        public function desc(string $field): self
        {
        }

        /**
         * Add an ASC ordering to the query
         *
         * @param string $field The name of field
         * @return $this
         * @see QueryBuilder::asc
         */
        public function asc(string $field): self
        {
        }

        /**
         * Specifies a field to be the key of the fetched array
         *
         * @param string $column
         * @return $this
         * @see QueryBuilder::indexBy
         */
        public function indexBy(string $column): self
        {
        }

        /**
         * @return $this
         * @see QueryBuilder::forUpdate
         */
        public function forUpdate(): self
        {
        }

        /**
         * @return $this
         * @see QueryBuilder::forShare
         */
        public function forShare(): self
        {
        }

        /**
         * @param string|bool $lock
         * @return $this
         * @see QueryBuilder::lock
         */
        public function lock($lock): self
        {
        }

        /**
         * @param mixed $value
         * @param callable $callback
         * @param callable|null $default
         * @return $this
         * @see QueryBuilder::when
         */
        public function when($value, callable $callback, callable $default = null): self
        {
        }

        /**
         * @param mixed $value
         * @param callable $callback
         * @param callable|null $default
         * @return $this
         * @see QueryBuilder::unless
         */
        public function unless($value, callable $callback, callable $default = null): self
        {
        }

        /**
         * @param callable|null $converter
         * @return $this
         * @see QueryBuilder::setDbKeyConverter
         */
        public function setDbKeyConverter(callable $converter = null): self
        {
        }

        /**
         * @param callable|null $converter
         * @return $this
         * @see QueryBuilder::setPhpKeyConverter
         */
        public function setPhpKeyConverter(callable $converter = null): self
        {
        }
    }

    class Record
    {
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
    }

    class Res
    {
    }

    class Response
    {
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
    }

    class Router
    {
    }

    class SafeUrl
    {
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
    }

    class Session
    {
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
    }

    class Soap
    {
    }

    class StatsD
    {
    }

    class Str
    {
        /**
         * Returns a word in plural form
         *
         * @param string $word
         * @return string
         * @experimental will remove doctrine dependency
         * @see Str::pluralize
         */
        public function pluralize(string $word): string
        {
        }

        /**
         * Returns a word in singular form
         *
         * @param string $word
         * @return string
         * @experimental will remove doctrine dependency
         * @see Str::singularize
         */
        public function singularize(string $word): string
        {
        }

        /**
         * Convert a input to snake case
         *
         * @param string $input
         * @param string $delimiter
         * @return string
         * @see Str::snake
         */
        public function snake(string $input, string $delimiter = '_'): string
        {
        }

        /**
         * Convert a input to camel case
         *
         * @param string $input
         * @return string
         * @see Str::camel
         */
        public function camel(string $input): string
        {
        }

        /**
         * Convert a input to dash case
         *
         * @param string $input
         * @return string
         * @see Str::dash
         */
        public function dash(string $input): string
        {
        }
    }

    class T
    {
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
         * @return int
         * @see Time::timestamp
         */
        public function timestamp()
        {
        }
    }

    class Ua
    {
    }

    class Upload
    {
        /**
         * Upload a file, return a Ret object
         *
         * @param array $options
         * @return Ret|array{file: string, name: string, size: int, mimeType: string}
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
    }

    class Uuid
    {
    }

    class V
    {
        /**
         * Add label for current validator
         *
         * @param string $label
         * @return self
         * @see V::label
         */
        public function label(string $label): self
        {
        }

        /**
         * @return $this
         * @see V::defaultOptional
         */
        public function defaultOptional(): self
        {
        }

        /**
         * @return $this
         * @see V::defaultRequired
         */
        public function defaultRequired(): self
        {
        }

        /**
         * @return $this
         * @see V::defaultNotEmpty
         * @experimental
         */
        public function defaultNotEmpty(): self
        {
        }

        /**
         * @return $this
         * @see V::defaultAllowEmpty
         */
        public function defaultAllowEmpty(): self
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAll::__invoke
         */
        public function all($key = null, string $label = null, array $rules = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAll::__invoke
         */
        public function notAll($key = null, string $label = null, array $rules = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllOf::__invoke
         */
        public function allOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllOf::__invoke
         */
        public function notAllOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllow::__invoke
         */
        public function allow($key = null, string $label = null, ...$values)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllow::__invoke
         */
        public function notAllow($key = null, string $label = null, ...$values)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllowEmpty::__invoke
         */
        public function allowEmpty($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAllowEmpty::__invoke
         */
        public function notAllowEmpty($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlnum::__invoke
         */
        public function alnum($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlnum::__invoke
         */
        public function notAlnum($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlpha::__invoke
         */
        public function alpha($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAlpha::__invoke
         */
        public function notAlpha($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAnyDateTime::__invoke
         */
        public function anyDateTime($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsAnyDateTime::__invoke
         */
        public function notAnyDateTime($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsArray::__invoke
         */
        public function array($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsArray::__invoke
         */
        public function notArray($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBetween::__invoke
         */
        public function between($key = null, string $label = null, $min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBetween::__invoke
         */
        public function notBetween($key = null, string $label = null, $min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBigInt::__invoke
         */
        public function bigInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBigInt::__invoke
         */
        public function notBigInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBlank::__invoke
         */
        public function blank($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBlank::__invoke
         */
        public function notBlank($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBool::__invoke
         */
        public function bool($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBool::__invoke
         */
        public function notBool($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBoolable::__invoke
         */
        public function boolable($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsBoolable::__invoke
         */
        public function notBoolable($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCallback::__invoke
         */
        public function callback($key = null, string $label = null, $fn = null, $message = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCallback::__invoke
         */
        public function notCallback($key = null, string $label = null, $fn = null, $message = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChar::__invoke
         */
        public function char($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChar::__invoke
         */
        public function notChar($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChildren::__invoke
         */
        public function children($key = null, string $label = null, V $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChildren::__invoke
         */
        public function notChildren($key = null, string $label = null, V $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChinese::__invoke
         */
        public function chinese($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsChinese::__invoke
         */
        public function notChinese($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsColor::__invoke
         */
        public function color($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsColor::__invoke
         */
        public function notColor($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsContains::__invoke
         */
        public function contains($key = null, string $label = null, $search = null, $regex = false)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsContains::__invoke
         */
        public function notContains($key = null, string $label = null, $search = null, $regex = false)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCreditCard::__invoke
         */
        public function creditCard($key = null, string $label = null, $type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsCreditCard::__invoke
         */
        public function notCreditCard($key = null, string $label = null, $type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDate::__invoke
         */
        public function date($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDate::__invoke
         */
        public function notDate($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDateTime::__invoke
         */
        public function dateTime($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDateTime::__invoke
         */
        public function notDateTime($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDecimal::__invoke
         */
        public function decimal($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDecimal::__invoke
         */
        public function notDecimal($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDefaultInt::__invoke
         */
        public function defaultInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDefaultInt::__invoke
         */
        public function notDefaultInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDigit::__invoke
         */
        public function digit($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDigit::__invoke
         */
        public function notDigit($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDir::__invoke
         */
        public function dir($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDir::__invoke
         */
        public function notDir($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDivisibleBy::__invoke
         */
        public function divisibleBy($key = null, string $label = null, $divisor = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDivisibleBy::__invoke
         */
        public function notDivisibleBy($key = null, string $label = null, $divisor = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDoubleByte::__invoke
         */
        public function doubleByte($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsDoubleByte::__invoke
         */
        public function notDoubleByte($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEach::__invoke
         */
        public function each($key = null, string $label = null, $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEach::__invoke
         */
        public function notEach($key = null, string $label = null, $v = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmail::__invoke
         */
        public function email($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmail::__invoke
         */
        public function notEmail($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmpty::__invoke
         */
        public function empty($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEmpty::__invoke
         */
        public function notEmpty($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEndsWith::__invoke
         */
        public function endsWith($key = null, string $label = null, $findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEndsWith::__invoke
         */
        public function notEndsWith($key = null, string $label = null, $findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEqualTo::__invoke
         */
        public function equalTo($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsEqualTo::__invoke
         */
        public function notEqualTo($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsExists::__invoke
         */
        public function exists($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsExists::__invoke
         */
        public function notExists($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFieldExists::__invoke
         */
        public function fieldExists($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFieldExists::__invoke
         */
        public function notFieldExists($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFile::__invoke
         */
        public function file($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFile::__invoke
         */
        public function notFile($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFloat::__invoke
         */
        public function float($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsFloat::__invoke
         */
        public function notFloat($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThan::__invoke
         */
        public function greaterThan($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThan::__invoke
         */
        public function notGreaterThan($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThanOrEqual::__invoke
         */
        public function greaterThanOrEqual($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGreaterThanOrEqual::__invoke
         */
        public function notGreaterThanOrEqual($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGt::__invoke
         */
        public function gt($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGt::__invoke
         */
        public function notGt($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGte::__invoke
         */
        public function gte($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsGte::__invoke
         */
        public function notGte($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardCn::__invoke
         */
        public function idCardCn($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardCn::__invoke
         */
        public function notIdCardCn($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardHk::__invoke
         */
        public function idCardHk($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardHk::__invoke
         */
        public function notIdCardHk($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardMo::__invoke
         */
        public function idCardMo($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardMo::__invoke
         */
        public function notIdCardMo($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardTw::__invoke
         */
        public function idCardTw($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdCardTw::__invoke
         */
        public function notIdCardTw($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdenticalTo::__invoke
         */
        public function identicalTo($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIdenticalTo::__invoke
         */
        public function notIdenticalTo($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsImage::__invoke
         */
        public function image($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsImage::__invoke
         */
        public function notImage($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIn::__invoke
         */
        public function in($key = null, string $label = null, $array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIn::__invoke
         */
        public function notIn($key = null, string $label = null, $array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsInt::__invoke
         */
        public function int($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsInt::__invoke
         */
        public function notInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIp::__invoke
         */
        public function ip($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsIp::__invoke
         */
        public function notIp($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLength::__invoke
         */
        public function length($key = null, string $label = null, $min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLength::__invoke
         */
        public function notLength($key = null, string $label = null, $min = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThan::__invoke
         */
        public function lessThan($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThan::__invoke
         */
        public function notLessThan($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThanOrEqual::__invoke
         */
        public function lessThanOrEqual($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLessThanOrEqual::__invoke
         */
        public function notLessThanOrEqual($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLowercase::__invoke
         */
        public function lowercase($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLowercase::__invoke
         */
        public function notLowercase($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLt::__invoke
         */
        public function lt($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLt::__invoke
         */
        public function notLt($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLte::__invoke
         */
        public function lte($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLte::__invoke
         */
        public function notLte($key = null, string $label = null, $value = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLuhn::__invoke
         */
        public function luhn($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsLuhn::__invoke
         */
        public function notLuhn($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxAccuracy::__invoke
         */
        public function maxAccuracy($key = null, string $label = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxAccuracy::__invoke
         */
        public function notMaxAccuracy($key = null, string $label = null, $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxCharLength::__invoke
         */
        public function maxCharLength($key = null, string $label = null, $max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxCharLength::__invoke
         */
        public function notMaxCharLength($key = null, string $label = null, $max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxLength::__invoke
         */
        public function maxLength($key = null, string $label = null, $max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMaxLength::__invoke
         */
        public function notMaxLength($key = null, string $label = null, $max = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumInt::__invoke
         */
        public function mediumInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumInt::__invoke
         */
        public function notMediumInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumText::__invoke
         */
        public function mediumText($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMediumText::__invoke
         */
        public function notMediumText($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinCharLength::__invoke
         */
        public function minCharLength($key = null, string $label = null, $min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinCharLength::__invoke
         */
        public function notMinCharLength($key = null, string $label = null, $min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinLength::__invoke
         */
        public function minLength($key = null, string $label = null, $min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMinLength::__invoke
         */
        public function notMinLength($key = null, string $label = null, $min = null, $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMobileCn::__invoke
         */
        public function mobileCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsMobileCn::__invoke
         */
        public function notMobileCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNaturalNumber::__invoke
         */
        public function naturalNumber($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNaturalNumber::__invoke
         */
        public function notNaturalNumber($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNoneOf::__invoke
         */
        public function noneOf($key = null, string $label = null, array $rules = [], $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNoneOf::__invoke
         */
        public function notNoneOf($key = null, string $label = null, array $rules = [], $ignore = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNullType::__invoke
         */
        public function nullType($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNullType::__invoke
         */
        public function notNullType($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNumber::__invoke
         */
        public function number($key = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsNumber::__invoke
         */
        public function notNumber($key = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsObject::__invoke
         */
        public function object($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsObject::__invoke
         */
        public function notObject($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsOneOf::__invoke
         */
        public function oneOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsOneOf::__invoke
         */
        public function notOneOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPassword::__invoke
         */
        public function password($key = null, string $label = null, array $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPassword::__invoke
         */
        public function notPassword($key = null, string $label = null, array $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhone::__invoke
         */
        public function phone($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhone::__invoke
         */
        public function notPhone($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhoneCn::__invoke
         */
        public function phoneCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPhoneCn::__invoke
         */
        public function notPhoneCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPlateNumberCn::__invoke
         */
        public function plateNumberCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPlateNumberCn::__invoke
         */
        public function notPlateNumberCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPositiveInteger::__invoke
         */
        public function positiveInteger($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPositiveInteger::__invoke
         */
        public function notPositiveInteger($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPostcodeCn::__invoke
         */
        public function postcodeCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPostcodeCn::__invoke
         */
        public function notPostcodeCn($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPresent::__invoke
         */
        public function present($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsPresent::__invoke
         */
        public function notPresent($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsQQ::__invoke
         */
        public function qQ($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsQQ::__invoke
         */
        public function notQQ($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRecordExists::__invoke
         */
        public function recordExists($key = null, string $label = null, $table = null, $field = 'id')
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRecordExists::__invoke
         */
        public function notRecordExists($key = null, string $label = null, $table = null, $field = 'id')
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRegex::__invoke
         */
        public function regex($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRegex::__invoke
         */
        public function notRegex($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRequired::__invoke
         */
        public function required($key = null, string $label = null, $required = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsRequired::__invoke
         */
        public function notRequired($key = null, string $label = null, $required = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSmallInt::__invoke
         */
        public function smallInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSmallInt::__invoke
         */
        public function notSmallInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSomeOf::__invoke
         */
        public function someOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsSomeOf::__invoke
         */
        public function notSomeOf($key = null, string $label = null, array $rules = [], $atLeast = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsStartsWith::__invoke
         */
        public function startsWith($key = null, string $label = null, $findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsStartsWith::__invoke
         */
        public function notStartsWith($key = null, string $label = null, $findMe = null, $case = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsString::__invoke
         */
        public function string($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsString::__invoke
         */
        public function notString($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsText::__invoke
         */
        public function text($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsText::__invoke
         */
        public function notText($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTime::__invoke
         */
        public function time($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTime::__invoke
         */
        public function notTime($key = null, string $label = null, $format = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyChar::__invoke
         */
        public function tinyChar($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyChar::__invoke
         */
        public function notTinyChar($key = null, string $label = null, int $minLength = null, int $maxLength = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyInt::__invoke
         */
        public function tinyInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTinyInt::__invoke
         */
        public function notTinyInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTld::__invoke
         */
        public function tld($key = null, string $label = null, $array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsTld::__invoke
         */
        public function notTld($key = null, string $label = null, $array = [], $strict = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsType::__invoke
         */
        public function type($key = null, string $label = null, $type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsType::__invoke
         */
        public function notType($key = null, string $label = null, $type = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUBigInt::__invoke
         */
        public function uBigInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUBigInt::__invoke
         */
        public function notUBigInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUDefaultInt::__invoke
         */
        public function uDefaultInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUDefaultInt::__invoke
         */
        public function notUDefaultInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUMediumInt::__invoke
         */
        public function uMediumInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUMediumInt::__invoke
         */
        public function notUMediumInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUNumber::__invoke
         */
        public function uNumber($key = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUNumber::__invoke
         */
        public function notUNumber($key = null, string $label = null, int $precision = null, int $scale = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUSmallInt::__invoke
         */
        public function uSmallInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUSmallInt::__invoke
         */
        public function notUSmallInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUTinyInt::__invoke
         */
        public function uTinyInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUTinyInt::__invoke
         */
        public function notUTinyInt($key = null, string $label = null, int $min = null, int $max = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUppercase::__invoke
         */
        public function uppercase($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUppercase::__invoke
         */
        public function notUppercase($key = null, string $label = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUrl::__invoke
         */
        public function url($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUrl::__invoke
         */
        public function notUrl($key = null, string $label = null, $options = [])
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUuid::__invoke
         */
        public function uuid($key = null, string $label = null, $pattern = null)
        {
        }

        /**
         * @return $this
         * @see \Wei\IsUuid::__invoke
         */
        public function notUuid($key = null, string $label = null, $pattern = null)
        {
        }
    }

    class Validate
    {
    }

    class View
    {
    }

    class WeChatApp
    {
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
    }
}
