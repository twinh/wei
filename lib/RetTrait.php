<?php

namespace Wei;

/**
 * Add common usage result functions to service
 *
 * @property \Wei\Ret $ret
 */
trait RetTrait
{
    /**
     * Return operation result data
     *
     * @param string|array $message
     * @param int $code
     * @param string $type
     * @return array
     */
    public function ret($message, $code = 1, $type = 'success')
    {
        return $this->ret->__invoke($message, $code, $type);
    }

    /**
     * Return operation successful result
     *
     * @param null|string|array $message
     * @return array
     */
    public function suc($message = null)
    {
        return $this->ret->suc($message);
    }

    /**
     * Return operation failed result, and logs with an info level
     *
     * @param string|array $message
     * @param int $code
     * @param string $level
     * @return array
     */
    public function err($message, $code = -1, $level = 'info')
    {
        return $this->ret->err($message, $code, $level);
    }

    /**
     * Return operation failed result, and logs with a warning level
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    public function warning($message, $code = -1)
    {
        return $this->ret->warning($message, $code);
    }

    /**
     * Return operation failed result, and logs with an alert level
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    public function alert($message, $code = -1)
    {
        return $this->ret->alert($message, $code);
    }
}
