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
     * The instanced controller objects
     *
     * @var array
     */
    protected $controllerInstances = array();

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
        $options && $this->setOption($options);

        // Step1 Parse the path info to parameter set
        $request = $this->request;
        $paramSet = $this->router->matchParamSet($request->getPathInfo(), $request->getMethod());

        // Step2 根据多组参数,找出对应的控制器和操作,并执行
        return $this->dispatchParamSet($paramSet);
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

        try {
            foreach ($paramSet as $params) {
                $result = $this->dispatch($params['controller'], $params['action'], $params);
                if (is_array($result)) {
                    $notFound = array_merge_recursive($notFound, $result);
                } else {
                    return $this;
                }
            }
        } catch (\RuntimeException $e) {
            if ($e->getCode() === self::FORWARD) {
                return $this;
            } else {
                throw $e;
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

    /**
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return array|Response
     */
    public function dispatch($controller, $action = 'index', array $params = array())
    {
        $notFound = array();
        $classes = $this->getControllerClasses($controller);

        foreach ($classes as $class) {
            if (class_exists($class)) {
                if ($this->isActionAvailable($class, $action)) {

                    // Find existing controller and action
                    $this->setController($controller);
                    $this->setAction($action);
                    $this->request->set($params);

                    $instance = $this->getControllerInstance($class, $controller, $action);

                    $that = $this;
                    $middleware = method_exists($instance, 'getMiddleware') ? $instance->getMiddleware() : array();
                    $response = $this->callMiddleware($middleware, function () use ($instance, $action, $that) {
                        return $instance->$action($that->request, $that->response);
                    });

                    return $this->handleResponse($response);

                } else {
                    $notFound['actions'][$class] = $action;
                }
            } else {
                $notFound['classes'] = $class;
            }
        }
        return $notFound;
    }

    /**
     * @param array $middleware
     * @param callable $callback
     * @return Response
     */
    protected function callMiddleware(array $middleware, $callback)
    {
        $next = function () use (&$middleware, $callback, &$next) {
            $config = array_splice($middleware, 0, 1);
            if ($config) {
                $class = key($config);
                $service = new $class($config[$class]);
                // 如果是response,直接返回?
                $service($next);
            } else {
                return $callback();
            }
        };
        return $next();
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
            case is_scalar($response) || is_null($response) :
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
     * @param string $class The class name of controller
     * @param string $controller The name of controller
     * @param string $action The name of action
     * @return false|object
     */
    public function getControllerInstance($class, $controller, $action)
    {
        if (!isset($this->controllerInstances[$class])) {
            $this->controllerInstances[$class] = new $class(array(
                'wei' => $this->wei,
                'app' => $this,
                'controller' => $controller, /* deprecated */
                'action' => $action, /* deprecated */
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
     * @param string $controller    The name of controller
     * @param string $action        The name of action
     */
    public function forward($controller, $action = 'index')
    {
        $this->dispatchParamSet(array(array(
            'controller' => $controller,
            'action' => $action
        )));
        $this->preventPreviousDispatch();
    }
}