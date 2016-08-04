<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
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

    /**
     * @var callable
     */
    protected $parseResource;

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
     * @param string $name The name of template
     * @param array $data The variables pass to template
     * @return null|string
     * @throws \Exception View file not found
     */
    public function render($name, $data = array())
    {
        // Assign to double underscored variables to avoid conflict with view parameters
        $__file = $this->getFile($name);
        $__data = $data + $this->data;
        $__layout = $this->layout;

        // Render view
        extract($__data, EXTR_OVERWRITE);
        ob_start();
        try {
            require $__file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        $content = ob_get_clean();

        // If layout is configured, render layout
        if ($__layout != $this->layout) {
            $layout = $this->layout;
            $this->layout = array();
            $__data[$layout['variable']] = $content;
            $content = $this->render($layout['name'], $__data);
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
            $components = $this->parseResource($name);
            throw new \RuntimeException(sprintf('Template "%s" not found in directories "%s"', $components['file'], implode('", "', $components['dirs'])));
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
        $components = $this->parseResource($name);
        foreach ($components['dirs'] as $dir) {
            if (is_file($file = $dir . ($dir ? '/' : '') .  $components['file'])) {
                return $file;
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @return array
     */
    protected function parseResource($name)
    {
        $dirs = $this->dirs;
        if ($this->parseResource) {
            $components = call_user_func($this->parseResource, $name);
            if ($components['path']) {
                $dirs[] = $components['path'];
            }
            $name = $components['file'];
        }
        return array('dirs' => $dirs, 'file' => $name);
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
     * Returns the base directories
     *
     * @return array
     */
    public function getDirs()
    {
        return $this->dirs;
    }

    /**
     * Set the base directories
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
