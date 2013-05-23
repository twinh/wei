<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\AbstractWidget;

/**
 * A simple implementation of Cache\CacheInterface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractCache extends AbstractWidget implements CacheInterface
{
    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys)
    {
        $results = array();
        foreach ($keys as $key) {
            $results[$key] = $this->get($key);
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function setMulti(array $items, $expire = 0)
    {
        $results = array();
        foreach ($items as $key => $value)
        {
            $results[$key] = $this->set($key, $value, $expire);
        }
        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * Store callback result in cache for specified times
     *
     * @param callback $fn
     * @param int $expire
     * @param string $key
     */
    public function cached($fn, $expire, $key = null)
    {
        $key = md5(new \ReflectionFunction($fn)) . $key;

        if (false === $result = $this->get($key)) {
            $result = call_user_func($fn);
            $this->set($key, $result, $expire);
        }

        return $result;
    }
}
