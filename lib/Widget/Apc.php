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
 * A cache widget stored data in PHP APC
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Apc extends AbstractCache
{
    /**
     * {@inheritdoc}
     */
    protected function doGet($key)
    {
        return apc_fetch($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $expire >= 0 ? apc_store($key, $value, $expire) : apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return apc_add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        if (apc_exists($key)) {
            return apc_store($key, $value, $expire);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
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
