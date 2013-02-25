<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\View\ViewInterface;

/**
 * The view widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class View extends AbstractWidget implements ViewInterface
{
    /**
     * The template variables
     * 
     * @var array
     */
    protected $vars = array();
    
    /**
     * Template directory
     *
     * @var string|array
     */
    protected $dirs = array();

    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.php';

    /**
     * Get view object
     *
     * @return View
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @see \Widget\Viewable::render
     * @param array $context
     */
    public function render($name, $context = array())
    {
        $tmpl = new Tmpl(array(
            'name' => $name,
            'view' => $this,
            'widget' => $this->widget
        ));

        return $tmpl->render($context);
    }

    /**
     * @see \Widget\Viewable::display
     * @param array $context
     */
    public function display($name, $context = array())
    {
        echo $this->render($name, $context);
    }

    /**
     * @see \Widget\Viewable::assign
     */
    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->vars = $name + $this->vars;
        } else {
            $this->vars[$name] = $value;
        }

        return $this;
    }

    /**
     * @see \Widget\Viewable::getExtension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get the template file by name
     *
     * @param  string    $name The name of template
     * @return string    The template file path
     * @throws Exception When file not found
     */
    public function getFile($name)
    {
        foreach ($this->dirs as $dir) {
            if (is_file($file = $dir . '/' .  $name)) {
                return $file;
            }
        }

        throw new Exception(sprintf('Template "%s" not found in directories "%s"', $name, implode('", "', $this->dirs)));
    }
}
