<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in Memcache
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Memcache extends BaseCache
{
    /**
     * The memcache object
     *
     * @var \Memcache
     */
    protected $object;

    /**
     * The memcache server configurations
     *
     * @var array
     * @see \Memcache::addServer
     */
    protected $servers = [
        [
            'host' => '127.0.0.1',
            'port' => 11211,
            'persistent' => false,
        ],
    ];

    /**
     * The flag that use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
     *
     * @var int
     */
    protected $flag = 0;

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
     * Returns the memcache object, retrieve or store an item
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
     * Get memcache object
     *
     * @return \Memcache
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set memcache object
     *
     * @param \Memcache $object
     * @return $this
     */
    public function setObject(\Memcache $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function get($key, $default = null)
    {
        $flags = false;
        $result = $this->object->get($this->namespace . $key, $flags);
        return $flags ? $result : $this->getDefault($default);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        return $this->object->set($this->namespace . $key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        return $this->object->delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
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
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        return $this->object->add($this->namespace . $key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        return $this->object->replace($this->namespace . $key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset > 0);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function decr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset < 0);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return $this->object->flush();
    }

    /**
     * Instance memcache object and connect to server
     */
    protected function connect()
    {
        if (!$this->object) {
            $this->object = new \Memcache();
        }
        foreach ($this->servers as $server) {
            call_user_func_array([$this->object, 'addServer'], $server);
        }
    }

    /**
     * Increment/Decrement an item
     *
     * Compatible method for memcache < 3.0.3 that does not support
     * negative number as $offset parameter
     *
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
        // IMPORTANT: memcache may return 0 in some 3.0.x beta version
        if (false === $this->object->{$method}($key, $offset)) {
            return $this->object->set($key, $offset) ? $offset : false;
        }
        // Convert to int for memcache extension version < 3.0.3
        return (int) $this->object->get($key);
    }
}
