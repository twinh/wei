<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Trigger
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class Trigger extends AbstractWidget
{
    /**
     * @see \Widget\Manager::__invoke
     * @param array $params
     */
    public function __invoke($event, $params = array(), WidgetInterface $widget = null)
    {
        return $this->eventManager->__invoke($event, $params, $widget);
    }
}
