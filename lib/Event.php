<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The event manager to add, remove and trigger events
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
     * Manger: The available priorities text
     *
     * @var array
     */
    protected $priorities = array(
        'low' => -1000,
        'normal' => 0,
        'high' => 1000
    );

    /**
     * The name of event
     *
     * @var string
     */
    protected $type;

    /**
     * The namespaces of event
     *
     * @var array
     */
    protected $namespaces = array();

    /**
     * Time stamp with microseconds when object constructed
     *
     * @var float
     */
    protected $timeStamp;

    /**
     * Whether prevent the default action of event or not
     *
     * @var bool
     */
    protected $preventDefault = false;

    /**
     * Whether to trigger the next handler or not
     *
     * @var bool
     */
    protected $stopPropagation = false;

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
     * Returns the type of event
     *
     * @param bool $full Whether return type or type with with namespace
     * @return string
     */
    public function getType($full = false)
    {
        if ($full && $this->namespaces) {
            return $this->type . '.' . $this->getNamespace();
        } else {
            return $this->type;
        }
    }

    /**
     * Set the type of event
     *
     * @param  string $type
     * @return Event
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the namespaces of event
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Set the namespaces of event
     *
     * @param array $namespaces
     * @return Event
     */
    public function setNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces;
        return $this;
    }

    /**
     * Returns the event namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return implode('.', $this->namespaces);
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
     * Set a flag to prevent the default action
     *
     * @return Event
     */
    public function preventDefault()
    {
        $this->preventDefault = true;
        return $this;
    }

    /**
     * Whether prevent the default action of event or not
     *
     * @return bool
     */
    public function isDefaultPrevented()
    {
        return $this->preventDefault;
    }

    /**
     * Sets the event result
     *
     * @param mixed $result
     * @return Event
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
     * @return Event
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
     * Set a flag to stop trigger the next handler
     *
     * @return Event
     */
    public function stopPropagation()
    {
        $this->stopPropagation = true;
        return $this;
    }

    /**
     * Whether to trigger the next handler or not
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->stopPropagation;
    }

    /**
     * Manager: Create a event object
     *
     * @param string $type
     * @return $this
     */
    public function __invoke($type)
    {
        list($type, $namespaces) = $this->splitNamespace($type);
        return new static(array(
            'wei' => $this->wei,
            'type' => $type,
            'namespaces' => $namespaces,
        ));
    }

    /**
     * Manager: Trigger an event
     *
     * @param  string $type The name of event or an Event object
     * @param  array $args The arguments pass to the handle
     * @param null|Base $wei If the Wei contains the
     *                                     $type property, the event manager
     *                                     will trigger it too
     * @return $this The event object
     */
    public function trigger($type, $args = array(), Base $wei = null)
    {
        if ($type instanceof static) {
            $event = $type;
        } else {
            $event = $this($type);
        }

        $type = $event->getType();
        $namespaces = $event->getNamespaces();

        if (!is_array($args)) {
            $args = array($args);
        }

        // Prepend the event and Wei container object to the beginning of the arguments
        array_unshift($args, $event, $this->wei);

        if (isset($this->handlers[$type])) {
            krsort($this->handlers[$type]);
            foreach ($this->handlers[$type] as $handlers) {
                foreach ($handlers as $handler) {
                    if (!$namespaces || !$handler[2] || $namespaces == array_intersect($namespaces, $handler[2])) {
                        list($fn, $data) = $handler;
                        $event->setData($data);

                        if (false === ($result = call_user_func_array($fn, $args))) {
                            $event->preventDefault();
                        }
                        $event->setResult($result);

                        if ($event->isPropagationStopped()) {
                            break 2;
                        }
                    }
                }
            }
        }

        if ($wei && $selfEvent = $wei->getOption($type)) {
            if (is_callable($selfEvent)) {
                if (false === ($result = call_user_func_array($selfEvent, $args))) {
                    $event->preventDefault();
                }
                $event->setResult($result);
            }
        }

        return $event;
    }

    /**
     * Manger: Attach a handler to an event
     *
     * @param string|array $type The type of event, or an array that the key is event type and the value is event hanlder
     * @param callback $fn The event handler
     * @param int|string $priority The event priority, could be int or specify strings, the higer number, the higer priority
     * @param array $data The data pass to the event object, when the handler is triggered
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return Event
     */
    public function on($type, $fn = null, $priority = 0, $data = array())
    {
        // ( $types )
        if (is_array($type)) {
            foreach ($type as $name => $fn) {
                $this->on($name, $fn);
            }
            return $this;
        }

        // ( $type, $fn, $priority, $data )
        if (!is_callable($fn)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type callable, "%s" given',
                is_object($fn) ? get_class($fn) : gettype($fn)
            ));
        }

        $priority = is_numeric($priority) ? $priority :
            (isset($this->priorities[$priority]) ? $this->priorities[$priority] : 0);

        list($type, $namespaces) = $this->splitNamespace($type);

        if (!isset($this->handlers[$type])) {
            $this->handlers[$type] = array();
        }

        $this->handlers[$type][$priority][] = array($fn, $data, $namespaces);

        return $this;
    }

    /**
     * Manager: Remove event handlers by specified type
     *
     * @param string $type The type of event
     * @return Event
     */
    public function off($type)
    {
        list($type, $namespaces) = $this->splitNamespace($type);

        if ($type && isset($this->handlers[$type])) {
            if (!$namespaces) {
                unset($this->handlers[$type]);
            } else {
                foreach ($this->handlers[$type] as $i => $handlers) {
                    foreach ($handlers as $j => $handler) {
                        if ($namespaces == array_intersect($namespaces, $handler[2])) {
                            unset($this->handlers[$type][$i][$j]);
                        }
                    }
                }
            }
            // Unbind all event in namespace
        } else {
            foreach ($this->handlers as $type => $handlers) {
                $this->off($type . '.' . implode('.', $namespaces));
            }
        }

        return $this;
    }

    /**
     * Manager: Check if has the given type of event handlers
     *
     * @param  string $type
     * @return bool
     */
    public function has($type)
    {
        list($type, $namespaces) = $this->splitNamespace($type);

        if (!$namespaces) {
            return isset($this->handlers[$type]);
        } elseif (!$type) {
            foreach ($this->handlers as $type => $handlers) {
                if (true === $this->has($type . '.' . implode('.', $namespaces))) {
                    return true;
                }
            }
            return false;
        } else {
            if (!isset($this->handlers[$type])) {
                return false;
            } else {
                foreach ($this->handlers[$type] as $handlers) {
                    foreach ($handlers as $handler) {
                        if ($namespaces == array_intersect($namespaces, $handler[2])) {
                            return true;
                        }
                    }
                }
                return false;
            }
        }
    }

    /**
     * Manager: Returns the array with two elements, the first one is the event name and
     * the second one is the event namespaces
     *
     * @param string $type
     * @return array<string|array>
     */
    protected function splitNamespace($type)
    {
        if (false === ($pos = strpos($type, '.'))) {
            return array($type, array());
        } else {
            $namespaces = array_unique(array_filter(explode('.', substr($type, $pos))));
            sort($namespaces);
            return array(
                substr($type, 0, $pos),
                $namespaces
            );
        }
    }
}