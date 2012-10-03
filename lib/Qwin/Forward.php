<?php
/**
 * Qwin Library
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Forward
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Forward extends Widget
{
    /**
     * Forwards to the given controller and action
     * 
     * @param string $controller The name of controller
     * @param action $action The name of action
     * @return \Qwin\Forward
     */
    public function __invoke($controller, $action = 'index')
    {
        $this->app
            ->setControllerName($controller)
            ->setActionName($action)
            ->dispatch($controller, $action)
            ->preventPreviousDispatch();
        
        return $this;
    }
}