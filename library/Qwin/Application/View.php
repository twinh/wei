<?php
/**
 * View
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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-06 19:25:40
 * @todo        视图元素允许为代码段或以其他形式出现?
 * @todo        css,js等如何加载?
 * @todo        是否需要分为几个对象 $tihs->tag, $this->layout, $this->element...
 */

abstract class Qwin_Application_View extends Qwin_Metadata_Abstract
{
    /**
     * 变量数组
     * @var array
     */
    protected $_data;

    /**
     * 视图元素数组
     * @var array
     */
    protected $_element;

    /**
     * 布局文件路径
     * @var string
     */
    protected $_layout;

    /**
     * 标签,表示变量标识符,用于布局和视图元素的路径中
     * @var array
     */
    protected $_tag = array(
        'theme' => 'default',
        'style' => 'default',
    );

    /**
     * 标签名称,用于替换标签,格式为 <标签的键名>
     * @var array
     * @todo 目前采用的是用空间换取时间的方式,是否有更好的方法加速标签替换
     */
    protected $_tagName = array(
        'theme' => '<theme>',
        'style' => '<style>'
    );

    /**
     * 设置一组变量
     *
     * @param array $list 变量组,键名为变量名称,值为变量的值
     * @return object 当前对象
     */
    public function setVarList(array $list)
    {
        foreach($list as $key => $var)
        {
            $this->_data[$key] = $var;
        }
        return $this;
    }

    /**
     * 设置变量数组
     *
     * @param array $data 变量数组
     * @return Qwin_Application_View 当前对象
     */
    public function mergeVarData(array $data)
    {
        $this->_data += $data;
        return $this;
    }

    /**
     * 设置一个变量
     *
     * @param string $name 变量名称
     * @param mixed $value 变量的值
     * @return object 当前对象
     */
    public function setVar($name, $value)
    {
        $this->_data[$name] = $value;
        return $this;
    }

