<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Memcached
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Memcached extends WidgetProvider
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
        $this->option($options, array('object', 'servers'));
        
        parent::__construct();
    }
    
    /**
     * Set servers
     * 
     * @param array $servers
     * @return \Widget\Memcached
     */
    public function setServers($servers)
    {
        $this->object->addServers($servers);

        $this->servers = $servers;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $options = null)
    {
        return $this->object->get($key, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->set($key, $value, $expire);
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
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->replace($key, $value, $expire);
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
     * @return \Widget\Memcached
     */
    public function setObject(\Memcached $object = null)
    {
        if ($object) {
            $this->object = $object;
        } else {
            $this->object = new \Memcached;
        }
        
        return $this;
    }
}
