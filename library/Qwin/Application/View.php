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
     * 主题
     * @var string
     */
    protected $_theme = 'default';

    public function __construct()
    {
        
    }

    /**
     * 通过setVar设置一组变量
     *
     * @param array $list 变量组
     * @return object 当前对象
     */
    public function setVarList(array $list)
    {
        foreach($list as $key => $var)
        {
            $this->setVar($key, $var);
        }
        return $this;
    }

    /**
     * 设置变量
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
        if(isset($this->_data[$name]))
        {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     * 通过setElement设置一组视图元素
     *
     * @param array $list 视图元素组
     * @return object 当前对象
     */
    public function setElementList($list)
    {
        foreach($list as $name => $element)
        {
            call_user_func_array(array($this, 'setElement'), $element);
        }
        return $this;
    }

    /**
     * 设置视图元素
     *
     * @param string $name 名称
     * @param mixed $element 视图元素的内容
     * @param boolen $isFile 是否为文件,如果不是文件,则为代码段
     * @return object 当前对象
     */
    public function setElement($name, $element, $isFile = true)
    {
        $this->_element[$name] = array(
            'element' => $element,
            'type' => $isFile ? 'file' : 'code',
        );
        return $this;
    }

    /**
     * 销毁视图元素
     *
     * @param string $name
     * @return object 当前对象
     */
    public function unsetElement($name)
    {
        if(isset($this->_element[$name]))
        {
            unset($this->_element[$name]);
        }
        return $this;
    }

    /**
     * 加载视图元素
     *
     * @param <type> $name 视图元素的名称
     */
    public function loadElement($name)
    {
        require $this->_element[$name]['element'];
    }

    /**
     * 获取视图元素
     *
     * @param string $name
     * @return string 视图元素
     */
    public function getElement($name)
    {
        return $this->_element[$name]['element'];
    }

    /**
     * 设置布局文件的路径
     *
     * @param string $layout
     * @return object 当前对象
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * 输出页眉
     *
     * @return boolen
     */
    public function displayHeader()
    {
        return false;
    }

    /**
     * 输出主视图,例如
     * 
     * @return boolen
     */
    public function display()
    {
        return false;
    }

    /**
     * 输出页脚
     *
     * @return boolen
     */
    public function displayFooter()
    {
        return false;
    }
}
