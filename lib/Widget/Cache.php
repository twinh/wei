<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Exception\InvalidArgumentException;

use Widget\Storage\AbstractStorage;

/**
 * Cache
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Cache extends AbstractStorage
{
    /**
     * The storable widget object
     *
     * @var Widget\Storage\StorageInterface
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

        if (!in_array('Widget\Storage\StorageInterface', class_implements($class))) {
            throw new InvalidArgumentException(sprintf('Cache driver "%s" should implement the interface "Widget\Storage\StorageInterface"', $class));
        }

        $this->object = $this->widget->get($driver);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @param array $options
     */
    public function add($key, $value, $expire = 0)
    {
        return $this->object->add($key, $value, $expire);
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
    public function get($key, &$success = null)
    {
        return $this->object->get($key, $success);
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
    public function replace($key, $value, $expire = 0)
    {
        return $this->object->replace($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->object->set($key, $value, $expire);
    }
}
