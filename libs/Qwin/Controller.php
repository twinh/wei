<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Controller
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Controller extends Widget
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        if ('Qwin_Controller' != get_class($this)) {
            $this->init();
        }
    }

    /**
     * 根据模块获取控制器对象
     *
     * @param string $module 模块名称
     * @param bool $instance 是否实例化
     * @param mixed $param 参数
     * @return Qwin_Controller
     */
    public function __invoke($module = null, $instance = true, $param = null)
    {
        if (is_array($module)) {
            $options = $module;

            $controller = $this->__invoke($options['module']);

            if (!$controller || !method_exists($controller, $options['action'] . 'Action')) {
                return $this->error('The page you requested was not found.', 404);
            }

            return call_user_func(array($controller, $options['action'] . 'Action'));
        }


        $module = ucfirst($module);
        // 检查模块控制器文件是否存在
        $found = false;
        foreach ($this->app->options['dirs'] as $dir) {
            $file = $dir . '/' . $module . '/Controller.php';
            if (is_file($file)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->log('Module "' . $module . '" not found.');
            return false;
        }

        require_once $file;
        $class = $module . '_Controller';
        if (!class_exists($class)) {
            $this->log('Controller ' . $class . ' not found.');
            return false;
        }

        return $instance ? $this->qwin($class, $param) : $class;
    }

    /**
     * 初始化方法,于__construct方法之后调用
     *
     * @return Qwin_Controller 当前对象
     */
    public function init()
    {
        return $this;
    }

    /**
     * Execute action
     *
     * @param mixed $action
     * @return mixed
     */
    public function execute($action)
    {
        $action = (string)$action;
        if ($action) {
            $action2 = $action . 'Action';
            if (method_exists($this, $action2)) {
                return call_user_func(array($this, $action2));
            }
        }

        $this->log(sprintf('Action "%s" not found in controller "%s".', $action, get_class($this)));

        return false;
    }

    /**
     * Set or get options
     *
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
    public function option($name = null, $value = null)
    {
        // load options data from controller "options" dir
        if (1 == func_num_args() && (is_string($name) || is_int($name))) {
            if (!isset($this->options[$name])) {
                foreach ($this->app->options['dirs'] as $dir) {
                    $file = $dir . '/' . ucfirst($this->module()) . '/options/'  . $name . '.php';
                    if (is_file($file)) {
                        $this->options[$name] = require $file;
                        break;
                    }
                }
            }
            // get option
            return parent::option($name);
        }
        // other actions
        return parent::option($name, $value);
    }

    public function forward($controller, $action = 'index')
    {
        $this->app(array(
            'module' => $controller,
            'action' => $action,
        ));

        return $this;
    }

    public function redirect($url)
    {
        // todo set header location and delay
    }

    public function assign($name, $value = null)
    {
        return $this->view->assign($name, $value);
    }

    public function render()
    {

    }
}
