<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * An event dispatch service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Event extends Base
{
    /**
     * The array contains the event handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * The name of last triggered event
     *
     * @var string
     */
    protected $curName;

    /**
     * A callback that will called when an event triggered
     *
     * @var callable
     */
    protected $loadEvent;

    /**
     * Trigger an event
     *
     * @param  string $name The name of event
     * @param  array $args The arguments pass to the handle
     * @param bool $halt
     * @return array|mixed
     * @svc
     */
    protected function trigger($name, $args = [], $halt = false)
    {
        $this->curName = $name;
        $this->loadEvent && call_user_func($this->loadEvent, $name);

        if (!is_array($args)) {
            $args = [$args];
        }

        $results = [];
        if (isset($this->handlers[$name])) {
            krsort($this->handlers[$name]);
            foreach ($this->handlers[$name] as $handlers) {
                foreach ($handlers as $handler) {
                    $results[] = $result = call_user_func_array($handler, $args);

                    if (null !== $result && $halt) {
                        return $result;
                    }

                    if (false === $result) {
                        break 2;
                    }
                }
            }
        }

        return $halt ? null : $results;
    }

    /**
     * Trigger an event until the first non-null response is returned
     *
     * @param string $name
     * @param array $args
     * @return mixed
     * @link https://github.com/laravel/framework/blob/5.1/src/Illuminate/Events/Dispatcher.php#L161
     * @svc
     */
    protected function until($name, $args = [])
    {
        return $this->trigger($name, $args, true);
    }

    /**
     * Attach a handler to an event
     *
     * @param string|array $name The name of event, or an associative array contains event name and event handler pairs
     * @param callable $fn The event handler
     * @param int $priority The event priority
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     * @svc
     */
    protected function on($name, $fn = null, $priority = 0)
    {
        // ( $names )
        if (is_array($name)) {
            foreach ($name as $item => $fn) {
                $this->on($item, $fn);
            }
            return $this;
        }

        // ( $name, $fn, $priority, $data )
        if (!is_callable($fn)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of name callable, "%s" given',
                is_object($fn) ? get_class($fn) : gettype($fn)
            ));
        }

        if (!isset($this->handlers[$name])) {
            $this->handlers[$name] = [];
        }
        $this->handlers[$name][$priority][] = $fn;

        return $this;
    }

    /**
     * Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     * @svc
     */
    protected function off($name)
    {
        if (isset($this->handlers[$name])) {
            unset($this->handlers[$name]);
        }
        return $this;
    }

    /**
     * Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     * @svc
     */
    protected function has($name)
    {
        return isset($this->handlers[$name]);
    }

    /**
     * Returns the name of last triggered event
     *
     * @return string
     * @svc
     */
    protected function getCurName()
    {
        return $this->curName;
    }
}
