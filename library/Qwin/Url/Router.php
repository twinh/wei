<?php
/**
 * Router
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-24 01:05:28
 * @todo        是否需要实现路由器抽象类,接口类
 * @todo        是否需要实现参数占位符(问号参数和命名参数)
 * @todo        是否需要实现域名模式等
 */

class Qwin_Url_Router
{
    /**
     * @var array $_option  数据转换的选项
     *
     *      -- pattern      正则模式,不包含分隔符和修正符,默认为忽略大小写
     *
     *      -- match        与正则匹配结果对应的数组
     *
     *      -- default      默认的参数值(url形式的字符串或数组)
     *
     * @todo 能否允许自定义分隔符和修正符
     */
    protected $_option = array(
        'pattern'   => '',
        'match'     => array(),
        'default'   => '',
    );

    /**
     * 储存路由的数组
     *
     * @var array
     */
    protected $_data = array();

    /**
     * 解析成功时所应用的路由
     *
     * @var mixed
     */
    protected $_parsedRoute = false;

    protected $_search = array(
        '\\/', '\\-', '\\$', '\\(',  '\\)', '\\*',  '\\+', '\\.', '\\[',
        '\\]', '\\?', '\\\\', '\\^', '\\{', '\\}', '\\|',
    );
    
    protected $_replace = array(
        '/', '-', '$', '(', ')', '*',  '+', '.', '[',
        ']', '?', '\\', '^', '{', '}', '|',
    );

    /**
     * 添加一条路由,参数多的路由器应该优先添加
     *
     * @param array $route 路由配置
     * @param mixed $name 路由名称
     * @return Qwin_Url_Router 当前对象
     */
    public function add(array $route, $name = null)
    {
        $option =  $route + $this->_option;

        $option['pattern'] = '#^' . $option['pattern'] . '#i';

        // 检测正则模式是否有效
        if (false === preg_match($option['pattern'], '')) {
            throw new Qwin_Url_Router_Exception('The pattern ' . $option['pattern'] . ' is invalid' );
        }

        // 如果默认值是url字符串形式,自动转换为数组形式
        if (is_string($option['default'])) {
            $option['default'] = parse_str($option['default']);
        }

        if (null == $name) {
            $this->_data[] = $option;
        } else {
            $this->_data[$name] = $option;
        }
        return $this;
    }

    /**
     * 添加一组路由
     *
     * @param array $routeList 路由配置数组,键名表示名称
     * @return Qwin_Url_Router 当前对象
     */
    public function addList(array $routeList)
    {
        foreach ($routeList as $name => $route) {
            $this->add($route, $name);
        }
        return $this;
    }

    /**
     * 删除一个路由
     *
     * @param string $name 路由名称
     * @return Qwin_Url_Router 当前对象
     */
    public function delete($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     * 解析一条查询字符串,匹配则放回查询数组,否则返回false
     *
     * @param string $query
     * @return array|false
     */
    public function parse($query)
    {
        foreach ($this->_data as $router) {
            preg_match($router['pattern'], $query, $match);

            // 匹配不成功
            if (empty($match)) {
                continue;
            }

            // 剔除原始值
            array_shift($match);

            // 正则匹配数组和匹配键名的数目
            $difference = count($match) - count($router['match']);
            // 填充null值,使两边长度一致
            if (0 > $difference) {
                $match = array_pad($match, count($router['match']), null);
            // 删除多余的匹配值
            } elseif (0 < $difference) {
                $match = array_slice($match, 0, '-' . $difference);
            }

            // 合并各值,优先级为 默认值 > 配置值
            $get = array_combine($router['match'], $match) + $router['default'];

            // todo 新旧版的php对http_build_query函数的改动是否会造成差异
            $query = strtr(urldecode(http_build_query($get)), array('&amp;' => '&'));
            parse_str($query, $get);

            $this->_parsedRoute = $router;

            return $get;
        }
        return false;
    }

    public function build($data)
    {
        $url = '';
        
        // 根据提供的数据,找出和的路由match键名包含在$data中
        foreach ($this->_data as $route) {
            // 交集等于匹配键名数组才意味着匹配的可能性
            $filp = array_flip($route['match']);
            if (count($filp) != count(array_intersect_key($data, $filp))) {
                continue;
            }

            $tmpData = array_merge($route['default'], $data);

            // 提取正则的模式部分
            $pattern = substr($route['pattern'], 2, -2);

            // 按()分割
            $partList = preg_split('/\((.+?)\)/i', $pattern);
            
            // 补全匹配键名数组,并倒序,方便对可选模式(?)的处理
            $match = array_reverse(array_values(array_pad($route['match'], count($partList), null)));
            $partList = array_reverse($partList);

            $url = '';
            foreach ($partList as $key => $part) {
                // 前面部分可选
                if (isset($part[0]) && '?' == $part[0]) {
                    if (!isset($tmpData[$match[$key]])) {
                        $part = substr($part, 1);
                        $url = str_replace($this->_search, $this->_replace, $part) . $url;
                        unset($tmpData[$match[$key]]);
                    }
                } else {
                    !isset($tmpData[$match[$key]]) && $tmpData[$match[$key]] = '';
                    $url = str_replace($this->_search, $this->_replace, $part) . $tmpData[$match[$key]] . $url;
                    unset($tmpData[$match[$key]]);
                }
            }
            $data = $tmpData;
            break;
        }
        return $url . '?' . strtr(urldecode(http_build_query($data)), array('&amp;' => '&'));
    }
    
    /**
     * 获取上一条成功解析的路由
     *
     * @return array
     */
    public function getParsedRoute()
    {
        return $this->_parsedRoute;
    }
}