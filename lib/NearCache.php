<?php

namespace Wei;

/**
 * A kind of two level cache, including a "front cache" and a "back cache"
 *
 * Naming from http://docs.oracle.com/cd/E24290_01/coh.371/e22840/nearcache.htm#COHGS227
 *
 * @property    BaseCache $front The front cache service
 * @property    BaseCache $back The back cache service
 */
class NearCache extends BaseCache
{
    /**
     * @var array
     */
    protected $providers = array(
        'front' => 'arrayCache',
        'back' => 'apc',
    );

    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        $result = $this->front->get($key);
        if (false === $result) {
            $result = $this->back->get($key);
            if (false !== $result) {
                $this->front->set($key, $result, $expire);
            }
        }
        return $this->processGetResult($key, $result, $expire, $fn);
    }

    /**
     * First write data to front cache (eg local cache), then write to back cache (eg memcache)
     *
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $this->front->set($key, $value, $expire);
        return $this->back->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $result1 = $this->front->remove($key);
        $result2 = $this->back->remove($key);

        return $result1 && $result2;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return $this->front->exists($key) || $this->back->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        $result = $this->back->add($key, $value, $expire);
        if ($result) {
            $this->front->set($key, $value, $expire);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        $result = $this->back->replace($key, $value, $expire);
        if ($result) {
            $this->front->set($key, $value, $expire);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        $result = $this->back->incr($key, $offset);
        if ($result) {
            $this->front->set($key, $result);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $result1 = $this->front->clear();
        $result2 = $this->back->clear();

        return $result1 && $result2;
    }
}
