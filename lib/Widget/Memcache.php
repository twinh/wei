<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget that stored data in Memcache
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
    protected $servers = array(
        array(
            'host'          => '127.0.0.1',
            'port'          => 11211,
            'persistent'    => true
        )
    );

    /**
     * MEMCACHE_COMPRESSED
     *
     * @var int
     */
    protected $flag = 2;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Instance memcache object and connect to server
        if (!$this->object) {
            $this->object = new \Memcache;
        }
        foreach ($this->servers as $server) {
            call_user_func_array(array($this->object, 'addServer'), $server);
        }
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
        return $this->object->set($key, $value, $this->flag, $expire);
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
        return $this->object->add($key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        return $this->object->replace($key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doInc($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function dec($key, $offset = 1)
    {
        return $this->incDec($this->getKeyWithPrefix($key), $offset, $offset < 0);
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
        $method = $inc ? 'increment' : 'decrement';
        $offset = abs($offset);
        // IMPORTANT: memcache may return 0 in some 3.0.x beta version
        if (false === $this->object->$method($key, $offset)) {
            return $this->object->set($key, $offset) ? $offset : false;
        }
        // Convert to int for memcache extension version < 3.0.3
        return (int)$this->object->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->flush();
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
     * @return \Memcache
     */
    public function setObject(\Memcache $object)
    {
        $this->object = $object;
        return $this;
    }
}
