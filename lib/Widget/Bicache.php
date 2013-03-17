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
 * The two level cache widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Cache\CacheInterface $master The master(faster) cache object
 * @property    \Widget\Cache\CacheInterface $slave The slave(slower) cache object
 */
class Bicache extends AbstractCache
{
    /**
     * The dependence map
     * 
     * @var array
     */
    protected $deps = array(
        'master' => 'apc',
        'slave' => 'file',
    );

    /**
     * The seconds to update the slave cache
     *
     * @var int
     */
    protected $time = 5;
    
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
        $value = $this->master->get($key);
        
        if (false === $value) {
            return $this->slave->get($key);
        }
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $result = $this->master->set($key, $value, $expire);

        if ($this->needUpdate($key)) {
            $result = $this->slave->set($key, $value, $expire);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        $result = $this->master->add($key, $value, $expire);

        // The key could be only added one time, when added successed, set to the slave cache
        if ($result) {
            $result = $this->slave->set($key, $value, $expire);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        $result = $this->master->increment($key, $offset);

        if ($this->needUpdate($key)) {
            $result = $this->slave->set($key, $this->master->get($key));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        $result = $this->master->replace($key, $value, $expire);

        if ($result && $this->needUpdate($key)) {
            $result = $this->slave->set($key, $value);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $result1 = $this->master->remove($key);
        $result2 = $this->slave->remove($key);

        return $result1 && $result2;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $result1 = $this->master->clear();
        $result2 = $this->slave->clear();

        return $result1 && $result2;
    }

    /**
     * Check if the key need to update to the slave cache
     *
     * @param string $key
     */
    protected function needUpdate($key)
    {
        return $this->master->add('__' . $key, true, $this->time);
    }
}
