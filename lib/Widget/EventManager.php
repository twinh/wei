<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * EventManager
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method \Widget\Event event(string $type) Create a event object
 * @todo namespace
 */
class EventManager extends WidgetProvider
{
    /**
     * The array contains the event handlers
     *
     * @var array
     */
    protected $handlers = array();
    
    /**
     * The array contains the event objects
     * 
     * @var array
     */
    protected $events = array();

    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->initInternalEvent();
    }

    /**
     * Trigger a event
     *
     * @param  string $type The name of event or the Event object
     * @param  mixed $args The arguments pass to the handle
     * @return mixed The result returned by the last handle
     */
    public function __invoke($type, $args = array())
    {
        if ($type instanceof Event) {
            $event = $type;
            $type = $event->getType();
        } else {
            $event = $this->event($type);
        }
        
        // Storage the event object
        $this->events[$type] = $event;

        if (!isset($this->handlers[$type])) {
            return;
        }

        // Prepend the Event object to the beginning of the arguments
        array_unshift($args, $event);

        krsort($this->handlers[$type]);
        foreach ($this->handlers[$type] as $callbacks) {
            foreach ($callbacks as $callback) {
                $result = call_user_func_array($callback, $args);
                $event->setResult($result);
                if (false === $result || $event->isDefaultPrevented()) {
                    break 2;
                }
            }
        }

        return $result;
    }

    /**
     * Add a event handle
     *
     * @param  string       $type     The type of event
     * @param  mixed        $callback The callbable struct
     * @param  int          $priority The event priority
     * @return \Widget\EventManager
     */
    public function add($type, $callback, $priority = 0)
    {
        if (!is_callable($callback)) {
            throw new Exception('Parameter 2 should be a valid callback');
        }

        $type = strtolower($type);

        if (!isset($this->handlers[$type])) {
            $this->handlers[$type] = array();
        }

        $this->handlers[$type][$priority][] = $callback;

        return $this;
    }

    /**
     * Remove one or all handlers
     *
     * param string|null $type The type of event
     * @return \Widget\EventManager
     */
    public function remove($type = null)
    {
        if (null === $type) {
            $this->handlers = array();
        } else {
            $type = strtolower($type);
            if (isset($this->handlers[$type])) {
                unset($this->handlers[$type]);
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
        return isset($this->handlers[$type]);
    }
     
    /**
     * Returns the event object storaged in the event manager
     * 
     * @param string $type
     * @return \Widget\Event
     */
    public function getEvent($type)
    {
        return isset($this->events[$type]) ? $this->events[$type] : false;
    }

    /**
     * Init the internal event
     */
    protected function initInternalEvent()
    {
        $widget = $this->widget;
        $that   = $this;

        // Trigger the shutdown event
        register_shutdown_function(function() use($that, $widget) {
            $that('shutdown', array($widget));
        });
        
        // Assign the lambda function to the variable to avoid " Fatal error: Cannot destroy active lambda function"
        // Trigger the exception event
        $exceptionHandle = function($exception) use($that, $widget) {
            $that('exception', array($widget, $exception));

            restore_exception_handler();

            if (!$that->getEvent('exception')->isDefaultPrevented()) {
                throw $exception;
            }
        };
        
        $prevHandle = set_exception_handler($exceptionHandle);
  
        // If setted the previous handle, bind it agian
        if ($prevHandle) {
            $this->add('exception', function($event, $widget, $exception) use($prevHandle) {
                call_user_func($prevHandle, $exception);
            });
        }
    }
}
