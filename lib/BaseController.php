<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * The base class for controller
 *
 * @author      Twin Huang <twinhuang@qq.com>
 *
 * @property Request    $request A service that handles the HTTP request data
 * @property Response   $response A object that handles the HTTP response data
 */
abstract class BaseController extends Base
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
    public function middleware($name, array $options = array())
    {
        $this->middleware[$name] = $options;
        return $this;
    }

    /**
     * The callback triggered before execute the action
     *
     * @param Request $req
     * @param Response $res
     */
    public function before($req, $res)
    {

    }

    /**
     * The callback triggered after execute the action
     *
     * @param Request $req
     * @param Response $res
     */
    public function after($req, $res)
    {

    }
}