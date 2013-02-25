<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The infterce for all widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface WidgetAwareInterface
{
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