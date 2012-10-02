<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * App
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
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
    
    protected $controllers = array();

    /**
     * Startup application
     *
     * @param array $options options
     * @return \Qwin\App
     */
    public function __invoke(array $options = array())
    {
        // merge options
        $options = $this->option($options);
        
        $controller = $this->getControllerName();
        $action = $this->getActionName();
                
        return $this->dispatch($controller, $action);
    }
    
    public function dispatch($controller, $action = 'index')
    {
        $controllerObject = $this->getController($controller);
        
        if ($controllerObject) {
            $method = $action . 'Action';
            if (method_exists($controllerObject, $method)) {
                
                $this->trigger('before.action');
                
                $result = $controllerObject->$method();
                
                $this->trigger('after.action');
                
                return $this->handleActionResult($result);
            } else {
                $this->log(sprintf('Action "%s" not found in controller "%s".', $action, get_class($controllerObject)));
                throw new Exception('The page you requested was not found.', 404);
            }
        } else {
            $this->log(sprintf('Controller "%s" not found', $controller));
            throw new Exception('The page you requested was not found.', 404);
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
        if (!preg_match('/^([_a-z0-9]+)$/i', $name)) {
            return false;
        }
        
        foreach ($this->options['dirs'] as $namespace => $dir) {
            $file = $dir . '/' . $namespace . '/Controller/' . ucfirst($name) . 'Controller.php';

            if (!is_file($file)) {
                continue;
            }

            require_once $file;
            
            $class = $namespace . '\Controller\\' . ucfirst($name) . 'Controller';

            return new $class(array(
                'widget' => $this->widgetManager
            ));
        }
         
        return false;
    }
    
    public function handleActionResult($result)
    {
        switch (true) {
            case is_null($result):
            case is_string($result):
                return $this->response($result);
                
            case is_array($result):
                return $this->render(null, $result);
                
            case $result instanceof \Qwin\Response:
                // todo
                
            case is_object($result) && method_exists($result, '__toString') :
                
                
            default:
                throw new \UnexpectedValueException();
        }
    }
    
    /**
     * Set application directories
     *
     * @param array $dirs
     * @return \Qwin\App
     */
    public function setDirsOption($dirs)
    {
        if (empty($dirs)) {
            $this->options['dirs'] = array(dirname(dirname(dirname(__FILE__))) . '/apps');
        } else {
            $this->options['dirs'] = (array)$dirs;
        }
        return $this;
    }
}
