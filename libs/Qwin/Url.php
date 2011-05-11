<?php
/**
 * Url
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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

class Qwin_Url
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
     * 路由器对象
     * @var Qwin_Url_Router
     */
    protected $_router;

    /**
     * 路由器对象, 当禁用路由器时,由此变量存储路由器对象
     * @var Qwin_Url_Router
     */
    protected $_routerTmp;

    /**
     * 初始化,按需引入路由器对象
     *
     * @param Qwin_Url_Router $router 路由器
     */
    public function __construct(Qwin_Url_Router $router = null)
    {
        $this->_router = $router;
    }

    /**
     * 构建url查询字符串,功能类似http_build_query
     *
     * @param array $data url数组,如$_GET
     * @return string
     */
    public function build(array $data = null)
    {
        // TODO 是否应该引入Qwin_Request
        if (null == $data) {
            $data = $_GET;
        }

        // 对传入的多个参数进行合并
        if (1 < func_num_args ()) {
            $data = array();
            foreach(func_get_args() as $arg) {
                $data = array_merge($data, $arg);
            }
        }

        if ($this->_router) {
            return $this->_router->build($data);
        }
        return '?' . strtr(urldecode(http_build_query($data)), array('&amp;' => '&'));
    }

    /**
     * 解析一个url查询字符串
     *
     * @param string $url url查询字符串
     * @return array|false 解析结果
     */
    public function parse($url = null)
    {
        if (null == $url) {
            $url = $_SERVER['QUERY_STRING'];
        }
        if ($this->_router) {
            return $this->_router->parse($url);
        }
        parse_str($url, $get);
        return $get;
    }

    /**
     * 禁用路由器
     *
     * @return Qwin_Url 当前对象
     */
    public function disableRouter()
    {
        if (isset($this->_router)) {
            $this->_routerTmp = $this->_router;
            $this->_router = null;
            return $this;
        }
        // 没有设置路由器
        return false;
    }

    /**
     * 启用路由器
     *
     * @return Qwin_Url 当前对象
     */
    public function enableRouter()
    {
        if (isset($this->_routerTmp)) {
            $this->_router = $this->_routerTmp;
            return $this;
        }
        return false;
    }

    /**
     * 获取路由器对象
     *
     * @return Qwin_Url_Router
     */
    public function getRouter()
    {
        return $this->_router;
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
     * @return 链接
     */
    public function url($value1, $value2 = 'index', array $params = array())
    {
        return $this->build(array(
            $this->_url['name1'] => $value1,
            $this->_url['name2'] => $value2,
        ) + $params);
    }
}