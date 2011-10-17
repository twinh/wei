<?php
/**
 * Url
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @package     Qwin
 * @subpackage  Url
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Url extends Qwin_Widget
{
    /**
     * 提供一个只有两个值的数组,供快速构建url服务
     * @var array
     * @todo 允许配置
     */
    protected $_url = array(
        'name1' => 'module',
        'name2' => 'action',
    );

    /**
     * 默认选项
     * @var array
     */
    public $options = array(
        'basicParams' => array(),
        'names' => array(
            'module',
            'action'
        ),
    );

    /**
     * 构建url查询字符串,功能类似http_build_query
     *
     * @param array $data url数组,如$_GET
     * @return string
     */
    public function build(array $data = null)
    {
        if (null == $data) {
            $data = $_GET;
        } else {
            $data += $this->options['basicParams'];
        }

        // 对传入的多个参数进行合并
        if (1 < func_num_args ()) {
            $data = array();
            foreach(func_get_args() as $arg) {
                $data = array_merge($data, $arg);
            }
        }
        
        return '?' . strtr(urldecode(http_build_query($data)), array('&amp;' => '&'));
    }

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
    public function url($value1, $value2 = 'index', array $params = array())
    {
        return $this->build(array(
            $this->options['names'][0] => $value1,
            $this->options['names'][1] => $value2,
        ) + $params);
    }
}
