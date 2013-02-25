<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The infterce for all widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class AbstractWidgetAware implements WidgetAwareInterface
{
    /**
     * The widget manager
     * 
     * @var \Widget\Widget
     */
    protected $widget;
    
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