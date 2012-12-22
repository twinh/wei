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
     * The priorities text map
     * 
     * @var array
     */
    protected $priorities = array(
        'low'       => -10,
        'normal'    => 0,
        'high'      => 10
    );

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

        // Prepend the event and widget manager object to the beginning of the arguments
        array_unshift($args, $event, $this->widget);

        krsort($this->handlers[$type]);
        foreach ($this->handlers[$type] as $handlers) {
            foreach ($handlers as $handler) {
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

        return $result;
    }

    /**
     * Attach a handler to an event
     *
     * @param  string       $type     The type of event
     * @param  mixed        $fn       The callbable struct
     * @param  int          $priority The event priority
     * @param array $data The data passed to the event object, when the handler is bound
     * @return \Widget\EventManager
     */
    public function add($type, $fn, $priority = 0, $data = array())
    {
        if (!is_callable($fn)) {
            throw new Exception('Parameter 2 should be a valid callback');
        }
        
        $priority = is_numeric($priority) ? $priority :
            isset($this->priorities[$priority]) ? $this->priorities[$priority] : 0;

        $type = strtolower($type);

        if (!isset($this->handlers[$type])) {
            $this->handlers[$type] = array();
        }

        $this->handlers[$type][$priority][] = array($fn, $data);

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
        $that = $this;

        // Trigger the shutdown event
        register_shutdown_function(function() use($that) {
            $that('shutdown');
        });
        
        // Assign the lambda function to the variable to avoid " Fatal error: Cannot destroy active lambda function"
        // Trigger the exception event
        $exceptionHandle = function($exception) use($that) {
            $that('exception', array($exception));

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
