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
 * The base class for all widgets
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class Widget implements Widgetable
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
     * @var \Qwin\WidgetManager
     */
    protected $widgetManager;
    
    /**
     * Constructor
     *
     * @param  array        $options The options
     * @return \Qwin\Widget
     */
    public function __construct(array $options = array())
    {
        $this->option($options);

        if (!isset($this->widgetManager)) {
            $this->widgetManager = WidgetManager::create();
        } elseif (!$this->widgetManager instanceof self) {
            throw new \InvalidArgumentException('Option "widget" should be an instance of "' . __CLASS__ . '"');
        }
    }
    
    /**
     * Get or set options
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
            if (in_array($name, $this->getNonOptionalProperties())) {
                throw new \InvalidArgumentException(sprintf('Cannot access non-optional property %s', $name));
            }
            
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

        throw new \InvalidArgumentException();
    }

    /**
     * Return the non-optional properties for option method
     * 
     * @return array
     */
    protected function getNonOptionalProperties()
    {
        return array();
    }

    /**
     * Invoke widget by the given name
     *
     * @param  string $name The name of widget
     * @param  array  $args The arguments for widget's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        //return call_user_func_array($this->$name, $args);
        return $this->widgetManager->invoke($name, $args, null, $this->deps);
    }

    /**
     * Get widget instance by the given name
     *
     * @param  string       $name The name of widget
     * @return \Qwin\Widget
     */
    public function __get($name)
    {
        return $this->$name = $this->widgetManager->get($name, null, $this->deps);
    }

    // should be implemented by subclasses
    // avoid "Strict standards: Declaration of xxx::__invoke() should be compatible with that of xxx::__invoke()
    //public function __invoke(){}
}
