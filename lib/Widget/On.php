<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * On
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class On extends WidgetProvider
{
    /**
     * Attach a handler to an event
     *
     * @see Event::add()
     * @param  string $event    The type of event
     * @param  mixed  $callback The handle of event
     * @param int $priority The priority of event
     * @return Widget\EventManager
     */
    public function __invoke($event, $callback, $priority = 0)
    {
        return $this->eventManager->add($event, $callback, $priority);
    }
}
