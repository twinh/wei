<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

require_once 'Widgetable.php';

/**
 * The base class for all widgets
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class WidgetProvider implements Widgetable
{
    /**
     * The default dependence map
     * 
     * @var array
     */
    protected $deps = array();
    
    /**
     * The widget manager object
     * 
     * @var \Widget\Widget
     */
    protected $widget;
    
    /**
     * Constructor
     *
     * @param  array        $options The options
     * @return \Widget\Widget
     */
    public function __construct(array $options = array())
    {
        $this->option($options);

        if (!isset($this->widget)) {
            $this->widget = Widget::create();
        } elseif (!$this->widget instanceof self) {
            throw new \InvalidArgumentException('Option "widget" should be an instance of "' . __CLASS__ . '"');
        }
    }
    
    /**
     * Get or set property value
     *
     * @param  mixed $name  option name or options array
     * @param  mixed $value
     * @return mixed
     * @example $widget->option('name');            // get "name" option's value
     *          $widget->option('name', 'value');   // set "name" to "value"
     *          $widget->option();                  // get all options
     *          $widget->option(array());           // set options
     * @todo append
     * @todo how to init all or partail options when class construct ?
     */
    public function option($name = null, $value = null)
    {
        // set options
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->option($k, $v);
            }
            return $this;
        }

        if (is_string($name)) {
            // get option
            if (1 == func_num_args()) {
                $method = 'get' . ucfirst($name);
                if (method_exists($this, $method)) {
                    return $this->$method();
                } else {
                    // 必需是定义的列表中
                    return isset($this->$name) ? $this->$name : null;
                }
            // set option
            } else {
                $method = 'set' . ucfirst($name);
                if (method_exists($this, $method)) {
                    return $this->$method($value);
                } else {
                    return $this->$name = $value;
                }
            }
        }

        // get all options as array ?
        //if (null === $name) {
        //    return $this->options;
        //}
        throw new \InvalidArgumentException(sprintf(
            'Parameter 1 for option method should be string or array, %s given', 
            (is_object($name) ? get_class($name) : gettype($name))
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    { 
        //return call_user_func_array($this->__get($name), $args);
        return $this->widget->invoke($name, $args, $this->deps);
    }
    
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->$name = $this->widget->get($name, $this->deps);
    }
}
