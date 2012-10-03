<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

use Qwin\Response,
    Qwin\App\WorkFlowBreakNotifyException;

/**
 * App
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        output anywhere support?
 */
class App extends Widget
{
    /**
     * @var array           options
     *
     *       dirs           the root directories of the applications
     *
     *       controller     the default controller name
     *
     *       action         the default action name
     */
    public $options = array(
        'dirs'          => array(),
        'controller'    => null,
        'action'        => null,
    );
    
    /**
     * The controller name
     * 
     * @var string
     */
    protected $controller;
    
    /**
     * The action name
     * 
     * @var string
     */
    protected $action;
    
    /**
     * Controller instances
     * 
     * @var array
     */
    protected $controllers = array();

    /**
     * Startup application
     *
     * @param array $options options
     * @return \Qwin\App
     */
    public function __invoke(array $options = array())
    {
        $options = $this->option($options);
        
        $controller = $this->getControllerName();
        $action = $this->getActionName();

        return $this->dispatch($controller, $action);
    }
    
    /**
     * Load and execute the controller action
     * 
     * @param string $controller The name of controller
     * @param string $action The name of action
     * @return mixed
     * @throws Exception When controller or action not found
     */
    public function dispatch($controller, $action = 'index')
    {
        try {
            $controllerObject = $this->getController($controller);

            if ($controllerObject) {
                $method = $action . 'Action';
                if (method_exists($controllerObject, $method)) {

                    $this->trigger('before.action');

                    $response = $controllerObject->$method();

                    $this->trigger('after.action');

                    $this->handleResponse($response);
                    
                    return $this;
                } else {
                    $this->log(sprintf('Action "%s" not found in controller "%s".', $action, get_class($controllerObject)));
                    throw new Exception('The page you requested was not found.', 404);
                }
            } else {
                $this->log(sprintf('Controller "%s" not found', $controller));
                throw new Exception('The page you requested was not found.', 404);
            }
        // TODO has better name ?
        } catch (WorkFlowBreakNotifyException $e) {
            $this->log(sprintf('Caught exception "%s" with message "%s" called in %s on line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
        }
    }
    
    /**
     * Get the of name controller
     * 
     * @return string the name of controller
     */
    public function getControllerName()
    {
        if (!$this->controller) {
            $this->controller = $this->options['controller'] ?: $this->request('controller');
        }
        return $this->controller;
    }
    
    /**
     * Set the name of controller
     * 
     * @param string $controller the name of controller
     * @return \Qwin\App
     */
    public function setControllerName($controller)
    {
        $this->controller = $controller;
        return $this;
    }
    
    /**
     * Get the name of action
     * 
     * @return string the name of controller
     */
    public function getActionName()
    {
        if (!$this->action) {
            $this->action = $this->options['action'] ?: $this->request('action');
        }
        return $this->action;
    }
    
    /**
     * Set the name of action 
     * 
     * @param string $action the name of action
     * @return \Qwin\App
     */
    public function setActionName($action)
    {
        $this->action = $action;
        return $this;
    }
    
    /**
     * Get the controller instance, if not found, return false instead
     * 
     * @param string $name the name of controller
     * @return boolean|\Qwin\Controller
     */
    public function getController($name)
    {
        if (isset($this->controllers[$name])) {
            return $this->controllers[$name];
        }
        
        if (!preg_match('/^([_a-z0-9]+)$/i', $name)) {
            return false;
        }
        
        foreach ((array)$this->options['dirs'] as $namespace => $dir) {
            $file = $dir . '/' . $namespace . '/Controller/' . ucfirst($name) . 'Controller.php';

            if (!is_file($file)) {
                continue;
            }

            require_once $file;
            
            $class = $namespace . '\Controller\\' . ucfirst($name) . 'Controller';

            return $this->controllers[$name] = new $class(array(
                'widget' => $this->widgetManager
            ));
        }
         
        return false;
    }
    
    /**
     * Handle the response variable by controller action
     * 
     * @param mixed $response
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function handleResponse($response)
    {
        switch (true) {
            // render default template and using $result as template variables 
            case is_array($response) :
                $response = $this->getController($this->controller)->render(null, $response);
                
            // response directly
            case is_string($response) :
            case is_null($response) :
                return $this->response($response);

            // response
            case $response instanceof Response :
                return !$response->isSent() && $response->send();
 
            default :
                try {
                    $response = strval($response);
                    return $this->response($response);
                } catch (\Exception $e) {
                    throw new \UnexpectedValueException(
                        sprintf('Expected array, printable variable or \Qwin\Response, "%s" given', 
                        (is_object($response) ? get_class($response) : gettype($response))), 500);
                }
        }
    }
    
    /**
     * Throwa a WorkFlowBreakNotifyException to prevent the previous dispatch process
     * 
     * @throws WorkFlowBreakNotifyException
     */
    public function preventPreviousDispatch()
    {
        $traces = debug_backtrace();
        throw new WorkFlowBreakNotifyException('', 0, $traces[0]['file'], $traces[0]['line']);
    }
}