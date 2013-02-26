<?php

/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Exception\InvalidArgumentException;

/**
 * Cache
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Cache extends AbstractWidget implements Storable
{
    /**
     * The storable widget object
     *
     * @var Widget\Storable
     */
    protected $object;

    /**
     * The storable widget name
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
     * Set cache driver
     *
     * @param string $driver
     * @return Cache
     * @throws Exception
     */
    public function setDriver($driver)
    {
        $class = $this->widget->getClass($driver);

        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Cache driver class "%s" not found', $class));
        }

        if (!in_array('Widget\Storable', class_implements($class))) {
            throw new InvalidArgumentException(sprintf('Cache driver "%s" should implement the interface "Widget\Storable"', $class));
        }

        $this->object = $this->widget->get($driver);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->add($key, $options, $expire, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $step = 1)
    {
        return $this->object->decrement($key, $step);
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
    public function increment($key, $step = 1)
    {
        return $this->object->increment($key, $step);
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
     * @param array $options
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->replace($key, $options, $expire, $options);
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        return $this->object->set($key, $value, $expire, $options);
    }
}
