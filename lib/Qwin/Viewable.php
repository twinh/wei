<?php

/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Viewable
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Viewable
{
    /**
     * Render a template
     * 
     * @param string $name The name of template
     * @param array $context The parameters pass to template
     */
    public function render($name, $parameters);
    
    /**
     * Output a rendered template
     * 
     * @param string $name The name of template
     * @param array $context The parameters pass to template
     */
    public function display($name, $parameters);
    
    /**
     * Assign variables to template
     * 
     * @param string $name The name of the variable
     * @param mixed $value The value of the variable
     */
    public function assign($name, $value = null);
}