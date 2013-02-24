<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Bicache
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\Storable $master The master(faster) cache object
 * @property \Widget\Storable $slave The slave(slower) cache object
 */
class Bicache extends AbstractWidget implements Storable
{
    /**
     * @var array
     */
    protected $deps = array(
        'master' => 'memcache',
        'slave' => 'fcache',
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
    public function get($key, $options = null)
    {
        $value = $this->master->get($key);

        if (false !== $value) {
            return $value;
        } else {
            return $this->slave->get($key);
        }
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        $result = $this->master->set($key, $value, $expire, $options);

        if ($this->needUpdate($key)) {
            $result = $this->slave->set($key, $value, $expire, $options);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        $result = $this->master->add($key, $value, $expire, $options);

        // The key could be only added one time, when added successed, set to the slave cache
        if ($result) {
            $result = $this->slave->set($key, $value, $expire, $options);
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
        $result = $this->master->decrement($key, $offset);

        if ($this->needUpdate($key)) {
            $result = $this->slave->set($key, $this->master->get($key));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        $result = $this->master->replace($key, $value, $options);

        if ($this->needUpdate($key)) {
            $result = $this->slave->set($key, $value, $options);
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

    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }
}
