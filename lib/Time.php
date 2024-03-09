<?php

namespace Wei;

/**
 * Date time utils
 */
class Time extends Base
{
    /**
     * @var int|null
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

    /**
     * Set the current timestamp
     *
     * @param int|null $timestamp
     * @return $this
     * @svc
     */
    protected function setTimestamp(?int $timestamp = null): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}
