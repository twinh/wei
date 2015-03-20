<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that parse the URL to request data
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        The code is inspired by the awesome framework - Kohana
 *               http://kohanaframework.org/3.0/guide/api/Kohana_Route
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
     * The default route options
     *
     * @var array
     *
     * Name     | Type     | Description
     * ---------|----------|-------------
     * pattern  | string   | The string to be complied to regex
     * rules    | array    | The regex rules
     * defaults | array    | The defaults params of the route
     * method   | string   | The required request method of the route
     * regex    | string   | The regex complied from the pattern, just leave it blank when set a new route
     */
    protected $routeOptions = array(
        'pattern'   => null,
        'rules'     => array(),
        'defaults'  => array(),
        'method'    => null,
        'regex'     => null,
    );

    /**
     * The string to split out at the beginning of path
     *
     * @var array
     */
    protected $namespaces = array('admin', 'api');

    /**
     * The string to split out after namespace string
     *
     * @var array
     */
    protected $scopes = array('user');

    /**
     * The resources that contains "/", eg articles/categories, products/categories, issues/comments.
     *
     * @var array
     */
    protected $combinedResources = array();

    /**
     * @var string
     */
    protected $defaultController = 'index';

    /**
     * @var string
     */
    protected $defaultAction = 'index';

    /**
     * An array contains the HTTP method and action name
     *
     * @var array
     */
    protected $methodToAction = array(
        'GET-collection' => 'index',
        'GET' => 'show',
        'POST' => 'create',
        'PATCH' => 'update',
        'PUT' => 'update',
        'DELETE' => 'destroy'
    );

    /**
     * Singular inflector rules
     *
     * @var array
     */
    protected $singularRules = array(
        '/(o|x|ch|ss|sh)es$/i' => '\1', // heroes, potatoes, tomatoes
        '/([^aeiouy]|qu)ies$/i' => '\1y', // histories
        '/s$/i' => '',
    );

    /**
     * The plural to singular array
     *
     * @var array
     */
    protected $singulars = array(
        'aliases' => 'alias',
        'analyses' => 'analysis',
        'buses' => 'bus',
        'children' => 'child',
        'cookies' => 'cookie',
        'criteria' => 'criterion',
        'data' => 'datum',
        'lives' => 'life',
        'matrices' => 'matrix',
        'men' => 'man',
        'menus' => 'menu',
        'monies' => 'money',
        'news' => 'news',
        'people' => 'person',
        'quizzes' => 'quiz',
    );

    /**
     * Set routes
     *
     * @param array $routes
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function remove($id)
    {
        unset($this->routes[$id]);
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
        $pathInfo = rtrim($pathInfo, '/');
        !$pathInfo && $pathInfo = '/';
        foreach ($this->routes as $id => $route) {
            if (false !== ($parameters = $this->matchRoute($pathInfo, $method, $id))) {
                return $parameters;
            }
        }
        return false;
    }

    /**
     * Parse the path info to parameter set
     *
     * @param  string $pathInfo    The path info to match
     * @param  string|null $method The request method to match, maybe GET, POST, etc
     * @return array
     */
    public function matchParamSet($pathInfo, $method = 'GET')
    {
        $params = $this->match($pathInfo, $method);
        $restParams = $this->matchRestParamSet($pathInfo, $method);
        return $params ? array_merge(array($params), $restParams) : $restParams;
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
     * Parse the path info to multi REST parameters
     *
     * @param string $path
     * @param string $method
     * @return array
     */
    public function matchRestParamSet($path, $method = 'GET')
    {
        $rootCtrl = '';
        $params = array();
        $routes = array();

        // Split out format
        if (strpos($path, '.') !== false) {
            $params['_format'] = pathinfo($path, PATHINFO_EXTENSION);
            $path = substr($path, 0, - strlen($params['_format']) - 1);
        }

        $parts = $this->parsePath($path);

        // Split out namespace
        if (count($parts) > 1 && in_array($parts[0], $this->namespaces)) {
            $rootCtrl .= array_shift($parts) . '/';
        }

        // Split out scope
        if (count($parts) > 1 && in_array($parts[0], $this->scopes)) {
            $rootCtrl .= array_shift($parts) . '/';
        }

        $baseCtrl = $rootCtrl;

        // The first parameter must be controller name,
        // the second parameter may be action name or id, eg posts/show, posts/1
        // the last parameter may be controller name or action name or null, eg posts/1/comments, posts/1/edit
        $lastParts = array_pad(array_splice($parts, count($parts) % 2 == 0 ? -2 : -3), 3, null);
        list($ctrl, $actOrId, $ctrlOrAct) = $lastParts;

        // Generate part of controller name and query parameters
        $count = count($parts);
        for ($i = 0; $i < $count; $i += 2) {
            $baseCtrl .= $parts[$i] . '/';
            $params[$this->singularize($parts[$i]) . 'Id'] = $parts[$i + 1];
        }

        if (is_null($actOrId)) {
            // GET|POST|... /, GET|POST|... posts
            $routes[] = array(
                'controller' => $baseCtrl . ($ctrl ?: $this->defaultController),
                'action' => $this->methodToAction($method, true),
            );
        } elseif (is_null($ctrlOrAct)) {
            // GET posts/show
            if ($this->isAction($actOrId)) {
                $routes[] = array(
                    'controller' => $baseCtrl . $ctrl,
                    'action' => $actOrId,
                );
            }

            // GET|PUT|... posts/1
            $routes[] = array(
                'controller' => $baseCtrl . $ctrl,
                'action' => $this->methodToAction($method),
                'id' => $actOrId
            );

            // GET posts/1/comment/new => comment::new postId=1
            if ($count >= 2 && $this->isAction($actOrId)) {
                $routes[] = array(
                    'controller' => $rootCtrl . $ctrl,
                    'action' => $actOrId,
                );
            }
        } else {
            // GET posts/1/edit
            $routes[] = array(
                'controller' => $baseCtrl . $ctrl,
                'action' => $ctrlOrAct,
                'id' => $actOrId
            );

            // GET|PUT|... posts/1/comments
            $routes[] = array(
                'controller' => $baseCtrl . $ctrl . '/' . $ctrlOrAct,
                'action' => $this->methodToAction($method, true),
                $this->singularize($ctrl) . 'Id' => $actOrId,
            );

            // GET|PUT|... posts/1/comments, use the last parameter as main controller name
            $routes[] = array('controller' => $ctrlOrAct) + $routes[1];
        }

        foreach ($routes as &$route) {
            $route['controller'] = $this->camelize($route['controller']);
            $route['action'] = $this->camelize($route['action']);
            $route += $params;
        }
        return $routes;
    }

    /**
     * Parse path to resource names, actions and ids
     *
     * @param string $path
     * @return array
     */
    protected function parsePath($path)
    {
        $path = ltrim($path, '/');
        foreach ($this->combinedResources as $resource) {
            if (strpos($path, $resource) !== false) {
                list($part1, $part2) = explode($resource, $path, 2);
                $parts = array_merge(explode('/', $part1), array($resource), explode('/', $part2));
                return array_values(array_filter($parts));
            }
        }
        return explode('/', $path);
    }

    /**
     * Set combined resources
     *
     * @param array $combinedResources
     * @return $this
     */
    public function setCombinedResources(array $combinedResources)
    {
        $this->combinedResources += $combinedResources;
        return $this;
    }

    /**
     * Append words to singulars
     *
     * @param array $singulars
     * @return $this
     */
    public function setSingulars(array $singulars)
    {
        $this->singulars += $this->singulars;
        return $this;
    }

    /**
     * Returns a word in singular form.
     *
     * The implementation is borrowed from Doctrine Inflector
     *
     * @param string $word
     * @return string
     * @link https://github.com/doctrine/inflector
     */
    protected function singularize($word)
    {
        if (isset($this->singulars[$word])) {
            return $this->singulars[$word];
        }

        foreach ($this->singularRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                $this->singulars[$word] = preg_replace($rule, $replacement, $word);
                return $this->singulars[$word];
            }
        }

        $this->singulars[$word] = $word;
        return $word;
    }

    /**
     * Camelizes a word
     *
     * @param string $word The word to camelize.
     *
     * @return string The camelized word.
     */
    protected function camelize($word)
    {
        return lcfirst(str_replace(' ', '', ucwords(strtr($word, '_-', '  '))));
    }

    /**
     * Convert HTTP method to action name
     *
     * @param string $method
     * @param bool $collection
     * @return string
     */
    protected function methodToAction($method, $collection = false)
    {
        if ($method == 'GET' && $collection) {
            $method = $method . '-collection';
        }
        return isset($this->methodToAction[$method]) ? $this->methodToAction[$method] : $this->defaultAction;
    }

    /**
     * @param string $action
     * @return bool
     */
    protected function isAction($action)
    {
        return $action && !is_numeric($action[0]);
    }
}