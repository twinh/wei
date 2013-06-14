<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\AbstractWidget;

/**
 * The base class for view widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractView extends AbstractWidget
{
    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.php';

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Render a template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return string|null
     */
    abstract public function render($name, $vars = array());

    /**
     * Output a rendered template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return void
     */
    abstract public function display($name, $vars = array());

    /**
     * Assign variables to template
     *
     * @param string $name  The name of the variable
     * @param mixed  $value The value of the variable
     * @return AbstractView
     */
    abstract public function assign($name, $value = null);
}
