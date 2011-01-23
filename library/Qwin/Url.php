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
     * 路由器对象
     * @var Qwin_Url_Router
     */
    protected $_router;

    /**
     * 路由器对象, 当禁用路由器时,由此变量存储路由器对象
     * @var Qwin_Url_Router
     */
    protected $_routerTmp;

    public function __construct(Qwin_Url_Router $router = null)
    {
        $this->_router = $router;
    }

    /**
     * 构建url查询字符串
     *
     * @param array $data
     * @param <type> $prefix
     * @return <type>
     */
    public function build(array $data, $prefix = null)
    {
        return http_build_query($data, $prefix);
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
        } else {
            return parse_str($url);
        }
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
}