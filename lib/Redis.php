<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in Redis
 *
 * The methods are derived from code of the Laravel Framework
 *   * serialize
 *   * unserialize
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @see https://github.com/laravel/framework/blob/v8.68.1/src/Illuminate/Cache/RedisStore.php
 * @copyright Copyright (c) Taylor Otwell
 * @license The MIT License (MIT)
 */
class Redis extends BaseCache
{
    /**
     * The redis object
     *
     * @var \Redis
     */
    protected $object;

    /**
     * The host, or the path to a unix domain socket
     *
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * The port of the host
     *
     * @var int
     */
    protected $port = 6379;

    /**
     * The timeout seconds
     *
     * @var float|int
     */
    protected $timeout = 0.0;

    /**
     * Whether persistent connection or not
     *
     * @var bool
     */
    protected $persistent = false;

    /**
     * A password to authenticate the connection
     *
     * @var string
     */
    protected $auth;

    /**
     * The options for \Redis::setOptions()
     *
     * @var array
     */
    protected $options = [];

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->connect();
    }

    /**
     * Returns the redis object, retrieve or store an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return mixed
     */
    public function __invoke($key = null, $value = null, $expire = 0)
    {
        switch (func_num_args()) {
            case 0:
                return $this->object;
            case 1:
                return $this->get($key);
            default:
                return $this->set($key, $value, $expire);
        }
    }

    /**
     * Connect the redis server and set redis options
     *
     * @return bool true on success, false on error
     */
    public function connect()
    {
        if ($this->object) {
            return true;
        }

        $this->object = new \Redis();
        $connect = $this->persistent ? 'pconnect' : 'connect';
        $result = $this->object->{$connect}($this->host, $this->port, $this->timeout);

        if ($result && $this->auth) {
            $result = $this->object->auth($this->auth);
        }

        if ($result) {
            foreach ($this->options as $key => $value) {
                $this->object->setOption($key, $value);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $result = $this->object->get($this->namespace . $key);
        if (false === $result) {
            return $default;
        }
        return $this->unserialize($result);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        // Use null instead of 0 for redis extension 2.2.8, otherwise the key will expire after set
        return $this->object->set($this->namespace . $key, $this->serialize($value), 0 === $expire ? null : $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys)
    {
        $keysWithPrefix = [];
        foreach ($keys as $key) {
            $keysWithPrefix[] = $this->namespace . $key;
        }
        $values = $this->object->mGet($keysWithPrefix);

        $results = [];
        foreach ($values as $index => $value) {
            $results[$keys[$index]] = $this->unserialize($value);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * Note:
     * 1. The "$expire" parameter is not support by redis MSET command
     * 2. The elements in returning values are all true or false, see links for more detail
     *
     * @link http://redis.io/commands/mset
     * @link https://github.com/nicolasff/phpredis/blob/master/redis_array.c#L844
     */
    public function setMulti(array $keys, $expire = 0)
    {
        $values = [];
        foreach ($keys as $key => $value) {
            $values[$this->namespace . $key] = $this->serialize($value);
        }
        $result = $this->object->mset($values);
        return array_combine(array_keys($keys), array_pad([], count($keys), $result));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return (bool) $this->object->del($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        // Redis >= 4.0 returned int, if < 4.0 returned bool
        return (bool) $this->object->exists($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        $key = $this->namespace . $key;
        $result = $this->object->setnx($key, $this->serialize($value));
        if (true === $result && $expire) {
            $this->object->expire($key, $expire);
        }
        return $result;
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        if (false === $this->object->get($this->namespace . $key)) {
            return false;
        }
        return $this->set($key, $this->serialize($value), $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        return $this->object->incrBy($this->namespace . $key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->flushAll();
    }

    /**
     * Get the redis object
     *
     * @return \Redis
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set the redis object
     *
     * @param \Redis $object
     * @return $this
     */
    public function setObject(\Redis $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Serialize the value.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function serialize($value)
    {
        return is_numeric($value) && !in_array($value, [\INF, -\INF], true) && !is_nan($value)
            ? $value : serialize($value);
    }

    /**
     * Unserialize the value.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function unserialize($value)
    {
        return null === $value || is_numeric($value) ? $value : unserialize($value);
    }
}
