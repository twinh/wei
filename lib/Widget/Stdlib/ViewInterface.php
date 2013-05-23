<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

/**
 * The interface for view widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
interface ViewInterface
{
    /**
     * Render a template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return string|null
     */
    public function render($name, $vars = array());

    /**
     * Output a rendered template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return void
     */
    public function display($name, $vars = array());

    /**
     * Assign variables to template
     *
     * @param string $name  The name of the variable
     * @param mixed  $value The value of the variable
     * @return ViewInterface
     */
    public function assign($name, $value = null);

    /**
     * Get default template file extension, such as php, tpl, this is useful for
     * automatic render template
     *
     * @return string
     */
    public function getExtension();
}
