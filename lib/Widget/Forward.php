<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Forward
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property \Widget\App $app The application widget
 */
class Forward extends WidgetProvider
{
    /**
     * Forwards to the given module, controller and action
     * 
     * @param string $action        The name of action
     * @param string $controller    The name of controller
     * @param string $module        The name of module
     * @return \Widget\Forward
     */
    public function __invoke($action = 'index', $controller = null , $module = null)
    {
        $this->app
            ->setModule($module)
            ->setController($controller)
            ->setAction($action)
            ->dispatch($module, $controller, $action)
            ->preventPreviousDispatch();

        return $this;
    }
}
