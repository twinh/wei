<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in PHP array
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
    public function get($key, $expire = null, $fn = null)
    {
        $oriKey = $key;
        $key = $this->prefix . $key;
        $result = array_key_exists($key, $this->data) ? $this->data[$key] : false;
        return $this->processGetResult($oriKey, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $this->data[$this->prefix . $key] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (isset($this->data[$this->prefix . $key])) {
            unset($this->data[$this->prefix . $key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_key_exists($this->prefix . $key, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        if ($this->exists($key)) {
            return false;
        } else {
            return $this->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        if (!$this->exists($key)) {
            return false;
        } else {
            $this->data[$this->prefix . $key] = $value;
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        if ($this->exists($key)) {
            return $this->data[$this->prefix . $key] += $offset;
        } else {
            return $this->data[$this->prefix . $key] = $offset;
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
