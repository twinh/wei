<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service proxy
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Cache extends BaseCache
{
    /**
     * The storage service object
     *
     * @var BaseCache
     */
    protected $object;

    /**
     * The storage service name
     *
     * @ver string
     */
    protected $driver = 'apc';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->setDriver($this->driver);
        }
    }

    /**
     * Set cache driver
     *
     * @param string $driver
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setDriver($driver)
    {
        $class = $this->wei->getClass($driver);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cache driver class "%s" not found', $class));
        }

        if (!is_subclass_of($class, 'Wei\BaseCache')) {
            throw new \InvalidArgumentException(sprintf('Cache driver class "%s" must extend "Wei\BaseCache"', $class));
        }

        $this->driver = $driver;
        $this->object = $this->wei->get($driver);
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
    public function get($key, $expire = null, $fn = null)
    {
        $result = $this->object->get($key);
        return $this->processGetResult($key, $result, $expire, $fn);
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
    public function incr($key, $offset = 1)
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
