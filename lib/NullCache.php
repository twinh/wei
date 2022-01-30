<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * NullCache always returns false when reading and true when writing,
 * mainly for testing purposes or scenarios that want to remove the cache
 */
class NullCache extends BaseCache
{
    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function has(string $key): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        return true;
    }
}
