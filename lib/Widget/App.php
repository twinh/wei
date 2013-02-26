<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Response;
use Widget\Exception\NotFoundException;
use Widget\Exception\DispatchBreakException;
use Widget\Exception\UnexpectedTypeException;

/**
 * App
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @method \Widget\EventManager trigger(string $eventName) Trigger a event
 * @method \Widget\Log log(string $message) Log a default level message
 * @method mixed config(string $name) Get a config
 * @method \Widget\Response response(string $content) Send headers and output content
 * @method string|array request(string $name, mixed $default = null) Get a request parameter
 * @property callable $404 The 404 event handler
 * @property \Widget\Viewable $view The view widget, instance of \Widget\Viewable interface
 */
class App extends AbstractWidget
{
    /**
     * The available modules
     *
     * @var array
     */
    protected $modules = array(
        'App'
    );

    /**
     * The default values for module, controller and action
     *
     * @var array
     */
    protected $defaults = array(
        'module'        => 'index',
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
     /
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
     * @param  array     $options
     * @return App|null
     */
    public function __invoke(array $options = array())
    {
        $this->option($options);

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
     * @return App|null
     * @throws Widget\Exception\NotFoundException When controller or action not found
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

                        $this->trigger('before.action');

                        $response = $object->$method();

                        $this->trigger('after.action');

                        $this->handleResponse($response);

                        return $this;

                    } catch (DispatchBreakException $e) {
                        $this->log(sprintf('Caught exception "%s" with message "%s" called in %s on line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
                    }

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
                    $message .= sprintf('action "%s" not found in controller "%s".', $action, get_class($object));
                    break;
            }
        }

        if (false !== $this->trigger('404', array($this, $notFound, $message), $this)) {
            throw new NotFoundException($message);
        }
    }

    /**
     * Handle the response variable returned by controller action
     *
     * @param  mixed                     $response
     * @return Response|boolean
     * @throws Widget\Exception\UnexpectedTypeException
     */
    public function handleResponse($response)
    {
        switch (true) {
            // render default template and using $result as template variables
            case is_array($response) :
                $response = $this->view->render($this->getDefaultTemplate(), $response);

            // response directly
            case is_string($response) :
            case is_null($response) :
                return $this->response($response);

            // response
            case $response instanceof Response :
                return !$response->isSent() && $response->send();

            default :
                throw new UnexpectedTypeException($response, 'array, printable variable or \Widget\Response');
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
            $this->module = ucfirst((string)$this->request('module', $this->defaults['module']));
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
            $this->controller = ucfirst((string)$this->request('controller', $this->defaults['controller']));
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
     * @return string The name of controller
     */
    public function getAction()
    {
        if (!$this->action) {
            $this->action = (string)$this->request('action', $this->defaults['action']);
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
     * @param  string                   $name the name of controller
     * @param string $module
     * @param string $controller
     * @return false|object
     * @todo custom module namespace
     */
    public function getControllerInstance($module, $controller)
    {
        if (!preg_match('/^([_a-z0-9]+)$/i', $module . $controller)) {
            return false;
        }

        $class = $module . '\Controller\\' . $controller . 'Controller';

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
     * @throws Widget\Exception\DispatchBreakException
     */
    public function preventPreviousDispatch()
    {
        $traces = debug_backtrace();
        throw new DispatchBreakException('', 0, $traces[0]['file'], $traces[0]['line']);
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
}
