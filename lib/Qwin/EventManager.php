<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * EventManager
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class EventManager extends Widget
{
    /**
     * Event array
     * 
     * @var string
     */
    protected $events = array();


    /**
     * 调用一个事件
     *
     * @param string $name the name of event or the Event object
     * @param mixed $args arguments
     * @return EventManager
     */
    public function __invoke($name, $args)
    {
        if ($name instanceof Event) {
            $event = $name;
            $name = $event->getName();
        } else {
            $name = strtolower($name);
            $event = new Event($name);
        }
        
        if (!isset($this->events[$name])) {
            return $this;
        }

        // prepend the Event object to the beginning of the arguments
        array_unshift($args, $event);

        ksort($this->events[$name]);
        foreach ($this->events[$name] as $callbacks) {
            foreach ($callbacks as $callback) {
                if (false === call_user_func_array($callback, $args)) {
                    break 2;
                }
            }
        }

        return $this;
    }
    
    /**
     * Add event
     *
     * @param string $name the name of event
     * @param mixed $callback callbable struct
     * @param int $priority the event priority
     * @return EventManager
     */
    public function add($name, $callback, $priority = 10)
    {
        if (!is_callable($callback)) {
            throw new Exception('Parameter 2 should be a valid callback');
        }

        $name = strtolower($name);

        if (!isset($this->events[$name])) {
            $this->events[$name] = array();
        }
        
        $this->events[$name][$priority][] = $callback;
        
        return $this;
    }
    
    /**
     * Remove one or all events
     * 
     * param string|null $name the name of event
     * @return \Qwin\EventManager
     */
    public function remove($name = null)
    {
        if (null === $name) {
            $this->_events = array();
        } else {
            $name = strtolower($name);
            if (isset($this->_events[$name])) {
                unset($this->_events[$name]);
            }
        }

        return $this;
    }

    /**
     * Check if has the given name of event
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_events[$name]);
    }
}