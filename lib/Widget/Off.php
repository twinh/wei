<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Off
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class Off extends AbstractWidget
{
    /**
     * Remove one or all handlers
     *
     * param string|null $type The type of event
     * @return EventManager
     */
    public function __invoke($type)
    {
        return $this->eventManager->remove($type);
    }
}
