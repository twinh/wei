<?php
/**
 * Abstract
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
 * @since       2011-01-29 18:13:42
 */

abstract class Qwin_Widget_Abstract
{
    /**
     * 配置选项
     *
     * @var array
     */
    protected $_option = array();

    /**
     * 微件的根目录
     * @var string
     */
    protected $_rootPath;

    /*
     * 微件单例对象
     * @var Qwin_Widget
     */
    protected $_widget;

    abstract public function render($option);

    public function  __construct()
    {
        $this->_widget = Qwin_Widget::getInstance();
    }

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    /**
     * 获取配置选项
     *
     * @param string $name 配置名称
     * @return mixed
     */
    public function getOption($name = null)
    {
        if (null == $name) {
            return $this->_option;
        }
        return isset($this->_option[$name]) ? $this->_option[$name] : null;
    }

    /**
     * 生成属性字符串
     *
     * @param array $option 属性数组,键名表示属性名称,值表示属性值
     * @return string 属性字符串
     */
    public function renderAttr($option)
    {
        $attr = '';
        foreach ($option as $name => $value) {
            $attr .= $name . '="' . htmlspecialchars($value) . '" ';
        }
        return $attr;
    }

    /**
     * 获取根目录
     *
     * @param string $file 应为常量 __FILE__
     * @return string
     */
    protected function getRootPath($file)
    {
        if (!isset($this->_rootPath)) {
            if (isset($_SERVER['SCRIPT_FILENAME'])) {
                $realPath = $_SERVER['SCRIPT_FILENAME'];
            } else {
                $realPath = realpath('./') ;
            }
            $file = (str_replace('\\', '/', $file));
            $this->_rootPath = dirname($this->getRelativePath($realPath, $file));
        }
        return $this->_rootPath;
    }

    /**
     * 获取第二个路径对于第一个路径的相对路径
     *
     * @param string $from 第一个路径
     * @param string $to 第二个路径
     * @return string
     * @see http://stackoverflow.com/questions/2637945/getting-relative-path-from-absolute-path-in-php
     */
    public function getRelativePath($from, $to)
    {
        $from     = explode('/', $from);
        $to       = explode('/', $to);
        $relPath  = $to;

        foreach($from as $depth => $dir) {
            // find first non-matching dir
            if($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

    /**
     * 合并两个数组
     *
     * @param array $array1 数组1
     * @param array $array2 数组2
     * @return array
     */
    protected function merge(array $array1 = null, array $array2 = null)
    {
        if (null == $array2) {
            return $array1;
        }
        foreach ($array2 as $key => $val) {
            if (is_array($val)) {
                !isset($array1[$key]) && $array1[$key] = array();
                $array1[$key] = $this->merge($array1[$key], $val);
            } else {
                $array1[$key] = $val;
            }
        }
        return $array1;
    }
}