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
interface WidgetInterface
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
    
    /**
     * Get or set property value
     * 
     * @param string|array $name
     * @param string|array $value
     */
    public function option($name = null, $value = null);
    
    /**
     * Invoke the current widget
     *
     * The method __invoke should be implemented by subclasses, the comment here
     * is to avoid "Fatal error: Declaration of xxx::__invoke() must be
     * compatible with that of Widget\WidgetInterface::__invoke() in xxx", 
     * because php does NOT accept dynamic arguments in magic method __invoke
     */
    //public function __invoke();
}
