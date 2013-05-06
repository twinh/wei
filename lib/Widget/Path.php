<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * Alias of $router->generatePath widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Router $router Router
 */
class Path extends AbstractWidget
{
    public function __invoke(array $parameters = array(), $name = null)
    {
        return $this->router->generatePath($parameters, $name);
    }
}
