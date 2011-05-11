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
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

abstract class Qwin_Application_Controller
{
    /**
     * 视图对象
     * @var Qwin_Application_View
     */
    protected $_view;

    /**
     * 禁用的行为列表
     * 当行为被禁用时,无法通过外部进行访问
     * 通过禁用行为,可以用于精确的
     *
     * @var array
     */
    protected $_unableActions = array();

    public function __construct()
    {
        return $this;
    }

    /**
     * 根据模块获取控制器对象
     *
     * @param string $module 模块标识
     * @return Qwin_Application_Controller 控制器对象
     * @todo 当类不存在时,是否需要用父类?
     * @todo 是否应该考虑存在性,安全性
     */
    public static function getByModule($module, $instanced = true, $param = null)
    {
        if ($module instanceof Qwin_Module) {
            $class = $module->toClass();
        } else {
            $class = Qwin_Module::instance($module)->toClass();
        }
        $class .= '_Controller';
        return $instanced ? Qwin::call($class, $param) : $class;
    }

    /**
     * 获取禁用的行为列表
     *
     * @return array
     */
    public function getForbiddenActions()
    {
        return $this->_unableActions;
    }

    /**
     * 设置禁用行为
     *
     * @param string $action 行为名称,即方法名去除'action'标识
     * @return Qwin_Application_Controller 当前对象
     */
    public function setForbiddenActions($action)
    {
        if (method_exists($this, 'action' . $action)) {
            $this->_unableActions[] = strtolower($action);
        }
        return $this;
    }

    /**
     * 获取视图对象
     *
     * @param string $class 新的视图类名,可选
     * @return Qwin_Application_View
     */
    public function getView($class = null)
    {
        if (isset($class) && class_exists($class)) {
            $this->_view = Qwin::call($class);
        }
        return $this->_view;
    }
}
