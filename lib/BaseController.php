<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The base class for controller
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class BaseController extends Base
{
    /**
     * The controller middleware config
     *
     * @var array
     */
    protected $middleware = array();

    /**
     * Register a middleware with specified options
     *
     * @param string $name
     * @param array $options
     * @return $this
     */
    protected function middleware($name, array $options = [])
    {
        $this->middleware[$name] = $options;
        return $this;
    }
}