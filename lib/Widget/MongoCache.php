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
 * A cache widget base on MongoDB
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class MongoCache extends AbstractCache
{
    protected $host = 'localhost';

    protected $port = 27017;

    protected $object;

    protected $db = 'cache';

    protected $collection = 'cache';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->object) {
            $mongo = new \Mongo($this->host . ':' . $this->port);
            $this->object = $mongo->selectCollection($this->db, $this->collection);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doGet($key)
    {
        $result = $this->object->findOne(array('_id' => $key), array('value', 'expire'));

        if (null === $result || $result['expire'] < time()) {
            return false;
        } else {
            return unserialize($result['value']);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doSet($key, $value, $expire = 0)
    {
        return $this->object->save(array(
            '_id' => $key,
            'value' => serialize($value),
            'expire' => $expire ? time() + $expire : 2147483647,
            'lastModified' => time()
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        return $this->object->remove(array('_id' => $key));;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        $result = $this->object->findOne(array('_id' => $key), array('expire'));

        if (null === $result || $result['expire'] < time()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        if ($this->exists($key)) {
            return false;
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        if ($this->exists($key)) {
            return $this->set($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doInc($key, $offset = 1)
    {
        $result = $this->object->findAndModify(
            array('_id' => $key),
            array('$inc' => array('value' => $offset)),
            array(),
            array('upsert' => true)
        );
        return $result ? $result['value'] + $offset : $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->object->remove();
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
     * @return MongoCache
     */
    public function setObject(\MongoCollection $object)
    {
        $this->object = $object;

        return $this;
    }
}