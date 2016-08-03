<?php

namespace Wei;

/**
 * A service that use to build operation result
 *
 * @property \Wei\Logger $logger
 */
class Ret extends Base
{
    /**
     * The default operation result data
     *
     * @var array
     */
    protected $defaults = array(
        'message' => 'Operation successful',
        'code' => 1,
    );

    /**
     * Return operation successful result
     *
     * ```php
     * // Return specified message
     * $this->suc('Payment successful');
     *
     * // Return more data
     * $this->suc(['message' => 'Read successful', 'page' => 1, 'rows' => 123]);
     * ```
     *
     * @param string $message
     * @return array
     */
    public function suc($message = null)
    {
        return $this->__invoke($message ?: $this->defaults['message'], 1, 'success');
    }

    /**
     * Return operation failed result, and logs with an info level
     *
     * @param string $message
     * @param int $code
     * @param string $level
     * @return array
     */
    public function err($message, $code = -1, $level = 'info')
    {
        return $this->__invoke($message, $code, $level);
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
        return $this->err($message, $code, 'warning');
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
        return $this->err($message, $code, 'alert');
    }

    /**
     * Return operation result data
     *
     * @param string|array $message
     * @param int $code
     * @param string $type
     * @return array
     */
    public function __invoke($message, $code = 1, $type = 'success')
    {
        if (is_array($message)) {
            $data = $message + array('code' => $code) + $this->defaults;
        } else {
            $data = array('message' => (string)$message, 'code' => $code);
        }

        // Record error result
        // TODO record more relative data
        if ($code !== 1) {
            $this->logger->log($type, $message, $data);
        }

        return $data;
    }
}
