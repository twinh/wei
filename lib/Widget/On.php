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
     * @param  string       $type     The type of event
     * @param  mixed        $fn       The callbable struct
     * @param  int          $priority The event priority
     * @param array $data The data passed to the event object, when the handler is bound
     * @return \Widget\EventManager
     */
    public function __invoke($type, $fn, $priority = 0, $data = array())
    {
        return $this->eventManager->add($type, $fn, $priority, $data);
    }
}
