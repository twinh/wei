<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * WidgetAware
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class WidgetAware
{
    /**
     * Invoke widget by the given name
     *
     * @param  string $name The name of widget
     * @param  array  $args The arguments for widget's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return Widget::create()->invoke($name, $args);
    }

    /**
     * Get widget instance by the given name
     *
     * @param  string       $name The name of widget
     * @return object
     */
    public function __get($name)
    {
        return $this->$name = Widget::create()->get($name);
    }
}
