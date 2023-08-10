<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to build an MVC application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Router $router A service that parse the URL to request data
 * @property    Req $req A service that handles the HTTP request data
 * @property    Res $res A service that handles the HTTP response data
 * @property    View $view A service that use to render PHP template
 */
class App extends Base
{
    /**
     * The exception code for forward action
     */
    public const FORWARD = 1302;

    /**
     * The format of controller class
     *
     * @var string
     */
    protected $controllerFormat = 'controllers\%controller%';

    /**
     * The format of action method
     *
     * @var string
     * @option
     */
    protected $actionMethodFormat = '%action%Action';

    /**
     * The default controller name
     *
     * @var string
     */
    protected $defaultController = 'index';

    /**
     * The default action name
     *
     * @var string
     */
    protected $defaultAction = 'index';

    /**
     * The name of current application
     *
     * @var string
     */
    protected $namespace;

    /**
     * The name of controller
     *
     * @var string
     */
    protected $controller;

    /**
     * The name of action
     *
     * @var string
     */
    protected $action;

    /**
     * The instanced controller objects
     *
     * @var array
     */
    protected $controllerInstances = [];

    /**
     * An array that stores predefined controller class,
     * the key is controller name and value is controller class name
     *
     * @var array
     */
    protected $controllerMap = [];

    /**
     * Startup an MVC application
     *
     * @param array $options
     * @return Res
     * @throws \RuntimeException
     */
    public function __invoke(array $options = [])
    {
        $options && $this->setOption($options);

        // Parse the path info to parameter set
        $request = $this->req;
        $paramSet = $this->router->matchParamSet($request->getPathInfo(), $request->getMethod());

        // Find out exiting controller action and execute
        $notFound = [];
        foreach ($paramSet as $params) {
            $response = $this->dispatch($params['controller'], $params['action'], $params, false);
            if (is_array($response)) {
                $notFound = array_merge($notFound, $response);
            } else {
                return $response;
            }
        }
        throw $this->buildException($notFound);
    }

    /**
     * Dispatch by specified controller and action
     *
     * @param string $controller The name of controller
     * @param string|null $action The name of action
     * @param array $params The request parameters
     * @param bool $throwException Whether throw exception when application not found
     * @return array|Res
     */
    public function dispatch($controller, $action = null, array $params = [], $throwException = true)
    {
        $notFound = [];
        $action || $action = $this->defaultAction;
        $classes = $this->getControllerClasses($controller);

        foreach ($classes as $class) {
            if (!class_exists($class)) {
                $notFound['controllers'][$controller][] = $class;
                continue;
            }
            if (!$this->isActionAvailable($class, $action)) {
                $notFound['actions'][$action][$controller][] = $class;
                continue;
            }

            // Find out existing controller and action
            $this->setController($controller);
            $this->setAction($action);
            $this->req->set($params);
            try {
                $instance = $this->getControllerInstance($class);

                return $this->execute($instance, $action);
            } catch (\RuntimeException $e) {
                if (self::FORWARD === $e->getCode()) {
                    return $this->res;
                } else {
                    throw $e;
                }
            }
        }

        if ($throwException) {
            throw $this->buildException($notFound);
        } else {
            return $notFound;
        }
    }

