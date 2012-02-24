<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Url
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2009-11-24 20:45:11
 */
class Qwin_Url extends Qwin_Widget
{
    /**
     * @var array Options
     *
     *       names 提供一个只有两个值的数组,供快速构建url服务
     */
    public $options = array(
        'names' => array(
            'module',
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
