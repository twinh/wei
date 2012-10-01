<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

require_once 'Widgetable.php';

/**
 * The base class for all widget
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        global ? shared ? how to defined?
 */
abstract class Widget implements Widgetable
{
    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'widget' => null,
        'invoker' => null,
        'deps' => array(),
    );
    
    /**
     * The widget manager object
     * 
     * @var \Qwin
     */
    protected $widgetManager;

    /**
     * init widget
     *
     * @param mixed $options 对象的值
     * @return Qwin_Widget 当前对象
     */
    public function __construct(array $options = array())
    {
        // TODO what to do with init options ? 
        $this->option($options);
        
        // or ?
        //$this->options = $options + $this->options;

        if (!$options['widget'] instanceof self) {
            throw new \InvalidArgumentException('Option "widget" should be an instance of "' . __CLASS__ . '"');
        }

        $this->widgetManager = &$options['widget'];
    }

    /**
     * Get or set options
     *
     * @param mixed $name option name or options array
     * @param mixed $value
     * @return mixed
     * @example $widget->option('name');            // get "name" option's value
     *          $widget->option('name', 'value');   // set "name" to "value"
     *          $widget->option();                  // get all options
     *          $widget->option(array());           // set options
     * @todo append
     */
    public function option($name = null, $value = null)
    {
        // set options
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->option($k, $v);
            }
            $name = $this->options;
            return $this;
        }

        if (is_string($name) || is_int($name)) {
            // get option
            if (1 == func_num_args()) {
                $method = 'get' . ucfirst($name) . 'Option';
                if (method_exists($this, $method)) {
                    return $this->$method();
                } else {
                    return isset($this->options[$name]) ? $this->options[$name] : null;
                }
            // set option
            } else {
                $method = 'set' . ucfirst($name) . 'Option';
                if (method_exists($this, $method)) {
                    return $this->$method($value);
                } else {
                    return $this->options[$name] = $value;
                }
            }
        }

        // get all options
        if (null === $name) {
            return $this->options;
        }

        // not match any actions
        return null;
    }

    /**
     * 魔术方法,实现通过方法调用同名微件
     *
     * @param string $name 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($name, $args)
    {
        //return call_user_func($this->$name, $name, $args, null, $this);
        return $this->widgetManager->invokeWidget($name, $args, null, $this);
    }

    /**
     * 魔术方法,实现通过对象属性获取同名微件
     *
     * @param string $name the name of widget
     * @return \Qwin\Widget
     */
    public function __get($name)
    {
        return $this->$name = $this->widgetManager->getWidget($name, null, $this);
    }

    // should be implemented by subclasses
    // avoid "Strict standards: Declaration of xxx::__invoke() should be compatible with that of xxx::__invoke()
    //public function __invoke(){}
}
