<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Viewable
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Viewable
{
    /**
     * Render a template
     *
     * @param string $name    The name of template
     * @param array  $context The variables pass to template
     * @return string|null
     */
    public function render($name, $context = array());

    /**
     * Output a rendered template
     *
     * @param string $name    The name of template
     * @param array  $context The variables pass to template
     * @return void
     */
    public function display($name, $context = array());

    /**
     * Assign variables to template
     *
     * @param string $name  The name of the variable
     * @param mixed  $value The value of the variable
     * @return View|null
     */
    public function assign($name, $value = null);

    /**
     * Get default template file extension, for automatic render template only
     */
    public function getExtension();
}
