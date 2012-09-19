<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Url
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo add path ?
 */
class Url extends Widget
{
    /**
     * @var array Options
     *
     *       names 提供一个只有两个值的数组,供快速构建url服务
     */
    public $options = array(
        'names' => array(
            'controller',
            'action'
        ),
    );

    /**
     * 快速构建链接
     *
     * 大多数的应用,都是通过两个参数定位请求,
     * 例如Qwin使用的是的模块(module)和操作(action)两个参数,
     * 其他一些程序可能是使用控制器(controller)和操作(action),
     * 或是模块(mod,m)和操作(act,a)的缩写等等
     * 通过该方法,可以减少编写链接的工作量,同时将参数名称隐藏起来
     *
     * @param string $value1 值1
     * @param string $value2 值2
     * @param array $params 其他参数
     * @return string
     */
    public function __invoke($value1, $value2 = 'index', array $params = array())
    {
        return $this->router->uri(array(
            $this->options['names'][0] => $value1,
            $this->options['names'][1] => $value2,
        ) + $params);
    }
}
