<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A simple implementation of \Widget\WidgetAwareInterface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractWidgetAware implements WidgetAwareInterface
{
    /**
     * A widget manager instance
     * 
     * @var Widget
     */
    protected $widget;
    
    /**
     * {@inheritdoc}
     */
    public function setWidget(Widget $widget)
    {
        $this->widget = $widget;
        
        return $this;
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
        return $this->$name = $this->widget->get($name);
    }
}
