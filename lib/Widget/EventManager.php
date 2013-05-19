<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Event\Event;

/**
 * The event manager to add, remove and trigger events
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EventManager extends AbstractWidget
{
    /**
     * The array contains the event handlers
     *
     * @var array
     */
    protected $handlers = array();

    /**
     * Whether ignore the previous exception handler or attch it again to the
     * exception event
     *
     * @var bool
     */
    protected $ignorePrevHandler = false;

    /**
     * The available priorities text
     *
     * @var array
     */
    protected $priorities = array(
        'low'       => -1000,
        'normal'    => 0,
        'high'      => 1000
    );

    /**
     * The 404 exception classes
     *
     * @var array
     */
    protected $notFoundExceptions = array(
        'Widget\Exception\NotFoundException'
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->registerInternalEvent();
    }

    /**
     * Trigger an event
     *
     * @param  string $type The name of event or an Event object
     * @param  array $args The arguments pass to the handle
     * @param null|WidgetInterface $widget If the widget contains the
     *                                     $type property, the event manager
     *                                     will trigger it too
     * @return Event\Event The event object
     */
    public function __invoke($type, $args = array(), WidgetInterface $widget = null)
    {
        if ($type instanceof Event) {
            $event      = $type;
            $type       = $event->getType();
            $namespaces = $event->getNamespaces();
        } else {
            list($type, $namespaces) = $this->splitNamespace($type);
            $event      = new Event(array(
                'widget'        => $this->widget,
                'type'          => $type,
                'namespaces'    => $namespaces,
            ));
        }

        if (!is_array($args)) {
            $args = array($args);
        }

        // Prepend the event and widget manager object to the beginning of the arguments
        array_unshift($args, $event, $this->widget);

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

        if ($widget && $selfEvent = $widget->getOption($type)) {
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
     * Attach a handler to an event
     *
     * @param string|array $type The type of event, or an array that the key is event type and the value is event hanlder
     * @param callback $fn The event handler
     * @param int|string $priority The event priority, could be int or specify strings, the higer number, the higer priority
     * @param array $data The data pass to the event object, when the handler is triggered
     * @return EventManager
     */
    public function add($type, $fn = null, $priority = 0, $data = array())
    {
        // ( $types )
        if (is_array($type)) {
            foreach ($type as $name => $fn) {
                $this->add($name, $fn);
            }
            return $this;
        }

        // ( $type, $fn, $priority, $data )
        if (!is_callable($fn)) {
            throw new Exception\UnexpectedTypeException($fn, 'callable');
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
     * Remove event handlers by specified type
     *
     * @param string $type The type of event
     * @return EventManager
     */
    public function remove($type)
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
                $this->remove($type . '.' . implode('.', $namespaces));
            }
        }

        return $this;
    }

    /**
     * Check if has the given type of event handlers
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
     * Create a new event
     *
     * @return Event\Event
     * @param array $namespaces
     * @todo check interface
     */
    public function create($type, $class = 'Widget\Event\Event')
    {
        list($type, $namespaces) = $this->splitNamespace($type);

        return new $class(array(
            'widget'        => $this->widget,
            'type'          => $type,
            'namespaces'    => $namespaces,
        ));
    }

    /**
     * Register the internal event
     */
    protected function registerInternalEvent()
    {
        $that = $this;
        $cwd = getcwd();

        // Attach the shutdown event
        register_shutdown_function(function() use($that, $cwd) {
            $error = error_get_last();
            if ($error && in_array($error['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
                chdir($cwd);
                $exception = new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
                $event = $that('fatal', $exception);
                if (!$event->isDefaultPrevented()) {
                    $that('exception', $exception);
                }
            }

            $that('shutdown');
        });

        // Assign the exception to a class method instead of a lambda function
        // to avoid "Fatal error: Cannot destroy active lambda function"
        $prevHandler = set_exception_handler(array($this, 'handleException'));

        // If setted the previous handler, attach it agian
        if (!$this->ignorePrevHandler && $prevHandler) {
            $this->add('exception', function($event, $widget, $exception) use($prevHandler) {
                call_user_func($prevHandler, $exception);
            });
        }

        // Convert exceptions to 404 event
        $notFoundExceptions = $this->notFoundExceptions;
        $this->add('exception', function(Event $event, $widget, $exception) use($that, $notFoundExceptions) {
            if ($that->has('404')) {
                foreach ($notFoundExceptions as $class) {
                    if ($exception instanceof $class) {
                        $event->preventDefault();
                        $event->stopPropagation();
                        $that('404', array($exception));
                        return;
                    }
                }
            }
        });

        // Attach the widget manager's construct and constructed event
        $this->widget->setOption(array(
            'construct' => function ($name, $full) use($that) {
                $that('construct.' . $name, array($name, $full));
            },
            'constructed' => function($widget, $name, $full) use($that) {
                $that('constructed.' . $name, array($widget, $name, $full));
            }
        ));
    }

    /**
     * The internal exception hanlder integrated with event
     *
     * @param \Exception $exception
     * @throws \Exception When none of exception events have been prevented
     * @internal
     */
    public function handleException(\Exception $exception)
    {
        $event = $this('exception', array($exception));

        restore_exception_handler();

        if (!$event->isDefaultPrevented()) {
            throw $exception;
        }
    }

    /**
     * Returns the array with two elements, the first one is the event name and
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
