<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The router widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        The code is base on the awesome framework - Kohana
 *              http://kohanaframework.org/3.0/guide/api/Kohana_Route
 */
class Router extends AbstractWidget
{
    /**
     * Routes configurations
     *
     * @var array
     */
    protected $routes = array();

    /**
     * @var array route options
     *
     *      -- name             string  the name of the route
     *
     *      -- uri              string  the string to be complied to regex
     *
     *      -- rules            array   the regex rules
     *
     *      -- defaults         array   the defaults params of the route
     *
     *      -- method           regex   the request method
     * 
     *      -- regex            string  the regex complied from the uri, just leave it blank when
     *                                  set a new route
     */
    protected $routeOptions = array(
        'name'      => null,
        'callback'  => null,
        'pattern'   => null,
        'rules'     => array(),
        'defaults'  => array(),
        'method'    => null,
        'regex'     => null,
    );

    /**
     * Returns the router object
     *
     * @return \Widget\Router
     */
    public function __invoke()
    {
        return $this;
    }

    public function setRoutes($routes)
    {
        foreach ($routes as &$route) {
            $route += $this->routeOptions;
        }
        $this->routes = $routes;

        return $this;
    }

    /**
     * Set route
     *
     * @param  array       $route the options of the route
     * @return Router
     */
    public function set(array $route)
    {
        $route =  $route + $this->routeOptions;

        if (!$route['name']) {
            $this->routes[] = $route;
            //array_unshift($this->routes, $route);
        } else {
            $this->routes = array($route['name'] => $route) + $this->routes;
        }

        return $this;
    }

    /**
     * Get the route by name
     *
     * @param  string $name the name of the route
     * @return array|null
     */
    public function get($name)
    {
        return isset($this->routes[$name]) ? $this->routes[$name] : null;
    }

    /**
     * Remove the route
     *
     * @param  string      $name the name of the route
     * @return \Widget\Router
     */
    public function remove($name)
    {
        if (isset($this->routes[$name])) {
            unset($this->routes[$name]);
        }

        return $this;
    }

    /**
     * Prepare the route uri to regex
     *
     * @param  array  $route the route array
     * @return array
     */
    protected function _compile(&$route)
    {
        if ($route['regex']) {
            return $route;
        }

        $regex = $route['uri'];

        $regex = preg_replace('#[.\+*?[^\]${}=!|]#', '\\\\$0', $regex);

        $regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);

        $regex = str_replace(array('<', '>'), array('(?P<', '>[^/?]++)'), $regex);

        if ($route['rules']) {
            $search = $replace = array();
            foreach ($route['rules'] as $key => $rule) {
                $search[] = '<' . $key . '>[^/?]++';
                $replace[] = '<' . $key . '>' . $rule;
            }
            $regex = str_replace($search, $replace, $regex);
        }

        $route['regex'] = '#^' . $regex . '$#uD';

