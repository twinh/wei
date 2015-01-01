<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace WeiExtension;
use Wei\Base;

/**
 * A wrapper widget for Twig object
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Twig extends Base
{
    /**
     * Options for \Twig_Environment
     *
     * @var array
     * @see \Twig_Environment::__construct
     */
    protected $envOptions = array();

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
    protected $object;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if (!$this->object) {
            $this->object = new \Twig_Environment(new \Twig_Loader_Filesystem($this->paths), $this->envOptions);
        }

        // Adds wei to template variable
        $this->object->addGlobal('wei', $this->wei);
    }

    /**
     * Returns \Twig_Environment object or render a Twig template
     *
     * if NO parameter provied, the invoke method will return the
     * \Twig_Environment object. otherwise, call the render method
     *
     * @param string $name The name of template
     * @param array $vars The variables pass to template
     *
     * @return \Twig_Environment|string
     */
    public function __invoke($name = null, $vars = array())
    {
        if (0 === func_num_args()) {
            return $this->object;
        } else {
            return $this->render($name, $vars);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->object->addGlobal($key, $value);
            }
        } else {
            $this->object->addGlobal($name, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function display($name, $vars = array())
    {
        return $this->object->display($name, $vars);
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, $vars = array())
    {
        return $this->object->render($name, $vars);
    }
}
