<?php

use Wei\Ret;

if (!function_exists('suc')) {
    /**
     * Return operation successful result
     *
     * @param string|array|null $message
     * @return Ret
     */
    function suc($message = null): Ret
    {
        return Ret::suc($message);
    }
}

if (!function_exists('err')) {
    /**
     * Return operation failed result, and logs with an info level
     *
     * @param array|string $message
     * @param int|null $code
     * @param string|null $level
     * @return Ret
     */
    function err($message, ?int $code = null, ?string $level = null): Ret
    {
        return Ret::err(...func_get_args());
    }
}
