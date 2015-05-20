<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
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
     * Manger: The array contains the event handlers
     *
     * @var array
     */
    protected $handlers = array();

    /**
     * The name of event
     *
     * @var string
     */
    protected $name;

    /**
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $timeStamp;

    /**
     * The last value returned by an event handler
     *
     * @var mixed
     */
    protected $result;

    /**
     * The data accepted from the handler
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->timeStamp = microtime(true);
    }

    /**
     * Returns the name of event
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the time stamp when event constructed
     *
     * @return string|double
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * Sets the event result
     *
     * @param mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Returns the last result returned by the event handler
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the event data
     *
     * @param array $data
     * @return $this
     */
    public function setData($data = array())
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns the event data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Manager: Create a event object
     *
     * @param string $name
     * @return $this
     */
    public function __invoke($name)
    {
        return new static(array(
            'wei' => $this->wei,
            'name' => $name
        ));
    }

    /**
     * Manager: Trigger an event
     *
     * @param  string $name The name of event or an Event object
     * @param  array $args The arguments pass to the handle
     * @return $this
     */
    public function trigger($name, $args = array())
    {
        if ($name instanceof static) {
            $event = $name;
        } else {
            $event = $this($name);
        }

        $name = $event->getName();

        if (!is_array($args)) {
            $args = array($args);
        }

        // Prepend the event and service container to the beginning of the arguments
        array_unshift($args, $event, $this->wei);

        if (isset($this->handlers[$name])) {
            krsort($this->handlers[$name]);
            foreach ($this->handlers[$name] as $handlers) {
                foreach ($handlers as $handler) {
                    list($fn, $data) = $handler;
                    $event->setData($data);
                    $result = call_user_func_array($fn, $args);
                    $event->setResult($result);
                    if (false === $result) {
                        break 2;
                    }
                }
            }
        }

        return $event;
    }

    /**
     * Manger: Attach a handler to an event
     *
     * @param string|array $name The name of event, or an array that the key is event name and the value is event hanlder
     * @param callback $fn The event handler
     * @param int $priority The event priority
     * @param array $data The data pass to the event object, when the handler is triggered
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     */
    public function on($name, $fn = null, $priority = 0, $data = array())
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
            $this->handlers[$name] = array();
        }

        $this->handlers[$name][$priority][] = array($fn, $data);

        return $this;
    }

    /**
     * Manager: Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     */
    public function off($name)
    {
        if (isset($this->handlers[$name])) {
            unset($this->handlers[$name]);
        }
        return $this;
    }

    /**
     * Manager: Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->handlers[$name]);
    }
}