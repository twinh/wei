<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Twig
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Twig extends Widget implements Viewable
{
    /**
     * Options
     * 
     * @var array
     * @see \Twig_Environment
     * @see \Twig_Loader_Filesystem
     * @todo integrate with debug widget
     */
    public $options = array(
        // Options for \Twig_Loader_Filesystem
        'paths'                 => array(),
        'debug'                 => false,
        'charset'               => 'UTF-8',
        'base_template_class'   => 'Twig_Template',
        'strict_variables'      => false,
        'autoescape'            => 'html',
        'cache'                 => false,
        'auto_reload'           => null,
        'optimizations'         => -1,
        
        // options for \Qwin\Twig
        'ext'                   => '.html.twig'
    );
    
    /**
     * The twig environment object
     * 
     * @return \Twig_Environment
     */
    protected $twig;

    /**
     * Constructor
     * 
     * @var array 
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->options['paths']), $this->options);
        
        $this->twig->addGlobal('widget', $this->widgetManager);
        
        $this->twig->addGlobal('app', $this->app);
    }
    
    /**
     * Get twig environment object
     * 
     * @return \Twig_Environment
     */
    public function __invoke()
    {
        return $this->twig;
    }

    /**
     * @see \Qwin\Viewable::assign
     */
    public function assign($name, $value = null)
    {
        $this->twig->addGlobal($name, $name);
    }

    /**
     * @see \Qwin\Viewable::display
     */
    public function display($name, $context = array())
    {
        return $this->twig->display($name, $context);
    }

    /**
     * @see \Qwin\Viewable::render
     */
    public function render($name, $context = array())
    {
        return $this->twig->render($name, $context);
    }
}