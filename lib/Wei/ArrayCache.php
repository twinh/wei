<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache wei that stored data in PHP array
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class ArrayCache extends BaseCache
{
    /**
     * The array to store cache items
     *
     * @var array
     */
    protected $data = array();

    /**
     * {@inheritdoc}
     */
    protected function doGet($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : false;
    }

    /**
     * {@inheritdoc}
     */
    protected function doSet($key, $value, $expire = 0)
    {
        $this->data[$key] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doRemove($key)
    {
        unset($this->data[$key]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        if ($this->doExists($key)) {
            return false;
        } else {
            $this->data[$key] = $value;
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        if (!$this->doExists($key)) {
            return false;
        } else {
            $this->data[$key] = $value;
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doIncr($key, $offset = 1)
    {
        if ($this->doExists($key)) {
            return $this->data[$key] += $offset;
        } else {
            return $this->data[$key] = $offset;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = array();
        return true;
    }
}
