<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A interface for developers to get and invoke widget in their objects
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface WidgetAwareInterface
{
    /**
     * Sets the widget manager
     * 
     * @param \Widget\Widget $widget A widget manager instance
     * @return \Widget\WidgetAwareInterface
     */
    public function setWidget(Widget $widget);
    
    /**
     * Get the widget object by the given name
     *
     * @param  string       $name The name of widget
     * @return \Widget\WidgetInterface
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