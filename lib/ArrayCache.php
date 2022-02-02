<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
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
    protected function doGet(string $key): array
    {
        return [$this->data[$this->namespace . $key] ?? null, $this->has($key)];
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function set($key, $value, $expire = 0)
    {
        $this->data[$this->namespace . $key] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function delete(string $key): bool
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
     * @svc
     */
    protected function has(string $key): bool
    {
        return array_key_exists($this->namespace . $key, $this->data);
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function add($key, $value, $expire = 0)
    {
        if ($this->has($key)) {
            return false;
        } else {
            return $this->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function replace($key, $value, $expire = 0)
    {
        if (!$this->has($key)) {
            return false;
        } else {
            $this->data[$this->namespace . $key] = $value;
            return true;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function incr($key, $offset = 1)
    {
        if ($this->has($key)) {
            return $this->data[$this->namespace . $key] += $offset;
        } else {
            return $this->data[$this->namespace . $key] = $offset;
        }
    }

    /**
     * {@inheritdoc}
     * @svc
     */
    protected function clear()
    {
        $this->data = [];
        return true;
    }
}