        return $route;
    }

    /**
     * Check if there is a route matches the uri and method,
     * and return the parameters, or return false when not matched
     *
     * @param  string $pathInfo    The path info to match
     * @param  string|null $method The request method to match, maybe GET, POST, etc
     * @return false|array
     */
    public function match($pathInfo, $method = null, $name = null)
    {
        if ($name && isset($this->routes[$name])) {
            return $this->_match($pathInfo, $method, $name);
        } else {
            foreach ($this->routes as $name => $route) {
                if (false !== ($params = $this->_match($pathInfo, $method, $name))) {
                    return $params;
                }
            }
        }

        return false;
    }

    /**
     * Check if the route matches the uri and method,
     * and return the parameters, or return false when not matched
     *
     * @param  string      $uri    the uri to match
     * @param  string      $method the request method to match
     * @param  string      $name   the name of the route
     * @return false|array
     */
    protected function _match($pathInfo, $method, $name)
    {
        $route = $this->_compile($this->routes[$name]);
 
        // When $route['method'] is not provided, accepts all request methods
        if ($method && $route['method'] && !preg_match('#' . $route['method'] . '#i', $method)) {
            return false;
        }

        // Check if the route matches the uri
        if (!preg_match($route['regex'], $pathInfo, $matches)) {
            return false;
        }
        
        // get params in the uri
        $parameters = array();
        foreach ($matches as $key => $parameter) {
            if (is_int($key)) {
                continue;
            }
            $parameters[$key] = $parameter;
        }
        
        preg_match_all('#<([a-zA-Z0-9_]++)>#', $route['uri'], $matches);
        foreach ($matches[1] as $key) {
            if (!array_key_exists($key, $parameters)) {
                $parameters[$key] = null;
            }
        }

        // uri params > defaults params
        return array('_route' => $name) + $parameters + $route['defaults'];
    }

    /**
     * Build params to query string
     *
     * @param array $params the array params to combine
     */
    protected function _buildQuery($params)
    {
        if (!$params) {
            return '';
        }
        return '?' . http_build_query($params);
    }

    /**
     * @param array $params
     * @param string $name
     */
    protected function _uri($params, $name)
    {
        $route = $this->_compile($this->routes[$name]);

        $uri = $route['uri'];

        // static route
        if (false === strpos($uri, '<') && false === strpos($uri, '(')) {
            // check if $params contains all of the route default params
            $intersect = array_intersect_assoc($params, $route['defaults']);
            if ($route['defaults'] == $intersect) {
                return $uri . $this->_buildQuery(array_diff_assoc($params, $route['defaults']));
            }

            return false;
        } else {
            $isMatched = false;

            // search the minimal optional parts
            while (preg_match('#\([^()]++\)#', $uri, $match)) {
                // search for the matched value
                $search = $match[0];

                // remove the parenthesis from the match as the replace
                $replace = substr($match[0], 1, -1);

                // search the required parts in the optional parts
                while (preg_match('#<([a-zA-Z0-9_]++)>#', $replace, $match)) {
                    list($key, $param) = $match;

                    if (isset($params[$param])) {
                        if (isset($route['rules'][$param])) {
                            // the parameter not matched the rules
                            if (!preg_match('#' . $route['rules'][$param] . '#', $params[$param])) {
                                return false;
                            }
                        }

                        // replace the key with the parameter value
                        $replace = str_replace($key, urlencode($params[$param]), $replace);

                        $isMatched = true;

                        unset($params[$param]);
                    } else {
                        // this group has missing parameters
                        $replace = '';
                        break;
                    }
                }

                // replace the group in the uri
                $uri = str_replace($search, $replace, $uri);
            }

            if ('' === $uri) {
                return false;
            }

            // search the required parts NOT in the optional parts
            while (preg_match('#<([a-zA-Z0-9_]++)>#', $uri, $match)) {
                list($key, $param) = $match;

                // required route parameter not passed
                if (!isset($params[$param])) {
                    return false;
                }

                // check if parameter value matches the regex rule
                if (isset($route['rules'][$param])) {
                    if (!preg_match('#' . $route['rules'][$param] . '#', $params[$param])) {
                        return false;
                    }
                }

                $uri = str_replace($key, urlencode($params[$param]), $uri);

                $isMatched = true;

                unset($params[$param]);
            }

            // if nothing matched
            if (!$isMatched || '' === $uri) {
                return false;
            }

            return $uri . $this->_buildQuery(array_diff_assoc($params, $route['defaults']));
        }
    }

    /**
     * Generate uri from the $params array
     *
     * @param  array       $params
     * @param  string|null $name
     * @return string
     */
    public function uri(array $params = array(), $name = null)
    {
        if ($name && isset($this->routes[$name])) {
            return $this->_uri($params, $name);
        } else {
            foreach ($this->routes as $name => $route) {
                if (false !== ($uri = $this->_uri($params, $name))) {
                    return $uri;
                }
            }
        }

        return $this->_buildQuery($params);
    }
}
