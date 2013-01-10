<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * NewInstance
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method array config(string $name) Returns the config by specified name
 */
class NewInstance extends WidgetProvider
{
    /**
     * Instance a widget with the specify configuration name
     * 
     * @param string $name The name of widget
     * @param string $configName The name of configuration
     * @return \Widget\WidgetProvider
     */
    public function __invoke($name, $configName)
    {
        $name .= '.' . uniqid();
        $this->widget->config($name, $this->config($configName));
        return $this->widget->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->widget->get($name . '.' . uniqid());
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return $this->widget->invoke($name . '.' . uniqid(), $args);
    }
}
