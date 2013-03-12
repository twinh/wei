<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */
namespace Widget;

/**
 * Attach a handler to an event
 * 
 * This widget is the alias of `$widget->eventManager->add()`
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class On extends AbstractWidget
{
    /**
     * Attach a handler to an event
     *
     * @param string $type The type of event
     * @param mixed $fn The callbable struct
     * @param integer $priority The event priority, could be int or specify strings
     * @param array $data The data pass to the event object, when the handler is triggered
     * @return \Widget\EventManager
     */
    public function __invoke($type, $fn, $priority = 0, $data = array())
    {
        return $this->eventManager->add($type, $fn, $priority, $data);
    }
}
