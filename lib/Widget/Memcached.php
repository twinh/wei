<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget that stored data in Memcached
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
    protected $servers = array(
        array(
            'host'          => '127.0.0.1',
            'port'          => 11211,
        )
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->object = new \Memcached;
        }
        $this->object->addServers($this->servers);
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
        $keysWithPrefix = array_map(array($this, 'getKeyWithPrefix'), $keys);
        $values = $this->object->getMulti($keysWithPrefix, $cas, \Memcached::GET_PRESERVE_ORDER);
        return array_combine($keys, $values);
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        return $this->object->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        if ($this->object->add($key, true)) {
            $this->object->delete($key);
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        return $this->object->add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        return $this->object->replace($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doIncr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function decr($key, $offset = 1)
    {
        return $this->incDec($this->getKeyWithPrefix($key), $offset, $offset < 0);
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
        $method = $inc ? 'increment' : 'decrement';
        $offset = abs($offset);
        if (false === $this->object->$method($key, $offset)) {
            return $this->object->set($key, $offset) ? $offset : false;
        }
        return $this->object->get($key);
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
     * @return Memcached
     */
    public function setObject(\Memcached $object)
    {
        $this->object = $object;
        return $this;
    }
}
