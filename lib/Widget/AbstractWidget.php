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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method mixed __invoke(mixed $mixed) The invoke method
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
     * @var \Widget\Widget
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
     * @todo append
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
        
        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            return $this->$name = $value;
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
