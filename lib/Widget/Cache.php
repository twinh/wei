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
 * A cache widget proxy
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Cache extends AbstractCache
{
    /**
     * The storage widget object
     *
     * @var AbstractCache
     */
    protected $object;

    /**
     * The storage widget name
     *
     * @ver string
     */
    protected $driver = 'apc';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options + get_object_vars($this));
    }

    /**
     * Set cache driver
     *
     * @param string $driver
     * @return Cache
     * @throws \InvalidArgumentException
     */
    public function setDriver($driver)
    {
        $class = $this->widget->getClass($driver);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cache driver class "%s" not found', $class));
        }

        if (!is_subclass_of($class, 'Widget\Stdlib\AbstractCache')) {
            throw new \InvalidArgumentException(sprintf('Cache driver class "%s" must extend "Widget\Stdlib\AbstractCache"', $class));
        }

        $this->object = $this->widget->get($driver);

        return $this;
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
    public function remove($key)
    {
        return $this->object->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return $this->object->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return $this->object->add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
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
    public function clear()
    {
        return $this->object->clear();
    }
}
