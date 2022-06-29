<?php

namespace Wei;

/**
 * Date time utils
 */
class Time extends Base
{
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @return string
     * @svc
     */
    protected function now()
    {
        return date('Y-m-d H:i:s', $this->timestamp());
    }

    /**
     * @return string
     * @svc
     */
    protected function today()
    {
        return date('Y-m-d', $this->timestamp());
    }

    /**
     * @return int
     * @svc
     */
    protected function timestamp()
    {
        return $this->timestamp ?: time();
    }
}
