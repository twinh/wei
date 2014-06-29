<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
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
    protected $keys = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        register_shutdown_function(array($this, 'releaseAll'));

        // Release all lock and exist when catch signal in CLI
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, array($this, 'catchSignal'));
            pcntl_signal(SIGTERM, array($this, 'catchSignal'));
        }
    }

    /**
     * Acquire a lock key
     *
     * @param string $key
     * @return bool
     */
    public function __invoke($key)
    {
        if ($this->cache->add($key, true)) {
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
            if (($index = array_search($key, $this->keys)) !== false) {
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
     */
    public function catchSignal()
    {
        $this->releaseAll();
        exit;
    }
}