<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache wei that stored data in PHP APC
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Apc extends BaseCache
{
    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        $result = apc_fetch($this->keyPrefix . $key);
        return $this->processGetResult($key, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $key = $this->keyPrefix . $key;
        return $expire >= 0 ? apc_store($key, $value, $expire) : apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return apc_delete($this->keyPrefix . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return apc_exists($this->keyPrefix . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return apc_add($this->keyPrefix . $key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        $key = $this->keyPrefix . $key;
        if (apc_exists($key)) {
            return apc_store($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        $key = $this->keyPrefix . $key;
        if (false === apc_inc($key, $offset)) {
            return apc_store($key, $offset) ? $offset : false;
        }
        return apc_fetch($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return apc_clear_cache('user');
    }
}
