<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * On
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class On extends WidgetProvider
{
    /**
     * 绑定事件
     *
     * @see Event::add()
     * @param  string $event    事件名称
     * @param  mixed  $callback 回调结构
     * @return Widget
     */
    public function __invoke($event, $callback, $priority = 10)
    {
        return $this->eventManager->add($event, $callback, $priority);
    }
}
