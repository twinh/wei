<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Stdlib\AbstractCache;

/**
 * A two-level cache widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Stdlib\AbstractCache $master The master(faster) cache object
 * @property    Stdlib\AbstractCache $slave The slave(slower) cache object
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
        'slave' => 'fileCache',
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

        if ($result && $this->needUpdate($key)) {
            // $result is true, so return the slave cache result only
            return $this->slave->set($key, $value, $expire);
        }

        // No need to update, return mater result only
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
    public function exists($key)
    {
        return $this->master->exists($key) || $this->slave->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        $result = $this->master->add($key, $value, $expire);

        // The cache can be added only one time, when added success, set it to the slave cache
        if ($result) {
            // $result is true, so return the slave cache result only
            return $this->slave->set($key, $value, $expire);
        }

        // false
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        $result = $this->master->replace($key, $value, $expire);

        // The cache can always be replaced when it's exists, so check for update
        if ($result && $this->needUpdate($key)) {
            return $this->slave->set($key, $value, $expire);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        $result = $this->master->increment($key, $offset);

        if (false !== $result && $this->needUpdate($key)) {
            return $this->slave->set($key, $result) ? $result : false;
        }

        return $result;
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
