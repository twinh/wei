<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A widget helper to build URL
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Router $router Router
 */
class Url extends AbstractWidget
{
    protected $names = array(
        'module',
        'controller',
        'action'
    );
        
    public function __invoke($uri, $params = array())
    {
        $uris = explode('/', $uri);
        $arr = array();
        
        switch (count($uris)) {
            case 1:
                $arr[$this->names[1]] = $uris[0];
                break;
                
            case 2:
                $arr[$this->names[1]] = $uris[0];
                $arr[$this->names[2]] = $uris[1];
                break;
                
            case 3:
                $arr[$this->names[0]] = $uris[0];
                $arr[$this->names[1]] = $uris[1];
                $arr[$this->names[2]] = $uris[2];
                break;
                
            default:
                throw new Exception\InvalidArgumentException('');
        }
        
        if (is_string($params)) {
            parse_str($params, $params);
        }

        return $this->router->uri($arr + $params);
    }
}
