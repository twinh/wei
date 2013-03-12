<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Remove event handlers by specified type
 *
 * This widget is the alias of `$widget->eventManager->remove()`
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\EventManager $eventManager The event manager widget
 */
class Off extends AbstractWidget
{
    /**
     * Remove event handlers by specified type
     *
     * param string $type The type of event
     * @return \Widget\EventManager
     */
    public function __invoke($type)
    {
        return $this->eventManager->remove($type);
    }
}
