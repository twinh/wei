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
 * @todo namespace
 */
class EventManager extends WidgetProvider
{
    /**
     * The array contains the event handles
     *
     * @var array
     */
    protected $handles = array();
    
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
     * @param  string $name The name of event or the Event object
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

        if (!isset($this->handles[$type])) {
            return;
        }

        // Prepend the Event object to the beginning of the arguments
        array_unshift($args, $event);

        ksort($this->handles[$type]);
        foreach ($this->handles[$type] as $callbacks) {
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
     * @param  mixed        $callback callbable struct
     * @param  int          $priority the event priority
     * @return EventManager
     */
    public function add($name, $callback, $priority = 0)
    {
        if (!is_callable($callback)) {
            throw new Exception('Parameter 2 should be a valid callback');
        }

        $name = strtolower($name);

        if (!isset($this->handles[$name])) {
            $this->handles[$name] = array();
        }

        $this->handles[$name][$priority][] = $callback;

        return $this;
    }

    /**
     * Remove one or all handles
     *
     * param string|null $name the name of event
     * @return \Widget\EventManager
     */
    public function remove($name = null)
    {
        if (null === $name) {
            $this->handles = array();
        } else {
            $name = strtolower($name);
            if (isset($this->handles[$name])) {
                unset($this->handles[$name]);
            }
        }

        return $this;
    }

    /**
     * Check if has the given name of event
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->handles[$name]);
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
