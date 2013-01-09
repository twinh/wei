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
     * @param \Widget\Widgetable $widget If the widget contains the $type 
     *                                   property, the event manager will 
     *                                   trigger it too
     * @return mixed The result returned by the last handle
     */
    public function __invoke($type, $args = array(), Widgetable $widget = null)
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
        
        if ($widget && $selfEvent = $widget->option($type)) {
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
     * @param  string       $type     The type of event
     * @param  mixed        $fn       The callbable struct
     * @param  int|string   $priority The event priority, could be int or specify strings
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

        list($type, $namespaces) = $this->splitNamespace($type);

        if (!isset($this->handlers[$type])) {
            $this->handlers[$type] = array();
        }
 
        $this->handlers[$type][$priority][] = array($fn, $data, $namespaces);

        return $this;
    }

    /**
     * Remove handlers by given type
     *
     * param string $type The type of event
     * @return \Widget\EventManager
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
        return isset($this->handlers[$type]);
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
            $event = $that('exception', array($exception));

            restore_exception_handler();

            if (!$event->isDefaultPrevented()) {
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
        
        // Trigger the widget manager's construct and construct event
        $this->widget->option(array(
            'construct' => function ($name, $full) use($that) {
                $that('construct.' . $name, array($name, $full));
            },
            'constructed' => function($widget, $name, $full) use($that) {
                $that('constructed.' . $name, array($widget, $name, $full));
            }
        ));
    }
    
    /**
     * Returns the array with two elements, the first one is the event name and
     * the second one is the event namespaces
     * 
     * @param string $type
     * @return array
     */
    protected function splitNamespace($type)
    {
        $type = strtolower($type);
        
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
