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
    protected $providers = [
        'front' => 'arrayCache',
        'back' => 'apc',
    ];

    /**
     * {@inheritdoc}
     */
    protected function doGet(string $key): array
    {
        $result = $this->front->get($key);
        if ($this->front->isHit()) {
            return [$result, true];
        }

        $result = $this->back->get($key);
        if ($this->back->isHit()) {
            $this->front->set($key, $result);
            return [$result, true];
        }

        return [null, false];
    }

    /**
     * First write data to front cache (eg local cache), then write to back cache (eg memcache)
     *
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        $this->front->set($key, $value, $expire);
        return $this->back->set($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        $result1 = $this->front->delete($key);
        $result2 = $this->back->delete($key);

        return $result1 && $result2;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
    {
        return $this->front->has($key) || $this->back->has($key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        $result = $this->back->add($key, $value, $expire);
        if ($result) {
            $this->front->set($key, $value, $expire);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        $result = $this->back->replace($key, $value, $expire);
        if ($result) {
            $this->front->set($key, $value, $expire);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        $result = $this->back->incr($key, $offset);
        if ($result) {
            $this->front->set($key, $result);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        $result1 = $this->front->clear();
        $result2 = $this->back->clear();

        return $result1 && $result2;
    }

    /**
     * Return the front cache object
     *
     * @return BaseCache
     * @svc
     */
    protected function getFront(): BaseCache
    {
        return $this->front;
    }

    /**
     * Return the back cache object
     *
     * @return BaseCache
     * @svc
     */
    protected function getBack(): BaseCache
    {
        return $this->back;
    }
}
