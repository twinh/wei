<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The infterce for all widget
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Widgetable
{
    /**
     * Get the widget object by the given name
     *
     * @param  string       $name The name of widget
     * @return \Widget\Widget
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

    /**
     * The method __invoke should be implemented by subclasses, the comment here
     * is to avoid "Fatal error: Declaration of zzz::__invoke() must be 
     * compatible with that of Widget\Widgetable::__invoke() in xxx
     */
    //public function __invoke();
}
