<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that use to render PHP template
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class View extends Base
{
    /**
     * The template variables
     *
     * @var array
     */
    protected $vars = array();

    /**
     * The directories to find template
     *
     * @var array
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
     * @var array
     */
    protected $layout = array();

    /**
     * The current render view name
     *
     * @var string
     */
    private $currentName;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Adds service container and view service to template variables
        $this->assign(array(
            'wei'   => $this->wei,
            'view'  => $this,
        ));
    }

    /**
     * Render a PHP template
     *
     * @param string $name The name of template
     * @param array $vars The variables pass to template
     *
     * @return string
     */
    public function __invoke($name = null, $vars = array())
    {
        return $this->render($name, $vars);
    }

    /**
     * Render a template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return string|null
     */
    public function render($name, $vars = array())
    {
        // Set extra view variables
        $vars = $vars ? $vars + $this->vars : $this->vars;

        // Assign $name to $this->currentName to avoid conflict with view parameter
        $this->currentName = $name;

        // Render view
        extract($vars, EXTR_OVERWRITE);
        ob_start();
        require $this->getFile($this->currentName);
        $content = ob_get_clean();

        // Render layout
        if ($this->layout) {
            $layout = $this->layout;
            $this->layout = array();
            $content = $this->render($layout['name'], array(
                $layout['variable'] => $content
            ) + $vars);
        }

        return $content;
    }

    /**
     * Output a rendered template
     *
     * @param string $name  The name of template
     * @param array  $vars  The variables pass to template
     * @return void
     */
    public function display($name, $vars = array())
    {
        echo $this->render($name, $vars);
    }

    /**
     * Assign variables to template
     *
     * @param string $name  The name of the variable
     * @param mixed  $value The value of the variable
     * @return $this
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
     * Returns the variable value or null if not defined
     *
     * @param string $name The name of variable
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * Get the template file by name
     *
     * @param  string    $name The name of template
     * @return string    The template file path
     * @throws \RuntimeException When template file not found
     */
    public function getFile($name)
    {
        foreach ($this->dirs as $dir) {
            if (is_file($file = $dir . '/' .  $name)) {
                return $file;
            }
        }
        throw new \RuntimeException(sprintf('Template "%s" not found in directories "%s"', $name, implode('", "', $this->dirs)), 404);
    }

    /**
     * Set layout for current view
     *
     * @param string $name The name of layout template
     * @param string $variable The variable name of layout content
     * @return $this
     */
    public function layout($name, $variable = 'content')
    {
        $this->layout = array(
            'name' => $name,
            'variable' => $variable
        );
        return $this;
    }

    /**
     * Set base directory for views
     *
     * @param string|array $dirs
     * @return $this
     */
    public function setDirs($dirs)
    {
        $this->dirs = (array)$dirs;
        return $this;
    }

    /**
     * Get default template file extension, such as php, tpl, this is useful for
     * automatic render template
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
