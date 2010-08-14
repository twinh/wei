<?php
/**
 * Controller
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
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Trex_Controller
{
    /**
     * 行为重置时,用于保存原来行为的名称
     * @var string
     */
    private $_lastAction;

    /**
     * 模型对象
     * @var object
     */
    protected $_model;

    /**
     * 元数据对象
     * @var object
     */
    protected $_meta;

    /**
     * 视图配置
     */
    protected $_view = array(
        'class' => 'Qwin_Trex_View_Null',
        'data' => null,
        'element' => null,
        'layout' => null,
    );

    /**
     * 设置新的行为
     *
     * @param string $newAction 新的行为名称
     * @return object 当前类
     */
    public function setAction($newAction)
    {
        $this->_lastAction = $this->_set['action'];
        $this->_set['action'] = $newAction;
        return $this;
    }

    /**
     * 恢复为上一个行为
     *
     * @return string Action 的名称
     * @return object 当前类
     */
    public function resetAction()
    {
        $this->_set['action'] = $this->_lastAction;
        return $this;
    }

    /**
     * 根据视图配置加载视图类
     *
     * @param string $class 新的视图类名
     * @return object 视图类
     * @todo 类的检查
     */
    public function loadView($class = null)
    {
        Qwin::load($class);
        if(null != $class && class_exists($class))
        {
            $this->_view['class'] = $class;
        }
        $view = Qwin::run($this->_view['class']);
        isset($this->_view['data']) && $view->setVarList($this->_view['data']);
        isset($this->_view['element']) && $view->setElementList($this->_view['element']);
        isset($this->_view['layout']) && $view->setLayout($this->_view['layout']);
        return $view;
    }

    public function setView($type, $data = null)
    {
        $argv = func_get_args();
        switch ($type)
        {
            case 'alert' :
                $this->_view['class'] = 'Qwin_Trex_View_Alert';
                $this->_view['data']['message'] = $argv[1];
                $this->_view['data']['method'] = isset($argv[2]) ? $argv[2] : null;
                break;
            case 'text' :
                $this->_view['class'] = 'Qwin_Trex_View_Text';
                $this->_view['data']['data'] = $argv[1];
                break;
            default :
                break;
        }
        return true;
    }

    /**
     * 执行 on 方法
     * 
     * @param string $method
     * @return object 当前类
     */
    public function executeOnFunction($method)
    {
        if(method_exists($this, 'on' . $method))
        {
            $args = func_get_args();
            array_shift($args);
            call_user_func_array(array($this, 'on' . $method), $args);
        }
        return $this;
    }

    /**
     * 翻译单独一个代号
     * 
     * @param string $code 要翻译的代号
     * @return string 如果存在该代号,返回翻译值,否则返回原代号
     * TODO 是否应该放在控制器中
     */
    public function t($code)
    {
        //!isset($this->lang) && $this->lang = Qwin::run('-c')->lang;
        if(isset($this->lang[$code]))
        {
            return $this->lang[$code];
        }
        return $code;
    }

    public function _()
    {
        
    }

    /**
     * 快速初始一个类
     * @param <type> $name
     * @return <type>
     */
    public function __get($name)
    {
        if('Qwin_' == substr($name, 0, 5))
        {
            return Qwin::run($name);
        }
        return null;
    }
}
