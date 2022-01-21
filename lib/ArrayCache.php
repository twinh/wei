<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
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
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        $oriKey = $key;
        $key = $this->namespace . $key;
        $result = array_key_exists($key, $this->data) ? $this->data[$key] : null;
        return $this->processGetResult($oriKey, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $this->data[$this->namespace . $key] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (isset($this->data[$this->namespace . $key])) {
            unset($this->data[$this->namespace . $key]);
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
        return array_key_exists($this->namespace . $key, $this->data);
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
            $this->data[$this->namespace . $key] = $value;
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        if ($this->exists($key)) {
            return $this->data[$this->namespace . $key] += $offset;
        } else {
            return $this->data[$this->namespace . $key] = $offset;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = [];
        return true;
    }
}
