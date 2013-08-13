<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A util widget to build URL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Router $router Router
 */
class Url extends Base
{
    /**
     * @var array
     */
    protected $names = array(
        'module',
        'controller',
        'action'
    );

    /**
     * Build URL path by specified uri and parameters
     *
     * ```php
     * // Returns controller=user&id=admin
     * $this->url('user', array('id' => 'admin'));

     * // Returns controller=user&action=edit&id=>admin
     * $this->url('user/edit', array('id' => 'admin'));
     *
     * // Returns module=api&controller=user&action=edit&id=admin
     * $this->url('api/user/edit', array('id' => 'admin'))
     * ```
     *
     * @param string $uri The uri like "user/edit"
     * @param array|string $parameters Additional URL query parameters
     * @param array|string $_ More additional URL query parameters
     * @return string
     */
    public function __invoke($uri)
    {
        $arr = call_user_func_array(array($this, 'parse'), func_get_args());

        return $this->router->generatePath($arr);
    }

    /**
     * Build full URL by specified uri and parameters
     *
     * @return string
     */
    public function full()
    {
        $arr = call_user_func_array(array($this, 'parse'), func_get_args());

        return $this->router->generateUrl($arr);
    }

    /**
     * @param $uri
     * @return array
     * @throws \InvalidArgumentException When the number of uri parts is not allowed
     */
    protected function parse($uri)
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
                throw new \InvalidArgumentException(sprintf('Unrecognized uri "%s"', $uri));
        }

        $args = func_get_args();
        for ($i = 1, $count = count($args); $i < $count; $i++) {
            if (is_string($args[$i])) {
                parse_str($args[$i], $args[$i]);
            }
            $arr += $args[$i];
        }

        return $arr;
    }
}
