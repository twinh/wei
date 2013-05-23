<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use Widget\Response;

/**
 * A widget to build simple mvc application
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @method      Response response(string $content, int $status = 200) Send headers and output content
 * @property    Stdlib\ViewInterface $view The view widget, instance of \Widget\Viewable interface
 * @property    Logger $logger The logger widget
 * @property    Request $request The HTTP request widget
 */
class App extends AbstractWidget
{
    const FORWARD_CODE = 1000;

    /**
     * The available modules
     *
     * @var array
     */
    protected $modules = array(
        'app'
    );

    /**
     * The default values for module, controller and action
     *
     * @var array
     */
    protected $defaults = array(
        'module'        => 'app',
        'controller'    => 'index',
        'action'        => 'index',
    );

    /**
     * The name of module
     *
     * @var string
     */
    protected $module;

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
     * The controller class format
     *
     * @var string
     */
    protected $controllerFormat = '%module%\%controller%';

    /**
     * The controller instances
     *
     * @var array
     */
    protected $controllers = array();

    /**
     * Startup application
     *
     * @param  array     $options
     * @return App|null
     */
    public function __invoke(array $options = array())
    {
        $options && $this->setOption($options);

        $request = $this->request;
        $parameters = (array)$this->router->match($request->getPathInfo(), $request->getMethod());
        $request->set($parameters);

        return $this->dispatch(
            $this->getModule(),
            $this->getController(),
            $this->getAction()
        );
    }

    /**
     * Load and execute the controller action
     *
     * @param  string    $module     The name of module
     * @param  string    $controller The name of controller
     * @param  string    $action     The name of action
     * @return App
     * @throws \RuntimeException When module, controller or action not found
     */
    public function dispatch($module, $controller, $action = 'index')
    {
        // Check if module available
        if ($this->isModuleAvaiable($module)) {

            // Get controller instance by module and controller name
            $object = $this->getControllerInstance($module, $controller);
            if ($object) {

                // Check if action exists
                // TODO Check by controller object, such as $object->hasAction($action)
                $method = $action . 'Action';
                if (method_exists($object, $method)) {

                    try {

                        $response = $object->$method();

                        $this->handleResponse($response);
                    } catch (\RuntimeException $e) {
                        if ($e->getCode() === self::FORWARD_CODE) {
                            $this->logger->debug(sprintf('Caught exception "%s" with message "%s" called in %s on line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
                        } else {
                            throw $e;
                        }
                    }
                    return $this;
                } else {
                    $notFound = 'action';
                }
            } else {
                $notFound = 'controller';
            }
        } else {
            $notFound = 'module';
        }

        // Prepare exception message
        $message = 'The page you requested was not found';
        if ($this->widget->config('debug')) {
            $message .= (' - ');
            switch ($notFound) {
                case 'module':
                    $message .= sprintf('module "%s" is not available', $module);
                    break;

                case 'controller':
                    $message .= sprintf('controller "%s" not found in module "%s"', $controller, $module);
                    break;

                case 'action':
                    $message .= sprintf('action "%s" not found in controller "%s"', $action, get_class($object));
                    break;
            }
        }
        // You can use `$widget->error->notFound(function(){});` to custom the 404 page
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
            case is_string($response) :
            case is_null($response) :
                return $this->response($response);

            // Response if not sent
            case $response instanceof Response :
                return !$response->isSent() && $response->send();

            default :
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array, printable variable or \Widget\Response, "%s" given',
                    is_object($response) ? get_class($response) : gettype($response)
                ));
        }
    }

    /**
     * Get the of name module
     *
     * @return string The name of module
     */
    public function getModule()
    {
        if (!$this->module) {
            $this->module = $this->request->get('module', $this->defaults['module']);
        }

        return $this->module;
    }

    /**
     * Set the name of module
     *
     * @param  string    $module The name of module
     * @return App
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Check if the given module in avaiable modules
     *
     * @param string $module
     * @return bool
     */
    public function isModuleAvaiable($module)
    {
        return in_array($module, $this->modules);
    }

    /**
     * Get the of name controller
     *
     * @return string The name of controller
     */
    public function getController()
    {
        if (!$this->controller) {
            $this->controller = $this->request->get('controller', $this->defaults['controller']);
        }

        return $this->controller;
    }

    /**
     * Set the name of controller
     *
     * @param  string    $controller The name of controller
     * @return App
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get the name of action
     *
     * @return string The name of action
     */
    public function getAction()
    {
        if (!$this->action) {
            $this->action = $this->request->get('action', $this->defaults['action']);
        }

        return $this->action;
    }

    /**
     * Set the name of action
     *
     * @param  string    $action The name of action
     * @return App
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the controller instance, if not found, return false instead
     *
     * @param string $module The name of module
     * @param string $controller The name of controller
     * @return boolean
     */
    public function getControllerInstance($module, $controller)
    {
        if (!preg_match('/^([_a-z0-9]+)$/i', $controller)) {
            return false;
        }

        $class = str_replace(
            array('%module%', '%controller%'),
            array(ucfirst($module), ucfirst($controller)),
            $this->controllerFormat
        );

        if (isset($this->controllers[$class])) {
            return $this->controllers[$class];
        }

        if (!class_exists($class)) {
            return false;
        }

        return $this->controllers[$class] = new $class(array(
            'widget' => $this->widget,
        ));
    }

    /**
     * Throws a DispatchBreakException to prevent the previous dispatch process
     *
     * @throws \RuntimeException
     */
    public function preventPreviousDispatch()
    {
        throw new \RuntimeException(null, self::FORWARD_CODE);
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
     * Forwards to the given module, controller and action
     *
     * @param string $action            The name of action
     * @param string|null $controller   The name of controller
     * @param string|null $module       The name of module
     */
    public function forward($action = 'index', $controller = null , $module = null)
    {
        $this->setAction($action);
        $controller && $this->setController($controller);
        $module && $this->setModule($action);

        $this()->preventPreviousDispatch();
    }
}
