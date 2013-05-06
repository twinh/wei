<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * Trigger an event
 * 
 * This widget is the alias of `$widget->eventManager->__invoke()`
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    EventManager $eventManager The event manager widget
 */
class Trigger extends AbstractWidget
{
    /**
     * Trigger an event
     *
     * @param  string $type The name of event or a Event object
     * @param  array $args The arguments pass to the handle
     * @param null|WidgetInterface $widget If the widget contains the 
     *                                     $type property, the event manager 
     *                                     will trigger it too
     * @return Event\Event The event object
     */
    public function __invoke($event, $params = array(), WidgetInterface $widget = null)
    {
        return $this->eventManager->__invoke($event, $params, $widget);
    }
}
