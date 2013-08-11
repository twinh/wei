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
 * A cache widget that stored data in PHP APC
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
    protected function doSet($key, $value, $expire = 0)
    {
        return $expire >= 0 ? apc_store($key, $value, $expire) : apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        return apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        return apc_add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
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
    protected function doInc($key, $offset = 1)
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
