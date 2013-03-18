<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Cache\AbstractCache;

/**
 * The redis cache widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Redis extends AbstractCache
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
     * The options for \Redis::setOptions()
     * 
     * @var array
     */
    protected $options = array(
        \Redis::OPT_SERIALIZER => \Redis::SERIALIZER_IGBINARY
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        // force the constructor call "setObject" to init the redis object
        !array_key_exists('object', $options);
        $options['object'] = $this->object;

        parent::__construct($options);

        $this->connect();
    }

    /**
     * Connet the redis server and set redis options
     *
     * @return bool true on success, false on error
     */
    public function connect()
    {
        $connect = $this->persistent ? 'pconnect' : 'connect';

        $result = $this->object->$connect($this->host, $this->port, $this->timeout);
        
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
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->object->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->object->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        $result = $this->object->setnx($key, $value);
        if (true === $result) {
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
        if (false === $this->object->get($key)) {
            return false;
        }
        return $this->object->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        return $this->object->incrBy($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->object->decrBy($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return (bool)$this->object->del($key);
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
     * @param null|\Redis $object
     */
    public function setObject(\Redis $object = null)
    {
        if ($object) {
            $this->object = $object;
        } else {
            $this->object = new \Redis;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return $this->object->exists($key);
    }
}
