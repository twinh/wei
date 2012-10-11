<?php
/**
 * Qwin Library
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Controller
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class Controller extends Widget
{
    /**
     * Execute the controller's action method
     *
     * @param  string $action the action name
     * @return mixed
     * @todo add events
     */
    public function __invoke($action = 'index')
    {
        $method = $action . 'Action';

        if (method_exists($this, $method)) {
            $this->trigger('before.action');

            $result = $this->$method();

            $this->trigger('after.action');

            return $result;
        }

        $this->log(sprintf('Action "%s" not found in controller "%s".', $action, get_class($this)));

        throw new \Exception('The page you requested was not found.', 404);
    }

    /**
     * Set or get options
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return mixed
     */
    public function option($name = null, $value = null)
    {
        // load options data from controller "options" dir
        if (1 == func_num_args() && (is_string($name) || is_int($name))) {
            if (!isset($this->options[$name])) {
                foreach ($this->app->options['dirs'] as $dir) {
                    $file = $dir . '/' . ucfirst($this->module()) . '/options/'  . $name . '.php';
                    if (is_file($file)) {
                        $this->options[$name] = require $file;
                        break;
                    }
                }
            }
            // get option
            return parent::option($name);
        }
        // other actions
        return parent::option($name, $value);
    }
}
