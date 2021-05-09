<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use ReflectionMethod;

/**
 * A cache service that stored data in Memcached
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Memcached extends BaseCache
{
    /**
     * The memcached object
     *
     * @var \Memcached
     */
    protected $object;

    /**
     * The memcached server configurations
     *
     * @var array
     * @see \Memcached::addServers
     */
    protected $servers = [
        [
            'host' => '127.0.0.1',
            'port' => 11211,
        ],
    ];

    /**
     * Whether memcached version is >= 3.0.0
     *
     * @var bool
     * @link https://github.com/php-memcached-dev/php-memcached/issues/229
     * @link https://github.com/laravel/framework/pull/15739
     */
    protected $isMemcached3;

    /**
     * Constructor
     *
     * @param array $options
     * @SuppressWarnings(PHPMD.ConstructorNewOperator)
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->object = new \Memcached();
        }
        $this->object->addServers($this->servers);

        $method = new ReflectionMethod('Memcached', 'getMulti');
        $this->isMemcached3 = 2 === $method->getNumberOfParameters();
    }

    /**
     * Returns the memcached object, retrieve or store an item
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
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        $result = $this->object->get($this->namespace . $key);
        return $this->processGetResult($key, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->object->set($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     *
     * Note: setMulti method is not reimplemented for it returning only one
     * "true" or "false" for all items
     *
     * @link http://www.php.net/manual/en/memcached.setmulti.php
     * @link https://github.com/php-memcached-dev/php-memcached/blob/master/php_memcached.c#L1219
     */
    public function getMulti(array $keys)
    {
        $cas = null;
        $keysWithPrefix = [];
        foreach ($keys as $key) {
            $keysWithPrefix[] = $this->namespace . $key;
        }

        if ($this->isMemcached3) {
            $params = [$keysWithPrefix, \Memcached::GET_PRESERVE_ORDER];
        } else {
            $params = [$keysWithPrefix, $cas, \Memcached::GET_PRESERVE_ORDER];
        }
        $values = (array) call_user_func_array([$this->object, 'getMulti'], $params);
        return array_combine($keys, $values);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->object->delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $key = $this->namespace . $key;
        if ($this->object->add($key, true)) {
            $this->object->delete($key);
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return $this->object->add($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        return $this->object->replace($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function decr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset < 0);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->flush();
    }

    /**
     * Get memcached object
     *
     * @return \Memcached
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set memcached object
     *
     * @param \Memcached $object
     * @return $this
     */
    public function setObject(\Memcached $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultCode()
    {
        return $this->object->getResultCode();
    }

    /**
     * Increment/Decrement an item
     *
     * Memcached do not allow negative number as $offset parameter
     *
     * @link https://github.com/php-memcached-dev/php-memcached/blob/master/php_memcached.c#L1746
     * @param string $key The name of item
     * @param int $offset The value to be increased/decreased
     * @param bool $inc The operation is increase or decrease
     * @return int|false Returns the new value on success, or false on failure
     */
    protected function incDec($key, $offset, $inc = true)
    {
        $key = $this->namespace . $key;
        $method = $inc ? 'increment' : 'decrement';
        $offset = abs($offset);
        if (false === $this->object->{$method}($key, $offset)) {
            return $this->object->set($key, $offset) ? $offset : false;
        }
        return $this->object->get($key);
    }
}
