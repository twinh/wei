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
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class Trigger extends WidgetProvider
{
    /**
     * @see \Widget\Manager::__invoke
     */
    public function __invoke($event, $params = array(), Widgetable $widget = null)
    {
        return $this->eventManager->__invoke($event, $params, $widget);
    }
}
