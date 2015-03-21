<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to build an MVC application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Router $router A service that parse the URL to request data
 * @property    Request $request A service that handles the HTTP request data
 * @property    Response $response A service that handles the HTTP response data
 * @property    View $view A service that use to render PHP template
 */
class App extends Base
{
    /**
     * The exception code for forward action
     */
    const FORWARD = 1302;

    /**
     * The format of controller class
     *
     * @var string
     */
    protected $controllerFormat = 'controllers\%controller%';

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
    protected $controllerInstances = array();

    /**
     * An array that stores predefined controller class,
     * the key is controller name and value is controller class name
     *
     * @var array
     */
    protected $controllerMap = array();

    /**
     * Startup an MVC application
     *
     * @param array $options
     * @throws \RuntimeException
     * @return $this
     */
    public function __invoke(array $options = array())
    {
        $options && $this->setOption($options);

        // Parse the path info to parameter set
        $request = $this->request;
        $paramSet = $this->router->matchParamSet($request->getPathInfo(), $request->getMethod());

        // Find out exiting controller action and execute
        $notFound = array();
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
     * @param null|string $action The name of action
     * @param array $params The request parameters
     * @param bool $throwException Whether throw exception when application not found
     * @return array|Response
     */
    public function dispatch($controller, $action = null, array $params = array(), $throwException = true)
    {
        $notFound = array();
        $action || $action = $this->defaultAction;
        $classes = $this->getControllerClasses($controller);

        foreach ($classes as $class) {
            if (class_exists($class)) {
                if ($this->isActionAvailable($class, $action)) {

                    // Find out existing controller and action
                    $this->setController($controller);
                    $this->setAction($action);
                    $this->request->set($params);

                    try {
                        $instance = $this->getControllerInstance($class);
                        return $this->execute($instance, $action);
                    } catch (\RuntimeException $e) {
                        if ($e->getCode() === self::FORWARD) {
                            return $this;
                        } else {
                            throw $e;
                        }
                    }

                } else {
                    $notFound['actions'][$action][$controller][] = $class;
                }
            } else {
                $notFound['controllers'][$controller][]  = $class;
            }
        }

        if ($throwException) {
            throw $this->buildException($notFound);
        } else {
            return $notFound;
        }
    }

    /**
     * Build 404 exception from notFound array
     *
     * @param array $notFound
     * @return \RuntimeException
     */
    protected function buildException(array $notFound)
    {
        $notFound += array('controllers' => array(), 'actions' => array());

        // All controllers and actions were not found, prepare exception message
        $message = 'The page you requested was not found';
        if ($this->wei->isDebug()) {
            $detail = $this->request->get('debug-detail');
            foreach ($notFound['controllers'] as $controller => $classes) {
                $message .= sprintf('%s - controller "%s" not found', "\n", $controller);
                $detail && $message .= sprintf(' (class "%s")', implode($classes, '", "'));
            }
            foreach ($notFound['actions'] as $action => $controllers) {
                foreach ($controllers as $controller => $classes) {
                    $message .= sprintf('%s - action method "%s" not found in controller "%s"', "\n", $action, $controller);
                    $detail && $message .= sprintf(' (class "%s")', implode($classes, '", "'));
                }
            }
        }

        // You can use `$wei->error->notFound(function(){});` to custom the 404 page
        return new \RuntimeException($message, 404);
    }

    /**
     * Execute action with middleware
     *
     * @param \Wei\Base $instance
     * @param string $action
     * @return Response
     */
    protected function execute($instance, $action)
    {
        $that = $this;
        $response = $this->response;
        $middleware = $this->getMiddleware($instance, $action);

        $callback = function () use ($instance, $action, $that) {
            $response = $instance->$action($that->request, $that->response);
            return $that->handleResponse($response);
        };

        $next = function () use (&$middleware, &$next, $callback, $response) {
            $config = array_splice($middleware, 0, 1);
            if ($config) {
                $class = key($config);
                $service = new $class($config[$class]);
                $result = $service($next);
            } else {
                $result = $callback();
            }
            $result && $response = $result;
            return $response;
        };

        return $next()->send();
    }

    /**
     * Returns middleware for specified action
     *
     * @param \Wei\Base $instance
     * @param string $action
     * @return array
     */
    protected function getMiddleware($instance, $action)
    {
        $results = array();
        $middleware = (array)$instance->getOption('middleware');
        foreach ($middleware as $class => $options) {
            if ((isset($options['only']) && in_array($action, (array)$options['only'])) ||
                (isset($options['except']) && !in_array($action, (array)$options['except']))
            ) {
                $results[$class] = $options;
            }
        }
        return $results;
    }

    /**
     * Handle the response variable returned by controller action
     *
     * @param  mixed $response
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function handleResponse($response)
    {
        switch (true) {
            // Render default template and use $response as template variables
            case is_array($response) :
                $content = $this->view->render($this->getDefaultTemplate(), $response);
                return $this->response->setContent($content);

            // Response directly
            case is_scalar($response) || is_null($response) :
                return $this->response->setContent($response);

            // Response if not sent
            case $response instanceof Response :
                return $response;

            default :
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
            $this->namespace = $this->request->get('namespace');
        }
        return $this->namespace;
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
     * @param  string $controller The name of controller
     * @return string
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
     * @param  string $action The name of action
     * @return string
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
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
        $classes = array();

        // Prepare parameters for replacing
        $namespace = $this->getNamespace();
        $controller = strtr($controller, array('/' => '\\'));

        // Generate class from format
        $class = str_replace(
            array('%namespace%', '%controller%'),
            array($namespace, $controller),
            $this->controllerFormat
        );

        // Make the class name's first letter uppercase
        $upperLetter = strrpos($class, '\\') + 1;
        $class[$upperLetter] = strtoupper($class[$upperLetter]);
        $classes[] = $class;

        // Add class from pre-defined classes
        if (isset($this->controllerMap[$controller])) {
            $classes[] = $this->controllerMap[$controller];
        }

        return $classes;
    }

    /**
     * Get the controller instance, if not found, return false instead
     *
     * @param string $class The class name of controller
     * @return object
     */
    protected function getControllerInstance($class)
    {
        if (!isset($this->controllerInstances[$class])) {
            $this->controllerInstances[$class] = new $class(array(
                'wei' => $this->wei,
                'app' => $this,
            ));
        }
        return $this->controllerInstances[$class];
    }

    /**
     * Check if action name is available
     *
     * Returns false when
     * 1. method is not found
     * 2. method is not public
     * 3. method letters case error
     * 4. method is starts with "_"
     *
     * @param object $object The object of controller
     * @param string $action The name of action
     * @return bool
     */
    public function isActionAvailable($object, $action)
    {
        try {
            $ref = new \ReflectionMethod($object, $action);
            if ($ref->isPublic() && $action === $ref->name && $action[0] !== '_') {
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
     * @param null|string $action The name of action
     */
    public function forward($controller, $action = null)
    {
        $this->dispatch($controller, $action);
        $this->preventPreviousDispatch();
    }
}