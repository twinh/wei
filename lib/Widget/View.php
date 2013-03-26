<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\View\AbstractView;
use Widget\Exception\RuntimeException;

/**
 * The view widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class View extends AbstractView
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
    protected $dirs = array('.');

    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.php';
    
    /**
     * The layout configuration
     * 
     * @var array|null
     */
    protected $layout;

    /**
     * Returns view widget
     *
     * @return \Widget\View
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, $context = array())
    {
        // Set extra view variables
        $context = $context ? $context + $this->vars : $this->vars;
        
        // Render view
        extract($context, EXTR_OVERWRITE);
        ob_start();
        require $this->getFile($name);
        $content = ob_get_clean();
        
        // Render layout
        if ($this->layout) {
            $layout = $this->layout;
            $this->layout = null;
            $content = $this->render($layout['name'], array(
                $layout['variable'] => $content
            ) + $context);
        }
        
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function display($name, $context = array())
    {
        echo $this->render($name, $context);
    }

    /**
     * {@inheritdoc}
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

        throw new RuntimeException(sprintf('Template "%s" not found in directories "%s"', $name, implode('", "', $this->dirs)));
    }
    
    /**
     * Set layout for current view
     * 
     * @param string $name The name of layout template 
     * @param string $variable The varibale name that 
     * @return \Widget\View
     */
    public function layout($name, $variable = 'content')
    {
        $this->layout = array(
            'name' => $name,
            'variable' => $variable
        );
        
        return $this;
    }
}
