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
     * Options
     *
     * @var array
     */
    public $options = array(
        // options for \Smarty
        'compile_dir'   => null,
        'templateDir'  => null,
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
     * Return the \Smarty object
     *
     * @return \Smarty
     */
    public function __invoke()
    {
        return $this->smarty;
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

    /**
     * Set template directory for smarty object
     *
     * @param  string|array $dir
     * @return \Smarty
     */
    public function setTemplateDir($dir)
    {
        $this->smarty->setTemplateDir($dir);

        $this->options['templateDir'] = $dir;

        return $this;
    }

    /**
     * Set compole directory for smarty object
     *
     * @param  string       $dir
     * @return \Smarty
     */
    public function setCompileDir($dir)
    {
        $this->smarty->setCompileDir($dir);

        $this->options['compileDir'] = $dir;

        return $this;
    }
}
