<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\Widget;
use Widget\AbstractWidget;

/**
 * A interface for developers to get and invoke widget in their objects
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
interface WidgetAwareInterface
{
    /**
     * Sets the widget manager
     *
     * @param Widget $widget A widget manager instance
     * @return WidgetAwareInterface
     */
    public function setWidget(Widget $widget);

    /**
     * Get the widget object by the given name
     *
     * @param  string       $name The name of widget
     * @return AbstractWidget
     */
    public function __get($name);

    /**
     * Invoke the widget by the given name
     *
     * @param  string $name The name of widget
     * @param  array  $args The arguments for widget's __invoke method
     * @return mixed
     */
    public function __call($name, $args);
}
