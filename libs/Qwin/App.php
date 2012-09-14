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
        
        // get request & action
        $controller = $options['controller'] ? $options['controller'] : $this->request('controller');
        $action = $options['action'] ? $options['action'] : $this->request('action');

        foreach ($options['dirs'] as $namespace => $dir) {
            $file = $dir . '/' . $namespace . '/Controller/' . ucfirst($controller) . 'Controller.php';

            if (!is_file($file)) {
                continue;
            }

            require $file;
            
            $class = $namespace . '\Controller\\' . ucfirst($controller) . 'Controller';

            if (!class_exists($class, false)) {
                continue;
            }

            $controllerObject = new $class(array(
                'widget' => $this->widget
            ));

            $actionMethod = $action . 'Action';
            if (method_exists($controllerObject, $actionMethod)) {
                return $controllerObject->$actionMethod();
            }
        }
        
        return $this->exception('The page you requested was not found.', 404);
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