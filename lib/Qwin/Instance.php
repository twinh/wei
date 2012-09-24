<?php
/**
 * Qwin Library
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Instance
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Instance extends Widget
{
    /**
     * Instance a class
     *
     * @param string $class the name of class
     * @param array $args the parameters to be passed to the class constructor as an array.
     * @return false|object false when class not found or a instance of the class
     */
    public function __invoke($class, $args = array())
    {
        if (!class_exists($class)) {
            return false;
        }

        // get class arguments
        !is_array($args) && $args = array($args);

        // instance according to the argument number
        switch (count($args)) {
            case 0:
                $object = new $class;
                break;

            case 1:
                $object = new $class(current($args));
                break;

            case 2:
                $object = new $class(current($args), next($args));
                break;

            case 3:
                $object = new $class(current($args), next($args), next($args));
                break;

            default:
                if (method_exists($class, '__construct') || method_exists($class, $class)) {
                    $reflection = new ReflectionClass($class);
                    $object = $reflection->newInstanceArgs($args);
                } else {
                    $object = new $class;
                }
        }
        return $object;
    }
}