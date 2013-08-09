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
 * A cache widget base on Couchbase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/couchbase/php-ext-couchbase
 */
class Couchbase extends AbstractCache
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
    protected $persistent = true;

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
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->object = new \Couchbase($this->hosts, $this->user, $this->password, $this->bucket, $this->persistent);
        }
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
        return $this->object->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
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
    protected function doAdd($key, $value, $expire = 0)
    {
        return (bool)$this->object->add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        return (bool)$this->object->replace($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doInc($key, $offset = 1)
    {
        return $this->object->inc($key, $offset, true, 0, $offset);
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
            $result = (bool)$result;
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
     * @return Couchbase
     */
    public function setObject(\Couchbase $object)
    {
        $this->object = $object;

        return $this;
    }
}
