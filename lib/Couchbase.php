<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in Couchbase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/couchbase/php-ext-couchbase
 */
class Couchbase extends BaseCache
{
    /**
     * An array of hostnames[:port] where the Couchbase cluster is running. The
     * port number is optional (and only needed if you're using a non-standard
     * port).
     *
     * @var array|string
     */
    protected $hosts = '127.0.0.1:8091';

    /**
     * The username used for authentication to the cluster
     *
     * @var string
     */
    protected $user;

    /**
     * The password used to authenticate to the cluster
     *
     * @var string
     */
    protected $password;

    /**
     * The name of the bucket to connect to
     *
     * @var string
     */
    protected $bucket = 'default';

    /**
     * If a persistent object should be used or not
     *
     * @var bool
     */
    protected $persistent = false;

    /**
     * The couchbase object
     *
     * @var \Couchbase
     */
    protected $object;

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
            $this->object = new \Couchbase($this->hosts, $this->user, $this->password, $this->bucket, $this->persistent);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        $result = $this->object->get($this->namespace . $key);
        return $this->processGetResult($key, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->object->set($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->object->delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        if ($this->object->add($key, true)) {
            $this->object->delete($key);
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return (bool) $this->object->add($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        return (bool) $this->object->replace($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        return $this->object->inc($this->namespace . $key, $offset, true, 0, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \CouchbaseServerException when flush is disabled for the bucket
     * @link http://www.couchbase.com/docs/couchbase-manual-2.0/couchbase-admin-web-console-data-buckets-createedit.html
     * @link http://www.couchbase.com/docs/couchbase-manual-2.0/couchbase-admin-cli-flushing.html
     */
    public function clear()
    {
        return $this->object->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys)
    {
        return $this->object->getMulti($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function setMulti(array $items, $expire = 0)
    {
        $results = $this->object->setMulti($items, $expire);
        foreach ($results as &$result) {
            $result = (bool) $result;
        }
        return $results;
    }

    /**
     * Get couchbase object
     *
     * @return \Couchbase
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set couchbase object
     *
     * @param \Couchbase $object
     * @return $this
     */
    public function setObject(\Couchbase $object)
    {
        $this->object = $object;
        return $this;
    }
}
