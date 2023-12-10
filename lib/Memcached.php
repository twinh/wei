<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in Memcached
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Memcached extends BaseCache
{
    /**
     * The memcached object
     *
     * @var \Memcached
     */
    protected $object;

    /**
     * The memcached server configurations
     *
     * @var array
     * @see \Memcached::addServers
     */
    protected $servers = [
        [
            'host' => '127.0.0.1',
            'port' => 11211,
        ],
    ];

    /**
     * Constructor
     *
     * @param array $options
     * @SuppressWarnings(PHPMD.ConstructorNewOperator)
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->object = new \Memcached();
        }
        $this->object->addServers($this->servers);
    }

    /**
     * Returns the memcached object, retrieve or store an item
     *
     * @param string $key The name of item
     * @param mixed $value The value of item
     * @param int $expire The expire seconds, defaults to 0, means never expired
     * @return mixed
     */
    public function __invoke($key = null, $value = null, $expire = 0)
    {
        switch (func_num_args()) {
            case 0:
                return $this->object;
            case 1:
                return $this->get($key);
            default:
                return $this->set($key, $value, $expire);
        }
    }

    /**
     * Get memcached object
     *
     * @return \Memcached
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set memcached object
     *
     * @param \Memcached $object
     * @return $this
     */
    public function setObject(\Memcached $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultCode()
    {
        return $this->object->getResultCode();
    }

    /**
     * {@inheritdoc}
     */
    protected function doGet(string $key): array
    {
        $result = $this->object->get($this->namespace . $key);
        $isHit = \Memcached::RES_SUCCESS === $this->object->getResultCode();
        return [$isHit ? $result : null, $isHit];
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        return $this->object->set($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        return $this->object->delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
    {
        $key = $this->namespace . $key;
        if ($this->object->add($key, true)) {
            $this->object->delete($key);
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        return $this->object->add($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        return $this->object->replace($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset > 0);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function decr($key, $offset = 1)
    {
        return $this->incDec($key, $offset, $offset < 0);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return $this->object->flush();
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function getMultiple(iterable $keys, $default = null): iterable
    {
        $keys = $this->iterableToArray($keys);

        $keysWithPrefix = [];
        foreach ($keys as $key) {
            $keysWithPrefix[] = $this->namespace . $key;
        }

        $caches = $this->object->getMulti($keys);

        $this->hits = [];
        $values = [];
        foreach ($keysWithPrefix as $i => $key) {
            $isHit = array_key_exists($key, $caches);
            $this->hits[$keys[$i]] = $isHit;
            $values[$keys[$i]] = $isHit ? $caches[$key] : $default;
        }

        return $values;
    }

    /**
     * Increment/Decrement an item
     *
     * Memcached do not allow negative number as $offset parameter
     *
     * @link https://github.com/php-memcached-dev/php-memcached/blob/master/php_memcached.c#L1746
     * @param string $key The name of item
     * @param int $offset The value to be increased/decreased
     * @param bool $inc The operation is increase or decrease
     * @return int|false Returns the new value on success, or false on failure
     */
    protected function incDec($key, $offset, $inc = true)
    {
        $key = $this->namespace . $key;
        $method = $inc ? 'increment' : 'decrement';
        $offset = abs($offset);
        if (false === $this->object->{$method}($key, $offset)) {
            return $this->object->set($key, $offset) ? $offset : false;
        }
        return $this->object->get($key);
    }

    /**
     * Convert iterable type to array
     *
     * @param iterable $iterable
     * @return array
     * @internal
     */
    protected function iterableToArray(iterable $iterable): array
    {
        if (is_array($iterable)) {
            return $iterable;
        }
        return iterator_to_array((static function () use ($iterable) {
            yield from $iterable;
        })());
    }
}
