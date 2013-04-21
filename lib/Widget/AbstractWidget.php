<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @see Widget\WidgetInterface
 */
require_once 'WidgetInterface.php';

/**
 * The base class for all widgets
 *
 * @author   Twin Huang <twinhuang@qq.com>
 * @method   mixed __invoke(mixed $mixed) The invoke method
 * @property Apc $apc The PHP APC cache widget 
 * @method   mixed apc($key, $value = null, $expire = 0) Retrieve or store an item
 * @property App $app The application widget
 * @method   App app(array $options = array()) Startup application
 */
abstract class AbstractWidget implements WidgetInterface
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
     * @var Widget
     */
    protected $widget;

    /**
     * Constructor
     *
     * @param  array        $options The property options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->setOption($options);

        if (!isset($this->widget)) {
            $this->widget = Widget::create();
        } elseif (!$this->widget instanceof WidgetInterface) {
            throw new \InvalidArgumentException(sprintf('Option "widget" of class "%s" should be an instance of "WidgetInterface"', get_class($this)));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value = null)
    {
        // Set options
        if (is_array($name)) {
            if (is_array($value)) {
                $name += array_intersect_key(get_object_vars($this), array_flip($value));
            }
            foreach ($name as $k => $v) {
                $this->setOption($k, $v);
            }
            return $this;
        }
        
        // Append option
        if (isset($name[0]) && '+' == $name[0]) {
            return $this->appendOption(substr($name, 1), $value);
        }
        
        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            $this->$name = $value;
            return $this;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOption($name = null)
    {
        // Returns all property options
        if (null === $name) {
            return get_object_vars($this);
        }
        
        if (method_exists($this, $method = 'get' . $name)) {
            return $this->$method();
        } else {
            return isset($this->$name) ? $this->$name : null;
        }
    }
    
    /**
     * Append property value
     * 
     * @param string $name
     * @param array $value
     */
    public function appendOption($name, array $value) 
    {
        $this->$name = (array)$this->$name + $value;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->$name = $this->widget->get($name, array(), $this->deps);
    }
}
