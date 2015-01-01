<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that use to render PHP template
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class View extends Base implements \ArrayAccess
{
    /**
     * The template variables
     *
     * @var array
     */
    protected $data = array();

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
     * The default layout path for layout method
     *
     * @var string
     */
    protected $defaultLayout;

    /*
     * The current render view variables
     *
     * @var array
     */
    private $currentData = array();

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
     * @param array $data The variables pass to template
     *
     * @return string
     */
    public function __invoke($name = null, $data = array())
    {
        return $this->render($name, $data);
    }

    /**
     * Render a template
     *
     * @param string $name  The name of template
     * @param array  $data  The variables pass to template
     * @return string|null
     */
    public function render($name, $data = array())
    {
        // Assign $name to $this->currentName to avoid conflict with view parameter
        $this->currentName = $name;

        // Set extra view variables
        $this->currentData = $data + $this->data;

        // Render view
        extract($this->currentData, EXTR_OVERWRITE);
        ob_start();
        require $this->getFile($this->currentName);
        $content = ob_get_clean();

        // Render layout
        if ($this->layout) {
            $layout = $this->layout;
            $this->layout = array();
            $content = $this->render($layout['name'], array(
                $layout['variable'] => $content
            ) + $this->currentData);
        }

        return $content;
    }

    /**
     * Output a rendered template
     *
     * @param string $name  The name of template
     * @param array  $data  The variables pass to template
     * @return void
     */
    public function display($name, $data = array())
    {
        echo $this->render($name, $data);
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
            $this->data = $name + $this->data;
        } else {
            $this->data[$name] = $value;
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
        return isset($this->data[$name]) ? $this->data[$name] : null;
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
        if ($file = $this->resolveFile($name)) {
            return $file;
        } else {
            throw new \RuntimeException(sprintf('Template "%s" not found in directories "%s"', $name, implode('", "', $this->dirs)));
        }
    }

    /**
     * Resolve the template file by name
     *
     * @param  string $name The name of template
     * @return string|false The template file path or false if file not found
     */
    public function resolveFile($name)
    {
        foreach ($this->dirs as $dir) {
            if (is_file($file = $dir . ($dir ? '/' : '') .  $name)) {
                return $file;
            }
        }
        return false;
    }

    /**
     * Set layout file for current view
     *
     * @param string $name The name of layout template
     * @param string $variable The variable name of layout content
     * @return $this
     */
    public function layout($name = null, $variable = 'content')
    {
        $this->layout = array(
            'name' => $name ?: $this->defaultLayout,
            'variable' => $variable
        );
        return $this;
    }

    /**
     * Set the default layout parameter
     *
     * @param string $defaultLayout
     * @return $this
     */
    public function setDefaultLayout($defaultLayout)
    {
        $this->defaultLayout = $defaultLayout;
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

    /**
     * Check if the offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Get the offset value
     *
     * @param  string $offset
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Set the offset value
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
