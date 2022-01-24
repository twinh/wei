<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stores data in MongoDB
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MongoCache extends BaseCache
{
    private const OK_CODE = 1.0;

    /**
     * The host connect to MongoDB
     *
     * @var string
     */
    protected $host = 'localhost';

    /**
     * The port connect to MongoDB
     *
     * @var int
     */
    protected $port = 27017;

    /**
     * The MongoDb collection object
     *
     * @var \MongoCollection
     */
    protected $object;

    /**
     * The database stores cache data
     *
     * @var string
     */
    protected $db = 'cache';

    /**
     * The collection stores cache data
     *
     * @var string
     */
    protected $collection = 'cache';

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
            $mongo = new \MongoClient($this->host . ':' . $this->port);
            $this->object = $mongo->selectCollection($this->db, $this->collection);
        }
    }

    /**
     * Get couchbase object
     *
     * @return \MongoCollection
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set couchbase object
     *
     * @param \MongoCollection $object
     * @return $this
     */
    public function setObject(\MongoCollection $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function get($key, $default = null)
    {
        $result = $this->object->findOne(['_id' => $this->namespace . $key], ['value', 'expire']);
        if (null === $result || $result['expire'] < time()) {
            $result = $this->getDefault($default);
        } else {
            $result = unserialize($result['value']);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        $result = $this->object->save([
            '_id' => $this->namespace . $key,
            'value' => serialize($value),
            'expire' => $expire ? time() + $expire : 2147483647,
            'lastModified' => time(),
        ]);
        return self::OK_CODE === $result['ok'];
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(string $key): bool
    {
        $result = $this->object->remove(['_id' => $this->namespace . $key]);
        return 1 === $result['n'];
    }

    /**
     * {@inheritdoc}
     */
    protected function has(string $key): bool
    {
        $result = $this->object->findOne(['_id' => $this->namespace . $key], ['expire']);
        if (null === $result || $result['expire'] < time()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        if ($this->has($key)) {
            return false;
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        if ($this->has($key)) {
            return $this->set($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        $value = $this->get($key) + $offset;
        return $this->set($key, $value) ? $value : false;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return $this->object->remove();
    }
}
