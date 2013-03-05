<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Storage\AbstractStorage;

/**
 * Memcache
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Memcache extends AbstractStorage
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
        $this->setOption($options, array('object', 'servers'));

        parent::__construct();
    }

    /**
     * Set servers
     *
     * @param array $servers
     * @return Memcache
     */
    public function setServers($servers)
    {
        foreach ((array)$servers as $server) {
            call_user_func_array(array($this->object, 'addServer'), $server);
        }

        $this->servers = $servers;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, &$success = null)
    {
        return $this->object->get($key);
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->set($key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->object->delete($key);
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->add($key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->replace($key, $value, $this->flag, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        return $this->object->increment($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->object->decrement($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->flush();
    }

    /**
     * {@inheritdoc}
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
     * @param null|\Memcache $object
     * @return Memcache
     */
    public function setObject(\Memcache $object = null)
    {
        if ($object) {
            $this->object = $object;
        } else {
            $this->object = new \Memcache;
        }

        return $this;
    }
}
