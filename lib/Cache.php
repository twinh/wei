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
    public function get($key, $default = null)
    {
        return $this->object->get($key, $default);
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
    protected function delete(string $key): bool
    {
        return $this->object->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function has(string $key): bool
    {
        return $this->object->has($key);
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

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function getMultiple(iterable $keys, $default = null): iterable
    {
        return $this->object->getMultiple($keys, $default);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function setMultiple(iterable $keys, $ttl = null): bool
    {
        return $this->object->setMultiple($keys, $ttl);
    }
}
