<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that build a simple REST application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        The code is base on the awesome framework - Kohana
 *              http://kohanaframework.org/3.0/guide/api/Kohana_Route
 * @property    Request $request The HTTP request widget
 * @method      Response response(string $content) Send response header and content
 */
class Router extends Base
{
    /**
     * The routes configurations
     *
     * @var array
     */
    protected $routes = array();

    /**
     * @var array route options
     *
     *      -- pattern          string  the string to be complied to regex
     *
     *      -- rules            array   the regex rules
     *
     *      -- defaults         array   the defaults params of the route
     *
     *      -- method           regex   the request method
     *
     *      -- regex            string  the regex complied from the pattern, just leave it blank when
     *                                  set a new route
     */
    protected $routeOptions = array(
        'pattern'   => null,
        'rules'     => array(),
        'defaults'  => array(),
        'method'    => null,
        'callback'  => null,
        'regex'     => null,
    );

    /**
     * Run the application
     */
    public function __invoke($pathInfo = null, $method = null)
    {
        if (0 === func_num_args()) {
            $request = $this->request;
            $pathInfo = $request->getPathInfo();
            $method = $request->getMethod();
        }

        if (false !== ($parameters = $this->match($pathInfo, $method))) {
            $route = $this->router->getRoute($parameters['_id']);
            unset($parameters['_id']);

            // Merge parameters to query widget
            $this->query->set($parameters);

            array_unshift($parameters, $this->widget);
            $result = call_user_func_array($route['callback'], $parameters);

            return $this->response($result);
        } else {
            throw new \RuntimeException('The page you requested was not found', 404);
        }
    }

    /**
     * Set routes
     *
     * @param array $routes
     * @return Router
     */
    public function setRoutes($routes)
    {
        foreach ($routes as $route) {
            $this->set($route);
        }

        return $this;
    }

