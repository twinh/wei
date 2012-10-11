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
class Smarty extends Widget implements Viewable
{
    /**
     * Smarty object
     *
     * @var \Smarty
     */
    protected $smarty;

    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        // options for \Smarty
        'compile_dir'   => null,
        'templateDir'  => null,

        // options for internal
        'ext'           => '.tpl',
    );

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

        // added default global template variable
        $this->smarty->assign('widget', $this->widgetManager);
    }

    /**
     * Get smarty object
     *
     * @return \Smarty
     */
    public function __invoke()
    {
        return $this->smarty;
    }

    /**
     * @see \Qwin\Viewable::assign
     */
    public function assign($name, $value = null)
    {
        return $this->smarty->assign($name, $value);
    }

    /**
     * @see \Qwin\Viewable::display
     */
    public function display($name, $context = array())
    {
         $context && $this->smarty->assign($context);

         return $this->smarty->display($name);
    }

    /**
     * @see \Qwin\Viewable::render
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
     * @return \Qwin\Smarty
     */
    public function setTemplateDirOption($dir)
    {
        $this->smarty->setTemplateDir($dir);

        $this->options['templateDir'] = $dir;

        return $this;
    }

    /**
     * Set compole directory for smarty object
     *
     * @param  string       $dir
     * @return \Qwin\Smarty
     */
    public function setCompileDirOption($dir)
    {
        $this->smarty->setCompileDir($dir);

        $this->options['compileDir'] = $dir;

        return $this;
    }
}
