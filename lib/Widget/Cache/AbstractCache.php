<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache;

use Widget\AbstractWidget;

/**
 * A simple implementation of \Widget\Cache\CacheInterface
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
}
