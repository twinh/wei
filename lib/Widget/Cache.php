<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget proxy
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Cache extends BaseCache
{
    /**
     * The storage widget object
     *
     * @var BaseCache
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

        if (!is_subclass_of($class, 'Widget\BaseCache')) {
            throw new \InvalidArgumentException(sprintf('Cache driver class "%s" must extend "Widget\Stdlib\AbstractCache"', $class));
        }

        $this->driver = $driver;

        $this->object = $this->widget->get($driver);

        return $this;
    }

    /**
     * Returns current cache driver
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
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
     */
    protected function doRemove($key)
    {
        return $this->object->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        return $this->object->exists($key);
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
        return $this->object->incr($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->clear();
    }
}
