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
 * @property    Logger $logger A logger service, which is inspired by Monolog
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
    protected $controllerFormat = 'Controller\%controller%';

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
     * The controller instances
     *
     * @var array
     */
    protected $controllers = array();

    /**
     * An array contains the pre-defined controller classes
     *
     * @var array
     */
    protected $controllerClasses = array();

    /**
     * Startup an MVC application
     *
     * @param array $options
     * @throws \RuntimeException
     * @return $this
     */
    public function __invoke(array $options = array())
    {
        try {
            $options && $this->setOption($options);

            // Step1 Parse the path info to parameter set
            $request = $this->request;
            $paramSet = $this->router->matchParamSet($request->getPathInfo(), $request->getMethod());

            // Step2 根据多组参数,找出对应的控制器和操作,并执行
            return $this->dispatchParamSet($paramSet);

        } catch (\RuntimeException $e) {
            if ($e->getCode() === self::FORWARD) {
                $this->logger->debug(sprintf('Caught exception "%s" with message "%s" called in %s on line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
                return $this;
            } else {
                throw $e;
            }
        }
    }

    /**
     * Dispatch by specified parameter set
     *
     * @param array $paramSet
     * @return $this
     */
    public function dispatchParamSet(array $paramSet)
    {
        $notFound = array('classes' => array(), 'actions' => array());

        foreach ($paramSet as $params) {
            $result = $this->dispatch($params['controller'], $params['action'], $params);
            if (is_array($result)) {
                $notFound = array_merge_recursive($notFound, $result);
            } else {
                return $this;
            }
        }

        // All controllers and actions were not found, prepare exception message
        $message = 'The page you requested was not found';
        if ($this->wei->isDebug()) {
            $detail = $this->request->get('debug-detail');
            foreach ($notFound['classes'] as $controller) {
                $classes = $this->getControllerClasses($controller);
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
        throw new \RuntimeException($message, 404);
    }

    public function dispatch($controller, $action, array $params = array())
    {
        $notFound = array();
        $classes = $this->getControllerClasses($controller);

        foreach ($classes as $class) {
            if (class_exists($class)) {
                if ($this->isActionAvailable($class, $action)) {
                    // 找到符合的控制器和操作
                    $this->request->set($params);

                    // Instance controller
                    $this->controllers[$class] = $object = new $class(array(
                        'wei' => $this->wei,
                        'app' => $this,
                        'controller' => $controller,
                        'action' => $action,
                    ));

                    $middleware = method_exists($object, 'getMiddleware') ? $object->getMiddleware() : array();
                    $that = $this;
                    $this->callMiddleware($middleware, function () use ($object, $action, $that) {
                        $response = $object->$action($that->request, $that->response);
                        $that->handleResponse($response);
                    });
                    return $this;

                } else {
                    $notFound['actions'][$class] = $action;
                }
            } else {
                $notFound['classes'] = $class;
            }
        }
        return $notFound;
    }

    protected function callMiddleware(array $middleware, $callback)
    {
        $next = function () use (&$middleware, $callback, &$next) {
            $config = array_splice($middleware, 0, 1);
            if ($config) {
                $class = key($config);
                $service = new $class($config[$class]);
                $service($next);
            } else {
                $callback();
            }
        };
        $next();
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
                $response = $this->view->render($this->getDefaultTemplate(), $response);
                return $this->response->send($response);

            // Response directly
            case is_scalar($response) :
            case is_null($response) :
                return $this->response->send($response);

            // Response if not sent
            case $response instanceof Response :
                !$response->isSent() && $response->send();
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
        if (!$this->controller) {
            $this->controller = $this->request->get('controller', $this->defaultController);
        }
        return $this->controller;
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
        if (!$this->action) {
            $this->action = $this->request->get('action', $this->defaultAction);
        }
        return $this->action;
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
        $upperLetter = strrpos($class, '\\') + 1;
        $class[$upperLetter] = strtoupper($class[$upperLetter]);
        $classes[] = $class;

        // Add class from pre-defined classes
        if (isset($this->controllerClasses[$controller])) {
            $classes[] = $this->controllerClasses[$controller];
        }

        return $classes;
    }

    /**
     * Get the controller instance, if not found, return false instead
     *
     * @param string $controller The name of controller
     * @param string $action The name of action
     * @return object|false
     */
    public function getControllerInstance($controller, $action)
    {
        $class = $this->getControllerClass($controller);

        if (isset($this->controllers[$class])) {
            return $this->controllers[$class];
        }

        if (!class_exists($class)) {
            return false;
        }

        return $this->controllers[$class] = new $class(array(
            'wei' => $this->wei,
            'app' => $this,
            'controller' => $controller,
            'action' => $action,
        ));
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
        throw new \RuntimeException('', self::FORWARD);
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
     * @param string $controller    The name of controller
     * @param string $action        The name of action
     */
    public function forward($controller, $action = 'index')
    {
        $this->setController($controller);
        $this->setAction($action);
        $this()->preventPreviousDispatch();
    }
}