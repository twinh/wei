<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The base class for controller
 *
 * @author      Twin Huang <twinhuang@qq.com>
 *
 * @property Req $req A service that handles the HTTP request data
 * @property Res $res A object that handles the HTTP response data
 */
abstract class BaseController extends Base
{
    /**
     * The controller middleware config
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Return the registered middleware
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Register a middleware with specified options
     *
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function middleware($name, array $options = [])
    {
        $this->middleware[$name] = $options;
        return $this;
    }

    /**
     * Remove a middleware
     *
     * @param string $name
     * @return $this
     */
    public function removeMiddleware(string $name): self
    {
        unset($this->middleware[$name]);
        return $this;
    }

    /**
     * Initialize the controller, can be used to register middleware
     *
     * @experimental
     */
    public function init()
    {
    }

    /**
     * The callback triggered before execute the action
     *
     * @param Req $req
     * @param Res $res
     */
    public function before($req, $res)
    {
    }

    /**
     * The callback triggered after execute the action
     *
     * @param Req $req
     * @param Res $res
     */
    public function after($req, $res)
    {
    }
}
