<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Redis
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Redis extends WidgetProvider implements Storable
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
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->option($options, array('object'));
        
        parent::__construct();

        $this->connect();
    }
    
    /**
     * Connet the redis server
     * 
     * @return bool true on success, false on error
     */
    public function connect()
    {
        $connect = $this->persistent ? 'pconnect' : 'connect';
        
        return $this->object->$connect($this->host, $this->port, $this->timeout);
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
    public function get($key, $options = null)
    {
        return $this->object->get($key);
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
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->setnx($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->getSet($key, $value);
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
        return $this->object->del($key);
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
     */
    public function setObject(\Redis $object = null)
    {
        if ($object) {
            $this->object = $object;
        } else {
            $this->object = new \Redis;
        }
    }
}
