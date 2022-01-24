<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in PHP APC
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Apc extends BaseCache
{
    /**
     * {@inheritdoc}
     * @svc
     */
    protected function get($key, $default = null)
    {
        $result = apc_fetch($this->namespace . $key, $success);
        return $success ? $result : $this->getDefault($default);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        $key = $this->namespace . $key;
        return $expire >= 0 ? apc_store($key, $value, $expire) : apc_delete($key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        return apc_delete($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
    {
        return apc_exists($this->namespace . $key);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        return apc_add($this->namespace . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        $key = $this->namespace . $key;
        if (apc_exists($key)) {
            return apc_store($key, $value, $expire);
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
        $value = apc_inc($key, $offset, $success);
        if ($success) {
            return $value;
        } else {
            return apc_store($key, $offset) ? $offset : false;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return apc_clear_cache('user');
    }
}
