<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to build simple MVC application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A service that handles the HTTP request data
 * @property    Router $router A simple router
 * @property    Response $response A service that handles the HTTP response data
 * @property    View $view A service that use to render PHP template
 * @property    Logger $logger A simple logger service, which is inspired by Monolog
 */
class App extends Base
{
    /**
     * The exception code for forward action
     */
    const FORWARD = 1302;

    /**
     * The root PHP namespace of application
     *
     * @var string
     */
    protected $namespace = 'Controller';

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
     * Startup application
     *
     * @param array $options
     * @throws \RuntimeException
     * @return $this
     */
    public function __invoke(array $options = array())
    {
        try {
            $options && $this->setOption($options);

            $request = $this->request;
            $parameters = (array)$this->router->match($request->getPathInfo(), $request->getMethod());
            $request->set($parameters);

            return $this->dispatch(
                $this->getController(),
                $this->getAction()
            );
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
     * Load and execute the controller action
     *
     * @param  string    $controller The name of controller
     * @param  string    $action     The name of action
     * @return $this
     * @throws \RuntimeException When controller or action not found
     */
    public function dispatch($controller, $action = 'index')
    {
        $object = $this->getControllerInstance($controller, $action);
        if ($object) {
            // Check if action is exists and public
            if (method_exists($object, $action)) {
                $ref = new \ReflectionMethod($object, $action);
                if ($ref->isPublic()) {
                    $response = $object->$action();
                    $this->handleResponse($response);
                    return $this;
                } else {
                    $notFound = 'action';
                }
            } else {
                $notFound = 'action';
            }
        } else {
            $notFound = 'controller';
        }

        // Prepare exception message
        $message = 'The page you requested was not found';
        if ($this->wei->isDebug()) {
            $message .= ' - ';
            switch ($notFound) {
                case 'controller':
                    $message .= sprintf('controller "%s" (class "%s") not found', $controller, $this->getControllerClass($controller));
                    break;

                case 'action':
                    $message .= sprintf('action method "%s" not found in controller "%s" (class "%s")', $action, $controller, get_class($object));
                    break;
            }
        }

        // You can use `$wei->error->notFound(function(){});` to custom the 404 page
        throw new \RuntimeException($message, 404);
    }

    /**
     * Handle the response variable returned by controller action
     *
     * @param  mixed                     $response
     * @return Response|boolean
     * @throws \InvalidArgumentException
     */
    public function handleResponse($response)
    {
        switch (true) {
            // Render default template and using $result as template variables
            case is_array($response) :
                $response = $this->view->render($this->getDefaultTemplate(), $response);
                // No break here, $response is use for next case detect

            // Response directly
            case is_scalar($response) :
            case is_null($response) :
                return $this->response->send($response);

            // Response if not sent
            case $response instanceof Response :
                return !$response->isSent() && $response->send();

            default :
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array, printable variable or \Wei\Response, "%s" given',
                    is_object($response) ? get_class($response) : gettype($response)
                ));
        }
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
     * Return the controller class name (without validate if the class exists)'
     *
     * @param string $controller The name of class
     * @return string
     */
    public function getControllerClass($controller)
    {
        $controller = implode('\\', array_map('ucfirst', explode('/', $controller)));
        return $this->namespace . '\\' . $controller;
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
        return strtolower($this->controller . '/' . $this->action) . $this->view->getExtension();
    }

    /**
     * Forwards to the given controller and action
     *
     * @param string $action            The name of action
     * @param string|null $controller   The name of controller
     */
    public function forward($action = 'index', $controller = null)
    {
        $this->setAction($action);
        $controller && $this->setController($controller);

        $this()->preventPreviousDispatch();
    }
}
