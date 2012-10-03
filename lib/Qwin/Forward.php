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
 * @todo        WorkFlowBreakNotifyException ?
 */
class Forward extends Widget
{
    public function __invoke($controller, $action = 'index')
    {
        $app = $this->app;
        $app->setControllerName($controller);
        $app->setActionName($action);
        return $app->dispatch($controller, $action);
    }
}