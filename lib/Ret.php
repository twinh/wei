<?php

namespace Wei;

use InvalidArgumentException;

/**
 * A service that use to build operation result
 *
 * @mixin \LoggerMixin
 */
class Ret extends Base implements \JsonSerializable, \ArrayAccess
{
    /**
     * {@inheritdoc}
     */
    protected static $createNewInstance = true;

    /**
     * The default success code
     *
     * @var int
     */
    protected $defaultSucCode = 0;

    /**
     * The default success message
     *
     * @var string
     */
    protected $defaultSucMessage = 'Operation successful';

    /**
     * The default error code when not provided.
     *
     * @var int
     */
    protected $defaultErrCode = -1;

    /**
     * The current operation result data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Additional data that will not display to the user
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Return operation result data
     *
     * @param array|string $message
     * @param int|null $code
     * @param string|null $type
     * @return $this
     */
    public function __invoke($message, $code = null, $type = null)
    {
        null === $code && $code = $this->defaultSucCode;

        if (is_string($message)) {
            // Use string message
            // $this->xxx('message', code);
            $data = ['message' => $message, 'code' => $code];
        } elseif (is_array($message) && !isset($message[0])) {
            // Use indexed array to pass more data
            // $this->xxx(['message' => 'xxx', 'code' => xxx, 'more' => 'xxx']);
            $data = $message + ['message' => $this->defaultSucMessage, 'code' => $code];
        } elseif (is_array($message) && isset($message[0])) {
            // Use associative array to pass original message and arguments to be formatted.
            // The original message can be recorded in the log, which is convenient to merge the same logs
            // $this->xxx(['me%sage', 'ss'], code)
            $rawMessage = array_shift($message);
            $params = $message;
            $message = vsprintf($rawMessage, $params);
            $data = ['message' => (string) $message, 'code' => $code];
        } else {
            throw new InvalidArgumentException(sprintf(
                'Expected argument of type string or array, "%s" given',
                is_object($message) ? get_class($message) : gettype($message)
            ));
        }
        $this->data = $data;

        // Record error result
        if ($this->isErr()) {
            $this->logger->log($type ?? 'info', $rawMessage ?? $data['message'], $params ?? $data);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuc()
    {
        return !$this->isErr();
    }

    /**
     * @return bool
     */
    public function isErr()
    {
        return $this->defaultSucCode !== $this->data['code'];
    }

    /**
     * Returns result as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Returns metadata value by key, or returns all metadata if key is not set
     *
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getMetadata(string $key = null)
    {
        return null === $key ? $this->metadata : ($this->metadata[$key] ?? null);
    }

    /**
     * Sets metadata by key or sets all metadata if key is an array
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function setMetadata($key, $value = null)
    {
        if (is_array($key)) {
            $this->metadata = $key;
        } else {
            $this->metadata[$key] = $value;
        }
        return $this;
    }

    /**
     * Removes metadata value by key or clears all metadata if key is not set
     *
     * @param string $key
     * @return $this
     */
    public function removeMetadata(string $key = null)
    {
        if (null === $key) {
            $this->metadata = [];
        } else {
            unset($this->metadata[$key]);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Return operation successful result
     *
     * ```php
     * // Specified message
     * $this->suc('Payment successful');
     *
     * // Format
     * $this->suc(['me%sage', 'ss']);
     *
     * // More data
     * $this->suc(['message' => 'Read successful', 'page' => 1, 'rows' => 123]);
     * ```
     *
     * @param array|string|null $message
     * @return $this
     * @svc
     */
    protected function suc($message = null)
    {
        return $this->__invoke($message ?? $this->defaultSucMessage);
    }

    /**
     * Return operation failed result, and logs with an info level
     *
     * @param array|string $message
     * @param int|null $code
     * @param string $level The log level, default to "info"
     * @return $this
     * @svc
     */
    protected function err($message, $code = null, $level = null)
    {
        return $this->__invoke($message, $code ?? $this->defaultErrCode, $level);
    }

    /**
     * Return operation failed result, and logs with a warning level
     *
     * @param string $message
     * @param int $code
     * @return $this
     * @svc
     */
    protected function warning($message, $code = null)
    {
        return $this->err($message, $code, 'warning');
    }

    /**
     * Return operation failed result, and logs with an alert level
     *
     * @param string $message
     * @param int $code
     * @return $this
     * @svc
     */
    protected function alert($message, $code = null)
    {
        return $this->err($message, $code, 'alert');
    }
}
