<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Apc
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Apc extends WidgetProvider implements Storable
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $options = null)
    {
        return apc_fetch($key, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        return apc_store($key, $value, $expire);
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
    public function add($key, $value, $expire = 0, array $options = array())
    {
        return apc_add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        apc_fetch($key, $success);
        if ($success) {
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
        return apc_inc($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return apc_dec($key, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return apc_clear_cache('user');
    }
}
