<?php
/**
 * QwUrl 的名称
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
     * 键名映射数组
     * @var array
     */
    private $_map = array();

    public $nca = array('namespace', 'module', 'controller', 'action');
    public $separator = array('&', '=');
    // url 文件后缀,带分割号
    public $extension = '';
    // 通过解码的返回的数组,与 $_GET 类似
    private $_get = array();
    
    // 初始化的模式
    private $_inital_mode = array('overwrite', 'skip');
    
    public function __construct()
    {
        $this->_decodeUrl();
        $this->mapUrl();
    }
    
    /**
     * 通过自定义url分隔符,解码查询字符串
     *
     * @todo 自定义路由
     */
    private function _decodeUrl()
    {
        $query = $_SERVER['QUERY_STRING'];
        // 去除后缀
        $position = strrpos($query, $this->extension);
        if($position)
        {
            $query = substr($query, 0, $position);
        }
        // 使用的是原 url 分割符
        if(array('&', '=') == $this->separator)
        {
            $this->_get = $_GET;
        // 自定义分割符
        } else {
            // 转为一样的分隔符
            if($this->separator[0] != $this->separator[1])
            {
                $query = str_replace($this->separator[1], $this->separator[0], $query);
            }
            $part = explode($this->separator[0], $query);
            for($i = 0, $count = count($part);
                $i <= $count;
                $i += 2
            ){
                // 剔除 null 键名
                if(null != $part[$i])
                {
                    $part[$i] = urldecode($part[$i]);                    
                    $this->_get[$part[$i]] = $part[$i + 1];
                }            
            }
            // 对键名的数组化转换
            $this->_rearrayGetKey($this->_get);
        }
    }

    /**
     * 地址键名映射
     * @param array $map
     * @todo  同步,$_POST
     */
    public function mapUrl($map = null)
    {
        if(null != $map)
        {
            $this->setMap($map);
        }
        foreach($this->_map as $key => $val)
        {
            if(null != $this->_get[$key])
            {
                $this->_get[$val] = $this->_get[$key];
            }
        }
    }

    /**
     * 设置一个地址键名映射
     * @param array $map
     * @return object
     */
    public function setMap(array $map)
    {
        $this->_map = $map;
        return $this;
    }

    /**
     * 添加一个地址键名映射
     * @param string $name
     * @param mixed $value
     * @return object
     */
    public function addMap($name, $value)
    {
        $this->_map[$name] = $value;
        return $this;
    }

    /**
     * 删除一个地址键名映射
     * @param string $name
     * @return object
     */
    public function unsetMap($name)
    {
        if(isset($this->_map[$name]))
        {
            unset($this->_map[$name]);
        }
        return $this;
    }
    
    /**
     * 将 $_get 数组的键名转换为数组
     *
     * @param array $arr $_get 数组
     * @return array 
     * @see $this->_decodeUrl
     */
    private function _rearrayGetKey(&$arr)
    {
        /* 删除重叠的键名
            WHERE[category_id][name]
            WHER 有效
            
            WHERE[category_id][name]
            WHERE 无效
            
            WHERE[category_id][name]
            WHERE[category_id] 无效
        */
        foreach($arr as $key => $val)
        {
            if(false === strpos($key, '['))
            {
                $key .= '[';
            }
            foreach($arr as  $key_2 => $val_2)
            {
                if($key_2 != $key && false !== strpos($key_2, $key))
                {
                    unset($arr[$key]);
                    break;
                }
            }
        }
        foreach($arr as $key => $val)
        {
            preg_match_all("/\[(.+?)\]/", $key, $matches);
            if(0 != count($matches[1]))
            {
                // 第一级键名
                $key_name_1 = substr($key, 0, strpos($key, '['));
                // 后面各级键名,带中括号
                $key_name_2 = '[' . implode('][', $matches[1]) . ']';
                if($key == $key_name_1 . $key_name_2)
                {
                    $matches[1] = array_map('addslashes', $matches[1]);
                    eval('$this->_get[\'' . $key_name_1 . '\'][\'' . implode('\'][\'', $matches[1]) . '\']' . '=' . $val . ';');
                }
                unset($arr[$key]);
            }
        }
    }
    
    /**
     * 获取单个 get 的值
     *
     * @param string $key
     * @return mixed
     */
    public function g($key)
    {
        return isset($this->_get[$key]) ? $this->_get[$key] : NULL;
    }
    
    /**
     * 获取多个 get 的值
     *
     * @param array $arr
     * @return array $arr_2
     */
    public function get($arr)
    {
        $arr = qw('-arr')->set($arr);
        foreach ($arr as $key => $val)
        {
            $arr_2[$val] = $this->g($val);
        }
        return $arr_2;
    }
    /**
     * 获取 $_get 数组
     *
     * @return array $_get数组
     */
    public function getGetArray()
    {
        return $this->_get;
    }
    
    /**
     * 根据提供的 nca 配置, 初始化 nca 的命名表示
     *
     * @param array $set
     */
    public function setNca($set)
    {
        for($i = 0;$i <= 2;$i++)
        {
            isset($set[$i]) && $this->nca[$i] = $set[$i];
        }
    }
    
    /**
     * 获取地址中的 nca 值
     *
     * @param mixed $key_arr 如果 $key_arr 是数组,则以数组的键名作为返回的 nca 的数组的键名
     * @rturn array $set nca数组
     */
    public function getNca($key_arr = NULL)
    {
        if(is_array($key_arr))
        {
            for($i = 0;$i <= 3;$i++)
            {
                $set[$key_arr[$i]] = $this->g($this->nca[$i]);
            }
        } else {
            for($i = 0;$i <= 3;$i++)
            {
                $set[$i] = $this->g($this->nca[$i]);
            }
        }
        return $set;
    }

    /**
     * 设置 url 分割符
     *
     */
    public function setSeparator($set)
    {
        true == isset($set[0]) && $this->separator[0] = $set[0];
        true == isset($set[1]) && $this->separator[1] = $set[1];
    }
    
    /**
     * 根据提供的 nca 配置,和附加参数,转化成 url 
     *
     * @param array $set nca配置
     * @param array $$addition 附加url参数
     * @return string $url
     */
    public function auto($set, $addition = NULL)
    {
        if(is_array($set))
        {
            for($i = 0;$i <= 3;$i++)
            {
                qw('-str')->set($set[$i]);
                $set_2[$this->nca[$i]] = $set[$i] ? $set[$i] : 'Default';
            }    
            $url = '?' . $this->arrayKey2Url($set_2 + $addition);
        } else {
            $url = $set . '?' . $this->arrayKey2Url($addition);
        }
        return $url . $this->extension;
    }
        
    public function adjustPath($path)
    {
        return str_replace(array('\\', '/'), DS, $path);
    }
    
    /**
     * 以数组的键名作为变量的名称,转化为 url 地址
     *
     * @param array $arr 二维以上的数组
     * @return string $url url 地址 
     */
    public function arrayKey2Url($arr)
    {
        $url = '';
        foreach($arr as $key => $val)
        {
            $url .= $this->array2Url($val, $key) . $this->separator[0];
        }
        return substr($url, 0, -1);
    }
    
    /**
     * 将数组转换为 url 地址
     *
     * @param array $arr 可传入字符串,但建议是数组
     * @param string $name 数组的名称
     * @return string $url 数组解码后的地址,如作为 url 地址,可通过 $_GET[$name] 获得原数组
     */
    public function array2Url($arr, $name)
    {
        $url = '';
        if(is_array($arr))
        {
            foreach($arr as $key => $val)
            {
                if(is_array($val))
                {
                    $url .= $this->array2Url($val, $name . '[' . $key . ']') . $this->separator[0];
                } elseif($name) {
                    $url .= $name . '[' . $key . ']' . $this->separator[1] . urlencode($val) . $this->separator[0];
                } else {
                    $url .= $name  . $this->separator[1] . urlencode($val) . $this->separator[0];
                }
            }
        } else {
            return $name . $this->separator[1] . urlencode($arr);
        }
        return substr($url, 0, -1);
    }
    
    public function to($url)
    {
        $str = '<script type="text/javascript">';
        $str .= 'window.location.href="' . $url . '";';
        $str .= '</script>';
        echo $str;
        exit;
    }
    
    /**
     * 获取 url 中的数据
     *
     * @param array $data add.edit等操作传过来的初始数据
     * @param int $mode 
     */
    public function getInitalData($data, $mode = 'ovwewrite')
    {
        !in_array($mode, $this->_inital_mode) && $mode = $this->_inital_mode[0];
        // 覆盖 $data 的值
        if($mode == $this->_inital_mode[0])
        {
            foreach($data as $key => $val)
            {
                if(isset($this->_get['data'][$key]) && '' != $this->_get['data'][$key])
                {
                    $data[$key] = $this->_get['data'][$key];
                }
            }
        } else {
            foreach($data as $key => $val)
            {
                if('' == $val)
                {
                    $data[$key] = $this->_get['data'][$key];
                }
            }
        }
        return $data;
    }
    
    /**
     * 设置 $_get 的值
     * 
     * @todo 完善多种设置形式
     */
    public function setGetData($key, $val)
    {
        $this->_get[$key] = $val;
    }
        
    /*
     * url 补全函数
     * @link http://hi.baidu.com/lukin/blog/item/a82df3039c21fded09fa93c9.html
     * @todo 重写或整理
     */     
}

function url($array, $argvs = array())
{
     return qw('-url')->auto($array, $argvs);
}
