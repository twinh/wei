<?php

namespace Wei;

use Wei\Ret\RetException;

/**
 * A service that use to build operation result
 *
 * @mixin \LoggerMixin
 * @mixin \ReqMixin
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
            // $this->xxx(['me%sage', 'ss'], code)
            $data = ['message' => $message, 'code' => $code];
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type string or array, "%s" given',
                is_object($message) ? get_class($message) : gettype($message)
            ));
        }

        if (is_array($data['message'])) {
            // The original message can be recorded in the log, which is convenient to merge the same logs
            $rawMessage = array_shift($data['message']);
            $data['message'] = vsprintf($rawMessage, $data['message']);
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
     * Return the operation result code
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->get('code');
    }

    /**
     * Set the operation result code
     *
     * @param int $code
     * @return $this
     */
    public function setCode(int $code): self
    {
        return $this->set('code', $code);
    }

    /**
     * Return the operation result message
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->get('message');
    }

    /**
     * Set the operation result message
     *
     * @param mixed $message
     * @return $this
     */
    public function setMessage($message): self
    {
        return $this->set('message', $message);
    }

    /**
     * Return the operation result data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->get('data');
    }

    /**
     * Set the operation result data
     *
     * @param mixed $data
     * @return $this
     */
    public function setData($data): self
    {
        return $this->set('data', $data);
    }

    /**
     * Return the operation result value by key
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Set the operation result value by key
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Add value to the result data
     *
     * @param string|array $with
     * @param mixed $value
     * @return $this
     */
    public function with($with, $value = null): self
    {
        if (is_string($with)) {
            $with = [$with => $value];
        }
        $this->data = array_merge($this->data, $with);
        return $this;
    }

    /**
     * Add value to to the "data" key in result if the item is specified in the request
     *
     * @param string $include
     * @param callable $callback
     * @return $this
     * @experimental
     */
    public function include(string $include, callable $callback): self
    {
        if ($this->isInclude($include, $this->req['include'])) {
            $this->data($include, $callback());
        }
        return $this;
    }

    /**
     * Add value to the result data if the item is specified in the request
     *
     * @param string $include
     * @param callable $callback
     * @return $this
     * @experimental
     */
    public function includeWith(string $include, callable $callback): self
    {
        if ($this->isInclude($include, $this->req['includeWith'])) {
            $this->with($include, $callback());
        }
        return $this;
    }

    /**
     * Add value to the "data" key in result data
     *
     * @param string|array $data
     * @param mixed $value
     * @return $this
     */
    public function data($data, $value = null): self
    {
        if (is_string($data)) {
            $data = [$data => $value];
        }

        /** @experimental Convert object to array to merge data */
        $internalData = $this->data['data'] ?? [];
        if (is_object($internalData) && method_exists($internalData, 'toArray')) {
            $internalData = $internalData->toArray();
        }

        $this->data['data'] = array_merge($internalData, $data);
        return $this;
    }

    /**
     * Returns metadata value by key, or returns all metadata if key is not set
     *
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getMetadata(?string $key = null)
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
    public function removeMetadata(?string $key = null)
    {
        if (null === $key) {
            $this->metadata = [];
        } else {
            unset($this->metadata[$key]);
        }
        return $this;
    }

    /**
     * Transform result data if exists
     *
     * @param string|class-string|object $class
     * @return $this
     * @experimental maybe rename
     */
    public function transform($class): self
    {
        if (!method_exists($class, 'toArray')) {
            throw new \InvalidArgumentException(sprintf(
                'Expected class `%s` to have method `toArray`',
                is_object($class) ? get_class($class) : $class
            ));
        }

        if (!isset($this->data['data'])) {
            return $this;
        }

        $this->data = forward_static_call([$class, 'toArray'], $this->data['data']) + $this->data;
        return $this;
    }

    /**
     * Prepend content to message
     *
     * @param string $prefix
     * @return $this
     * @experimental
     */
    public function prefix(string $prefix): self
    {
        $this->data['message'] = $prefix . $this->data['message'];
        return $this;
    }

    /**
     * Throw exception if ret is error
     *
     * @return $this
     * @throws RetException
     * @experimental
     */
    public function assert(): ?self
    {
        if ($this->isErr()) {
            throw new RetException($this);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**
     * Convert to JSON string
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(
            $this->jsonSerialize(),
            \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE
        );
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
        // Covert success code to error code, useful for exception or result code from external APIs
        if (!isset($code) || $this->defaultSucCode === $code) {
            $code = $this->defaultErrCode;
        }
        return $this->__invoke($message, $code, $level);
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

    /**
     * Check if a item is in the string list or array
     *
     * @param string $include
     * @param string|array $includes
     * @return bool
     * @experimental
     */
    protected function isInclude(string $include, $includes): bool
    {
        if (is_string($includes)) {
            return in_array($include, explode(',', $includes), true);
        }

        if (is_array($includes)) {
            return in_array($include, $includes, true);
        }

        return false;
    }
}
