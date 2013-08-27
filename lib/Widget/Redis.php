<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget that stored data in Redis
 *
 * @author      Twin Huang <twinhuang@qq.com>
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
    protected $persistent = true;

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
    protected $options = array(
        \Redis::OPT_SERIALIZER => \Redis::SERIALIZER_PHP
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
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
    public function __invoke($key, $value = null, $expire = 0)
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

        $this->object = new \Redis;
        $connect = $this->persistent ? 'pconnect' : 'connect';
        $result = $this->object->$connect($this->host, $this->port, $this->timeout);

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
    protected function doGet($key)
    {
        return $this->object->get($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doSet($key, $value, $expire = 0)
    {
        return $this->object->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys)
    {
        $keysWithPrefix = array_map(array($this, 'getKeyWithPrefix'), $keys);
        return array_combine($keys, $this->object->mGet($keysWithPrefix));
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
    public function setMulti(array $items, $expire = 0)
    {
        $keys = array_keys($items);
        $keysWithPrefix = array_map(array($this, 'getKeyWithPrefix'), $keys);
        $items = array_combine($keysWithPrefix, $items);
        $result = $this->object->mset($items);
        return array_combine($keys, array_pad(array(), count($items), $result));
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        return (bool)$this->object->del($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        return $this->object->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        $result = $this->object->setnx($key, $value);
        if (true === $result) {
            $this->object->expire($key, $expire === 0 ? -1 : $expire);
        }
        return $result;
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        if (false === $this->object->get($key)) {
            return false;
        }
        return $this->object->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doIncr($key, $offset = 1)
    {
        return $this->object->incrBy($key, $offset);
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
     * @return Redis
     */
    public function setObject(\Redis $object)
    {
        $this->object = $object;
        return $this;
    }
}
