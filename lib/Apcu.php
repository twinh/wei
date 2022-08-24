<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in PHP APCu
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Apcu extends BaseCache
{
    /**
     * {@inheritdoc}
     */
    protected function doGet(string $key): array
    {
        $value = apcu_fetch($this->namespace . $key, $isHit);
        return [$value, $isHit];
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        $key = $this->namespace . $key;
        return $expire >= 0 ? apcu_store($key, $value, $expire) : apcu_delete($key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        return apcu_delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
    {
        return apcu_exists($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        return apcu_add($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        $key = $this->namespace . $key;
        if (apcu_exists($key)) {
            return apcu_store($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        $key = $this->namespace . $key;
        $value = apcu_inc($key, $offset, $success);
        if ($success) {
            return $value;
        } else {
            return apcu_store($key, $offset) ? $offset : false;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return apcu_clear_cache();
    }
}
