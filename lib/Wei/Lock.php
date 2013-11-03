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
        return $this->cache->remove($key);
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
}