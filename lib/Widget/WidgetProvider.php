<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @see Widget\Widgetable
 */
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
     * @param  array        $options The property options
     * @return \Widget\WidgetProvider
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
     * @param  mixed $name  The option name or options array
     * @param  mixed $value 
     * @return mixed
     * @example $widget->option('name');            // Get "name" option's value
     *          $widget->option('name', 'value');   // Set "name" to "value"
     *          $widget->option();                  // Get all options
     *          $widget->option(array());           // Set options
     * @todo append
     */
    public function option($name = null, $value = null)
    {
        // Set options
        if (is_array($name)) {
            if (is_array($value)) {
                $name += array_intersect_key(get_object_vars($this), array_flip($value));
            }
            foreach ($name as $k => $v) {
                $this->option($k, $v);
            }
            return $this;
        }

        if (is_string($name)) {
            // Get option
            if (1 == func_num_args()) {
                if (method_exists($this, $method = 'get' . $name)) {
                    return $this->$method();
                } else {
                    return isset($this->$name) ? $this->$name : null;
                }
            // Set option
            } else {
                if (method_exists($this, $method = 'set' . $name)) {
                    return $this->$method($value);
                } else {
                    return $this->$name = $value;
                }
            }
        }

        // Get all property options
        if (null === $name) {
            return get_object_vars($this);
        }
        
        throw new \InvalidArgumentException(sprintf(
            'Parameter 1 for option method should be string, array or null, %s given', 
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
