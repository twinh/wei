<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
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
