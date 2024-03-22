<?php

namespace Wei;

/**
 * Add common usage result functions to service
 */
trait RetTrait
{
    /**
     * Return operation result data
     *
     * @param string|array $message
     * @param int $code
     * @param string $type
     * @return Ret
     */
    public function ret($message, $code = null, $type = null)
    {
        // NOTE: always create new Ret instance
        return $this->wei->ret->__invoke($message, $code, $type);
    }

    /**
     * Return operation successful result
     *
     * @param string|array|null $message
     * @return Ret
     */
    public function suc($message = null)
    {
        return $this->wei->ret->suc($message);
    }

    /**
     * Return operation failed result, and logs with an info level
     *
     * @param string|array $message
     * @param int $code
     * @param string $level
     * @return Ret
     */
    public function err($message, $code = null, $level = null)
    {
        return $this->wei->ret->err($message, $code, $level);
    }
}
