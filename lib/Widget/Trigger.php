<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Trigger a event
 * 
 * This widget is the alias of `$widget->eventManager->__invoke()`
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    EventManager $eventManager The event manager widget
 */
class Trigger extends AbstractWidget
{
    /**
     * Trigger a event
     *
     * @param  string $type The name of event or a Event object
     * @param  array $args The arguments pass to the handle
     * @param null|WidgetInterface $widget If the widget contains the 
     *                                     $type property, the event manager 
     *                                     will trigger it too
     * @return Event\EventInterface The event object
     */
    public function __invoke($event, $params = array(), WidgetInterface $widget = null)
    {
        return $this->eventManager->__invoke($event, $params, $widget);
    }
}
