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
 * @author      Twin Huang <twinhuang@qq.com>
 * @property EventManager $eventManager The event manager widget
 */
class On extends AbstractWidget
{
    /**
     * Attach a handler to an event
     *
     * @param string|array $type The type of event, or an array that the key is event type and the value is event hanlder
     * @param callback $fn The event handler
     * @param int|string $priority The event priority, could be int or specify strings, the higer number, the higer priority
     * @param array $data The data pass to the event object, when the handler is triggered
     * @return EventManager
     */
    public function __invoke($type, $fn = null, $priority = 0, $data = array())
    {
        return $this->eventManager->add($type, $fn, $priority, $data);
    }
}