    /**
     * Return routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add one route
     *
     * @param  array $route the options of the route
     * @throws \InvalidArgumentException When argument is not string or array
     * @return Router
     */
    public function set($route)
    {
        if (is_string($route)) {
            $this->routes[] = array(
                'pattern' => $route
            ) + $this->routeOptions;
        } elseif (is_array($route)) {
            $this->routes[] = $route + $this->routeOptions;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of type string or array, "%s" given',
                is_object($route) ? get_class($route) : gettype($route)
            ));
        }

        return $this;
    }

    /**
     * Get the route by id
     *
     * @param  string $id The id of the route
     * @return false|array
     */
    public function getRoute($id)
    {
        return isset($this->routes[$id]) ? $this->routes[$id] : false;
    }

    /**
     * Remove the route
     *
     * @param  string      $id The id of the route
     * @return Router
     */
    public function remove($id)
    {
        if (isset($this->routes[$id])) {
            unset($this->routes[$id]);
        }

        return $this;
    }

    /**
     * Prepare the route pattern to regex
     *
     * @param  array  $route the route array
     * @return array
     */
    protected function compile(&$route)
    {
        if ($route['regex']) {
            return $route;
        }

        $regex = preg_replace('#[.\+*?[^\]${}=!|:-]#', '\\\\$0', $route['pattern']);

        $regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);

        $regex = str_replace(array('<', '>'), array('(?P<', '>.+?)'), $regex);

        if ($route['rules']) {
            $search = $replace = array();
            foreach ($route['rules'] as $key => $rule) {
                $search[] = '<' . $key . '>.+?';
                $replace[] = '<' . $key . '>' . $rule;
            }
            $regex = str_replace($search, $replace, $regex);
        }

        $route['regex'] = '#^' . $regex . '$#uUD';

        return $route;
    }

    /**
     * Check if there is a route matches the path info and method,
     * and return the parameters, or return false when not matched
     *
     * @param  string $pathInfo    The path info to match
     * @param  string|null $method The request method to match, maybe GET, POST, etc
     * @return false|array
     */
    public function match($pathInfo, $method = null)
    {
        foreach ($this->routes as $id => $route) {
            if (false !== ($parameters = $this->matchRoute($pathInfo, $method, $id))) {
                return $parameters;
            }
        }

        return false;
    }

    /**
     * Check if the route matches the path info and method,
     * and return the parameters, or return false when not matched
     *
     * @param  string      $pathInfo The path info to match
     * @param  string      $method The request method to match
     * @param  string      $id The id of the route
     * @return false|array
     */
    protected function matchRoute($pathInfo, $method, $id)
    {
        $route = $this->compile($this->routes[$id]);

        // When $route['method'] is not provided, accepts all request methods
        if ($method && $route['method'] && !preg_match('#' . $route['method'] . '#i', $method)) {
            return false;
        }

        // Check if the route matches the path info
        if (!preg_match($route['regex'], $pathInfo, $matches)) {
            return false;
        }

        // Get params in the path info
        $parameters = array();
        foreach ($matches as $key => $parameter) {
            if (is_int($key)) {
                continue;
            }
            $parameters[$key] = $parameter;
        }

        $parameters += $route['defaults'];

        preg_match_all('#<([a-zA-Z0-9_]++)>#', $route['pattern'], $matches);
        foreach ($matches[1] as $key) {
            if (!array_key_exists($key, $parameters)) {
                $parameters[$key] = null;
            }
        }

        return array('_id' => $id) + $parameters;
    }

    /**
     * Build params to query string
     *
     * @param array $parameters The array params to combine
     * @return string
     */
    protected function buildQuery($parameters)
    {
        return $parameters ? '?' . http_build_query($parameters) : '';
    }

    /**
     * @param array $parameters
     * @param string $id
     * @return bool|string
     */
    protected function buildPath($parameters, $id)
    {
        $defaults = $this->routes[$id]['defaults'];
        $pattern = $this->routes[$id]['pattern'];

        // Static route
        if (false === strpos($pattern, '<') && false === strpos($pattern, '(')) {
            // Check if $parameters contains all of the route default params
            $intersect = array_intersect_assoc($parameters, $defaults);
            if ($defaults == $intersect) {
                return $pattern . $this->buildQuery(array_diff_assoc($parameters, $defaults));
            }

            return false;
        } else {
            $route = $this->compile($this->routes[$id]);

            $isMatched = false;

            // Search the minimal optional parts
            while (preg_match('#\([^()]++\)#', $pattern, $match)) {
                // Search for the matched value
                $search = $match[0];

                // Remove the parenthesis from the match as the replace
                $replace = substr($match[0], 1, -1);

                // Search the required parts in the optional parts
                while (preg_match('#<([a-zA-Z0-9_]++)>#', $replace, $match)) {
                    list($key, $param) = $match;

                    if (isset($parameters[$param])) {
                        if (isset($route['rules'][$param])) {
                            // The parameter not matched the rules
                            if (!preg_match('#' . $route['rules'][$param] . '#', $parameters[$param])) {
                                return false;
                            }
                        }

                        // Replace the key with the parameter value
                        $replace = str_replace($key, ($parameters[$param]), $replace);

                        $isMatched = true;

                        unset($parameters[$param]);
                    } else {
                        // This group has missing parameters
                        $replace = '';
                        break;
                    }
                }

                // Replace the group in the pattern
                $pattern = str_replace($search, $replace, $pattern);
            }

            if ('' === $pattern) {
                return false;
            }

            // Search the required parts NOT in the optional parts
            while (preg_match('#<([a-zA-Z0-9_]++)>#', $pattern, $match)) {
                list($key, $param) = $match;

                // Required route parameter not passed
                if (!isset($parameters[$param])) {
                    return false;
                }

                // Check if parameter value matches the regex rule
                if (isset($route['rules'][$param])) {
                    if (!preg_match('#' . $route['rules'][$param] . '#', $parameters[$param])) {
                        return false;
                    }
                }

                $pattern = str_replace($key, ($parameters[$param]), $pattern);

                $isMatched = true;

                unset($parameters[$param]);
            }

            // If nothing matched
            if (!$isMatched && '' === $pattern) {
                return false;
            }

            return $pattern . $this->buildQuery(array_diff_assoc($parameters, $defaults));
        }
    }

    /**
     * Generate url path from the specified array
     *
     * @param  array $parameters
     * @return string
     */
    public function generatePath(array $parameters)
    {
        foreach ($this->routes as $id => $route) {
            if (false !== ($uri = $this->buildPath($parameters, $id))) {
                return $uri;
            }
        }

        return $this->buildQuery($parameters);
    }

    /**
     * Add a GET route
     *
     * @param string $pattern
     * @param callback $fn
     * @return Router
     */
    public function get($pattern, $fn)
    {
        return $this->request($pattern, 'GET', $fn);
    }

    /**
     * Add a POST route
     *
     * @param string $pattern
     * @param callback $fn
     * @return Router
     */
    public function post($pattern, $fn)
    {
        return $this->request($pattern, 'POST', $fn);
    }

    /**
     * Add a DELETE route
     *
     * @param string $pattern
     * @param callback $fn
     * @return Router
     */
    public function delete($pattern, $fn)
    {
        return $this->request($pattern, 'DELETE', $fn);
    }

    /**
     * Add a PUT route
     *
     * @param string $pattern
     * @param callback $fn
     * @return Router
     */
    public function put($pattern, $fn)
    {
        return $this->request($pattern, 'PUT', $fn);
    }

    /**
     * Add a route allow any request methods
     *
     * @param string $pattern The route pattern
     * @param string $method The route method
     * @param callback $fn The callback invoke then route is matched
     * @return Router
     */
    public function request($pattern, $method, $fn = null)
    {
        $argc = func_num_args();
        if (2 == $argc) {
            $fn = $method;
            $method = null;
        }

        $this->router->set(array(
            'pattern' => $pattern,
            'method' => $method,
            'callback' => $fn
        ));

        return $this;
    }
}
