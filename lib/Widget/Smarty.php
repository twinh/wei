<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\View\AbstractView;

/**
 * The smarty widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Smarty extends AbstractView
{
    /**
     * Smarty object
     *
     * @var \Smarty
     */
    protected $smarty;
    
    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.tpl';

    /**
     * Options for \Smarty
     *
     * @var array
     */
    public $options = array(
        'compile_dir'   => null,
        'template_dir'  => null,
    );

    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->smarty = new \Smarty();

        // TODO better way
        \Smarty::unmuteExpectedErrors();

        parent::__construct($options);

        foreach ($this->options as $key => $value) {
            if (isset($this->smarty->$key)) {
                $this->smarty->$key = $value;
            }
        }

        // Adds default global template variable
        $this->smarty->assign('widget', $this->widget);
    }
    
    /**
     * Returns \Smarty object or render a template
     * 
     * if NO parameter provied, the invoke method will return the \Smarty 
     * object otherwise, call the render method
     *
     * @param string $name The name of template
     * @param array $context The variables pass to template
     * 
     * @return \Smarty|string
     */
    public function __invoke($name = null, $context = array())
    {
        if (0 === func_num_args()) {
            return $this->smarty;
        } else {
            return $this->render($name, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assign($name, $value = null)
    {
        $this->smarty->assign($name, $value);
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function display($name, $context = array())
    {
         $context && $this->smarty->assign($context);

         return $this->smarty->display($name);
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, $context = array())
    {
        $context && $this->smarty->assign($context);

        return $this->smarty->fetch($name);
    }
}
