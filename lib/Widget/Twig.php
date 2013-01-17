<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Twig
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Twig extends WidgetProvider implements Viewable
{
    /**
     * Options for \Twig_Environment
     *
     * @var array
     * @see \Twig_Environment::__construct
     */
    protected $envOptions = array(
        'debug'                 => false,
        'charset'               => 'UTF-8',
        'base_template_class'   => 'Twig_Template',
        'strict_variables'      => false,
        'autoescape'            => 'html',
        'cache'                 => false,
        'auto_reload'           => null,
        'optimizations'         => -1,
    );

    /**
     * Path for \Twig_Loader_Filesystem
     *
     * @var string|array
     */
    protected $paths = array();

    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.html.twig';

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
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->paths), $this->envOptions);

        // add in common use object
        $this->twig->addGlobal('widget', $this->widget);
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
     * @see \Widget\Viewable::assign
     */
    public function assign($name, $value = null)
    {
        $this->twig->addGlobal($name, $name);
    }

    /**
     * @see \Widget\Viewable::display
     * @param array $context
     */
    public function display($name, $context = array())
    {
        return $this->twig->display($name, $context);
    }

    /**
     * @see \Widget\Viewable::render
     * @param array $context
     */
    public function render($name, $context = array())
    {
        return $this->twig->render($name, $context);
    }

    /**
     * @see \Widget\Viewable::getExtension
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
