<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that provide the functionality of exclusive Lock
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Cache $cache A cache service
 */
class Lock extends Base
{
    /**
     * @var array
     */
    protected $keys = [];

    /**
     * Expiration seconds to release lock, use to avoid deadlock when PHP crash
     *
     * @var int
     */
    protected $expire = 30;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        register_shutdown_function([$this, 'releaseAll']);

        // Release locks and exist when catch signal in CLI
        if (function_exists('pcntl_signal')) {
            pcntl_signal(\SIGINT, [$this, 'catchSignal']);
            pcntl_signal(\SIGTERM, [$this, 'catchSignal']);
        }
    }

    /**
     * Acquire a lock key
     *
     * @param string $key
     * @param int|null $expire
     * @return bool
     */
    public function __invoke($key, $expire = null)
    {
        $expire = null === $expire ? $this->expire : $expire;
        if ($this->cache->add($key, true, $expire)) {
            $this->keys[] = $key;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Release a lock key
     *
     * @param string $key
     * @return bool
     */
    public function release($key)
    {
        if ($this->cache->remove($key)) {
            if (false !== ($index = array_search($key, $this->keys, true))) {
                unset($this->keys[$index]);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Release all lock keys
     */
    public function releaseAll()
    {
        foreach ($this->keys as $key) {
            $this->release($key);
        }
    }

    /**
     * Release all lock keys and exit
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function catchSignal()
    {
        $this->releaseAll();
        exit;
    }
}
