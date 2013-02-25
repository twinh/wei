<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */
namespace Widget;

/**
 * On
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\EventManager $eventManager The event manager widget
 */
class On extends AbstractWidget
{
    /**
     * @see \Widget\Manager::add
     * @param array $data
     */
    public function __invoke($type, $fn, $priority = 0, $data = array())
    {
        return $this->eventManager->add($type, $fn, $priority, $data);
    }
}