    /**
     * 销毁一个变量
     *
     * @param string $name 变量名称
     * @return object 当前对象
     */
    public function unsetVar($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     * 获取变量的值
     *
     * @param string $name
     * @return mixed 变量的值
     */
    public function getVar($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * 清空变量
     *
     * @return Qwin_Application_View 当前对象
     */
    public function clearVar()
    {
        $this->_data = array();
        return $this;
    }

    /**
     * 设置一组视图元素
     *
     * @param array $list 视图元素组,键名为视图名称,值为视图的值
     * @return Qwin_Application_View 当前对象
     */
    public function setElementList(array $list)
    {
        foreach ($list as $key => $value) {
            !is_array($value) && $value = array($value);
            $this->_element[$key] = $value;
        }
        return $this;
    }

    /**
     * 设置一个视图元素
     *
     * @param string $name 视图元素的名称
     * @param string|mixed $element 视图元素的路径
     * @return Qwin_Application_View 当前对象
     */
    public function setElement($name, $element)
    {
        !is_array($element) && $element = array($element);
        $this->_element[$name] = $element;
        return $this;
    }


    /**
     * 获取未处理的视图元素
     *
     * @param string $name
     * @return string 视图元素
     */
    public function getRawElement($name)
    {
        return $this->_element[$name];
    }

    public function getElement($name)
    {
        if (!isset($this->_element[$name])) {
            throw new Qwin_Application_View_Exception('Undefined element name: ' . $name);
        }
        $pathCahce = array();
        foreach ($this->_element[$name] as $path) {
            $path = $this->decodePath($path);
            if (is_file($path)) {
                return $path;
            }
            $pathCahce[] = $path;
        }
        throw new Qwin_Application_View_Exception('All of the element files are not exists: ' . implode(';', $pathCahce));
    }

    /**
     * 销毁视图元素
     *
     * @param string $name
     * @return object 当前对象
     */
    public function unsetElement($name)
    {
        if (isset($this->_element[$name])) {
            unset($this->_element[$name]);
        }
        return $this;
    }

    /**
     * 清空视图元素数组
     *
     * @return Qwin_Application_View 当前对象
     */
    public function clearElement()
    {
        $this->_element = array();
        return $this;
    }

    /**
     * 设置布局文件的路径,布局可以为一个或多个,多个将按照键名优先级和存在性选择
     *
     * @param string|array $layout
     * @return object 当前对象
     */
    public function setLayout($layout)
    {
        !is_array($layout) && $layout = array($layout);
        $this->_layout = $layout;
        return $this;
    }

    /**
     * 获取未处理的布局配置
     *
     * @return array 布局配置
     */
    public function getRawLayout()
    {
        return $this->_layout;
    }

    /**
     * 获取经过解码的布局路径
     *
     * @return string
     */
    public function getLayout()
    {
        $pathCahce = array();
        foreach ($this->_layout as $path) {
            $path = $this->decodePath($path);
            if (is_file($path)) {
                return $path;
            }
            $pathCahce[] = $path;
        }
        throw new Qwin_Application_View_Exception('All of the layout files are not exists: ' . implode(';', $pathCahce));
    }
    
    /**
     * 视图展示之前,用于视图数据处理,构建等
     *
     * @return Common_View 当前对象
     */
    public function preDisplay()
    {
        return $this;
    }

    /**
     * 展示视图
     *
     * @return Qwin_Application_View 当前对象
     */
    public function display()
    {
        return $this;
    }

    /**
     * 视图展示之后,用于视图数据的回调处理等
     *
     * @return Common_View 当前对象
     */
    public function afterDisplay()
    {
        return $this;
    }

    /**
     * 获取一个标签的值
     *
     * @param string $name 标签名称
     * @return 标签的值
     */
    public function getTag($name)
    {
        return isset($this->_tag[$name]) ? $this->_tag[$name] : null;
    }

    /**
     * 设置一个标签的值
     *
     * @param string $name 标签名称
     * @param mixed $value 标签的值
     * @return Qwin_Application_View 当前对象
     */
    public function setTag($name, $value)
    {
        $this->_tag[$name] = $value;
        $this->_tagName[$name] = '<' . $name . '>';
        return $this;
    }

    /**
     * 设置一组标签的值
     *
     * @param array $array 标签数组,键名表示标签名称,值表示标签的值
     * @return Qwin_Application_View 当前对象
     */
    public function setTagList(array $array)
    {
        foreach ($array as $key => $value) {
            // 视图文件名应该是小写和-组成
            $this->_tag[$key] = strtolower($value);
            $this->_tagName[$key] = '<' . $key . '>';
        }
        return $this;
    }

    /**
     * 获取标签数组
     *
     * @return array  标签数组
     */
    public function getTagList()
    {
        return $this->_tag;
    }

    /**
     * 删除一个标签
     *
     * @param string $name 标签名称
     * @return Qwin_Application_View 当前对象
     */
    public function unsetTag($name)
    {
        if (isset($this->_tag[$name])) {
            unset($this->_tag[$name]);
            unset($this->_tagName[$name]);
        }
        return $this;
    }

    /**
     * 清空标签数组
     *
     * @return Qwin_Application_View 当前对象
     */
    public function clearTag()
    {
        $this->_tag = array();
        $this->_tagName = array();
        return $this;
    }

    /**
     * 根据标签设置将标签路径解码为真实路径
     *
     * @param string $path 路径
     * @return 真实路径
     */
    public function decodePath($path)
    {
        return str_replace($this->_tagName, $this->_tag, $path);
    }

    /**
     * 加载一个微件
     *
     * @param string $name 微件的类名
     * @param mixed $param 参数
     * @return string 视图路径
     * @todo 规范化
     * @toto 类检查
     * @todo 允许多种返回类型
     * @todo 变量跨域
     */
    public function getWidget($name, $param = null)
    {
        if (!class_exists($name)) {
            throw new Qwin_Application_View_Exception('The widget class "' . $name . '" is not exists.');
        }
        $object = new $name;
        $file = $object->render($param, $this);
        if (!is_file($file)) {
           throw new Qwin_Application_View_Exception('The widget class should return a available file path.');
        }
        return $file;
    }
}