    /**
     * Handle the response variable returned by controller action
     *
     * @param mixed $response
     * @return Res
     * @throws \InvalidArgumentException
     */
    public function handleResponse($response)
    {
        switch (true) {
            // Render default template and use $response as template variables
            case is_array($response):
                $content = $this->view->render($this->getDefaultTemplate(), $response);

                return $this->res->setContent($content);

            // Response directly
            case is_scalar($response) || null === $response:
                return $this->res->setContent($response);

            // Response if not sent
            case $response instanceof Res:
                return $response;

            default:
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array, printable variable or \Wei\Response, "%s" given',
                    is_object($response) ? get_class($response) : gettype($response)
                ));
        }
    }

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getNamespace()
    {
        if (!$this->namespace) {
            $this->namespace = $this->req->get('namespace');
        }

        return $this->namespace;
    }

    /**
     * Set namespace
     *
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get the of name controller
     *
     * @return string The name of controller
     */
    public function getController()
    {
        return $this->controller ?: $this->defaultController;
    }

    /**
     * Set the name of controller
     *
     * @param string $controller The name of controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get the name of action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action ?: $this->defaultAction;
    }

    /**
     * Set the name of action
     *
     * @param string $action The name of action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Returns the method name of specified acion
     *
     * @param string $action
     * @return string
     */
    public function getActionMethod($action)
    {
        return str_replace('%action%', $action, $this->actionMethodFormat);
    }

    /**
     * Returns the URI that contains controller and action
     *
     * @return string
     */
    public function getControllerAction()
    {
        return $this->getController() . '/' . $this->getAction();
    }

    /**
     * Return the controller class names by controllers (without validate if the class exists)
     *
     * @param string $controller The name of controller
     * @return array
     */
    public function getControllerClasses($controller)
    {
        $classes = [];

        // Prepare parameters for replacing
        $controller = strtr($controller, ['/' => '\\']);

        if ($this->controllerFormat) {
            // Generate class from format
            $namespace = $this->getNamespace();
            $class = str_replace(
                ['%namespace%', '%controller%'],
                [ucfirst($namespace), ucfirst($controller)],
                $this->controllerFormat
            );

            // Make the class name's first letter uppercase
            $upperLetter = strrpos($class, '\\') + 1;
            $class[$upperLetter] = strtoupper($class[$upperLetter]);
            $classes[] = $class;
        }

        // Add class from predefined classes
        if (isset($this->controllerMap[$controller])) {
            $classes[] = $this->controllerMap[$controller];
        }

        return $classes;
    }

    /**
     * Set the format of controller class
     *
     * @param string $controllerFormat
     * @return $this
     */
    public function setControllerFormat($controllerFormat)
    {
        $this->controllerFormat = $controllerFormat;

        return $this;
    }

    /**
     * Set the map of controller classes
     *
     * @param array $controllerMap
     * @return $this
     */
    public function setControllerMap(array $controllerMap)
    {
        $this->controllerMap = $controllerMap + $this->controllerMap;

        return $this;
    }

    /**
     * Return the action method format
     *
     * @return string
     */
    public function getActionMethodFormat(): string
    {
        return $this->actionMethodFormat;
    }

    /**
     * Set the action method format
     *
     * @param string $actionMethodFormat
     * @return $this
     */
    public function setActionMethodFormat(string $actionMethodFormat): self
    {
        $this->actionMethodFormat = $actionMethodFormat;
        return $this;
    }

    /**
     * Check if action name is available
     *
     * Returns false when
     * 1. method is not found
     * 2. method is not public
     * 3. method letters case error
     *
     * @param object $object The object of controller
     * @param string $action The name of action
     * @return bool
     */
    public function isActionAvailable($object, $action)
    {
        $method = $this->getActionMethod($action);
        try {
            $ref = new \ReflectionMethod($object, $method);
            if ($ref->isPublic() && $method === $ref->name) {
                return true;
            } else {
                return false;
            }
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * Throws a exception to prevent the previous dispatch process
     *
     * @throws \RuntimeException
     */
    public function preventPreviousDispatch()
    {
        throw new \RuntimeException('Forwarding, please ignore me', self::FORWARD);
    }

    /**
     * Get default template file according to the controller, action and file
     * extension provided by the view engine
     *
     * @return string
     */
    public function getDefaultTemplate()
    {
        return lcfirst($this->controller) . '/' . $this->action . $this->view->getExtension();
    }

    /**
     * Forwards to the given controller and action
     *
     * @param string $controller The name of controller
     * @param string|null $action The name of action
     * @param array $params The request parameters
     */
    public function forward($controller, $action = null, array $params = [])
    {
        $this->res = $this->dispatch($controller, $action, $params);
        $this->preventPreviousDispatch();
    }

    /**
     * Build 404 exception from notFound array
     *
     * @param array $notFound
     * @return \RuntimeException
     */
    protected function buildException(array $notFound)
    {
        $notFound += ['controllers' => [], 'actions' => []];

        // All controllers and actions were not found, prepare exception message
        $message = 'The page you requested was not found';
        if ($this->wei->isDebug()) {
            $detail = $this->req->get('debug-detail');
            foreach ($notFound['controllers'] as $controller => $classes) {
                $message .= sprintf('%s - controller "%s" not found', "\n", $controller);
                $detail && $message .= sprintf(' (class "%s")', implode('", "', $classes));
            }
            foreach ($notFound['actions'] as $action => $controllers) {
                $method = $this->getActionMethod($action);
                foreach ($controllers as $controller => $classes) {
                    $message .= sprintf('%s - method "%s" not found in controller "%s"', "\n", $method, $controller);
                    $detail && $message .= sprintf(' (class "%s")', implode('", "', $classes));
                }
            }
        }

        // You can use `$wei->error->notFound(function(){});` to custom the 404 page
        return new \RuntimeException($message, 404);
    }

    /**
     * Execute action with middleware
     *
     * @param \Wei\BaseController $instance
     * @param string $action
     * @return Res
     */
    protected function execute($instance, $action)
    {
        $wei = $this->wei;
        $middleware = $this->getMiddleware($instance, $action);

        $callback = function () use ($instance, $action) {
            $instance->before($this->req, $this->res);

            $method = $this->getActionMethod($action);
            $response = $instance->{$method}($this->req, $this->res);

            $instance->after($this->req, $response);

            return $response;
        };

        $next = function () use (&$middleware, &$next, $callback, $wei) {
            $config = array_splice($middleware, 0, 1);
            if ($config) {
                $class = key($config);
                $service = new $class(['wei' => $wei] + $config[$class]);
                $result = $service($next);
            } else {
                $result = $callback();
            }

            return $result;
        };

        return $this->handleResponse($next())->send();
    }

    /**
     * Returns middleware for specified action
     *
     * @param \Wei\BaseController $instance
     * @param string $action
     * @return array
     */
    protected function getMiddleware($instance, $action)
    {
        $results = [];
        $middleware = $instance->getMiddleware();
        foreach ($middleware as $class => $options) {
            if (
                (!isset($options['only']) || in_array($action, (array) $options['only'], true))
                && (!isset($options['except']) || !in_array($action, (array) $options['except'], true))
            ) {
                $results[$class] = $options;
            }
        }

        return $results;
    }

    /**
     * Get the controller instance, if not found, return false instead
     *
     * @param string $class The class name of controller
     * @return \Wei\BaseController
     */
    protected function getControllerInstance($class)
    {
        if (!isset($this->controllerInstances[$class])) {
            $this->controllerInstances[$class] = new $class([
                'wei' => $this->wei,
                'app' => $this,
            ]);
        }

        return $this->controllerInstances[$class];
    }
}
