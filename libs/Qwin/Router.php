<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Router

 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-01-24 01:05:28
 * @link        The code is base on the awesome framework - Kohana
 *              http://kohanaframework.org/3.0/guide/api/Kohana_Route
 *
 */
class Qwin_Router extends Qwin_Widget
{
    protected $_routeOptions = array(
        'name'              => null,
        'uri'               => null,
        'rules'             => array(),
        'defaults'          => array(),
        'method'            => null,
        'slashSeparator'    => false,
        'regex'             => null,
    );

    public $options = array(
        'routes' => array(),
    );

    /**
     * 储存路由的数组
     *
     * @var array
     */
    protected $_data = array(
        'default' => array(
            'name' => 'default',
            'uri' => '(<module>(/<action>(/<id>)))',
            'rules' => array(),
            'method' => null,
            'regex' => null,
            'slashSeparator' => false,
            'defaults' => array(
                'module' => 'index',
                'action' => 'index',
            ),
        ),
    );

    public function __invoke()
    {
        return $this;
    }

    /**
     * Set route
     *
     * @param array $route the options of the route
     * @return Qwin_Router
     */
    public function set(array $route)
    {
        $route =  $route + $this->_routeOptions;

        if (!$route['name']) {
            array_unshift($this->_data, $route);
        } else {
            $this->_data = array($route['name'] => $route) + $this->_data;
        }
        return $this;
    }

    /**
     * Get the route by name
     *
     * @param string $name the name of the route
     * @return array
     */
    public function get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * Remove the route
     *
     * @param string $name the name of the route
     * @return Qwin_Router
     */
    public function remove($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     * Prepare the route uri to regex
     *
     * @param array $route the route array
     * @return string
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

        $route['regex'] = '#^' . $regex . '#uD';

        return $route;
    }

    /**
     * Check if there is a route matches the uri and method,
     * and return the parameters, or return false when not matched
     *
     * @param string $uri uri to match
     * @param string $method the request method to match, maybe GET, POST, etc
     * @return array
     */
    public function match($uri, $method = null)
    {
        foreach ($this->_data as $name => &$route) {
            if (false !== ($params = $this->matchOne($name, $uri, $method))) {
                return $params;
            }
        }
        return false;
    }

    /**
     * Check if the route matches the uri and method,
     * and return the parameters, or return false when not matched
     *
     * @param string $name the name of the route
     * @param string $uri the uri to match
     * @param string $method the request method to match
     * @return array|false
     */
    public function matchOne($name, $uri, $method = null)
    {
        if (!isset($this->_data[$name])) {
            return false;
        }

        $route = $this->_compile($this->_data[$name]);

        // when $route['method'] is not provided, accepts all request methods
        if ($method && $route['method'] && !preg_match('#' . $route['method'] . '#i', $method)) {
            return false;
        }

        // check if the route matches the uri
        if (!preg_match($route['regex'], $uri, $matches)) {
            return false;
        }

        // get the query string and parse it to array
        $query = substr($uri, strlen($matches[0]));
        $query = $query ? $this->_parseQuery($query, $route['slashSeparator']) : array();

        // get params in the uri
        $params = array();
        foreach ($matches as $name => $param) {
            if (is_int($name)) {
                continue;
            }
            $params[$name] = $param;
        }

        // query params > uri params > defaults params
        return $query + $params + $route['defaults'];
    }

    /**
     * Parse the query string to array
     *
     * @param string $query the query string to parse
     * @param bool $slash whether combined by slash or normal(&,=) separators
     * @return array
     */
    protected function _parseQuery($query, $slash = false)
    {
        if (!$slash) {
            parse_str(ltrim($query, '?/'), $params);
        } else {
            $query = explode('/', ltrim($query, '/'));
            foreach ($query as $num => $param) {
                if ($num % 2) {
                    $params[$query[$num - 1]] = $param;
                } else {
                    $params[$param] = null;
                }
            }
        }
        return $params;
    }

    /**
     * Build params to query string
     *
     * @param array $params the array params to combine
     * @param bool $slash whether combined by slash or normal(&,=) separators
     */
    protected function _buildQuery($params, $slash = false)
    {
        if (!$params) {
            return '';
        }

        if (!$slash) {
            return '?' . http_build_query($params);
        } else {
            $query = '';
            foreach ($params as $key => $value) {
                $query .= '/' . $key . '/' . $value;
            }
            return $query;
        }
    }

    protected function _uri($params, $name)
    {
        $route = $this->_compile($this->_data[$name]);

        $uri = $route['uri'];

        // static route
        if (false === strpos($uri, '<') && false === strpos($uri, '(')) {
            // check if $params contains all of the route default params
            $intersect = array_intersect_assoc($params, $route['defaults']);
            if ($route['defaults'] == $intersect) {
                return $uri . $this->_buildQuery(array_diff_assoc($params, $route['defaults']), $route['slashSeparator']);
            }
            return false;
        } else {
            $regex = $route['regex'];

            $opts = array();

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

                    //
                    $opts[] = $param;

                    if (isset($params[$param])) {
                        $isMatched = true;
                        if (isset($route['rules'][$param])) {
                            // the parameter not matched the rules
                            if (!preg_match('#' . $route['rules'][$param] . '#', $params[$param])) {
                                return false;
                            }
                        }

                        // replace the key with the parameter value
                        $replace = str_replace($key, $params[$param], $replace);

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

                $isMatched = true;
                $uri = str_replace($key, $params[$param], $uri);
                unset($params[$param]);
            }

            /*$defaults = $route['defaults'];
            foreach ($opts as $key) {
                if (isset($defaults[$key])) {
                    unset($defaults[$key]);
                }
            }
            foreach ($defaults as $key => $value) {
                if (isset($params[$key]) && $params[$key] != $value) {
                    return false;
                }
            }*/

            // if nothing matched
            if (!$isMatched) {
                return false;
            }

            return $uri . $this->_buildQuery(array_diff_assoc($params, $route['defaults']), $route['slashSeparator']);
        }
    }

    public function uri(array $params = array(), $name = null)
    {
        if ($name && isset($this->_data[$name])) {
            return $this->_uri($params, $name);
        } else {
            foreach ($this->_data as $name => $route) {
                if (false !== ($uri = $this->_uri($params, $name))) {
                    return $uri;
                }
            }
        }
        return $this->_buildQuery($params);
    }
}