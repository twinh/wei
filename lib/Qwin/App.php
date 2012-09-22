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
     * @return Qwin_App
     */
    public function __invoke(array $options = array())
    {
        // merge options
        $this->option($options);
        $options = &$this->options;
        
        $this->controller = $options['controller'] ?: $this->request('controller');
        $this->action = $options['action'] ?: $this->request('action');
        
        return $this->dispatch($this->controller, $this->action);
    }
    
    public function dispatch($controller, $action = 'index')
    {
        $controllerObject = $this->getController($controller);
        
        if ($controllerObject) {
                      
            $result = $controllerObject($action);
            
            if ($result) {
                $this->response($result);
            }

            return $this;
        }
          
        throw new Exception('The page you requested was not found.', 404);
    }
    
    public function getControllerName()
    {
        return $this->controller;
    }
    
    public function getActionName()
    {
        return $this->action;
    }
    
    public function getController($name)
    {
        foreach ($this->options['dirs'] as $namespace => $dir) {
            $file = $dir . '/' . $namespace . '/Controller/' . ucfirst($name) . 'Controller.php';

            if (!is_file($file)) {
                continue;
            }

            require_once $file;
            
            $class = $namespace . '\Controller\\' . ucfirst($name) . 'Controller';

            return new $class(array(
                'widget' => $this->rootWidget
            ));
        }
        
        $this->log(sprintf('Controller "%s" not found', $name));
            
        return false;
    }
    
    public function getAction()
    {
        
    }
    
    /**
     * Set application directories
     *
     * @param array $dirs
     * @return Qwin_App
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