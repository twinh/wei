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

class Qwin_Application_Controller
{
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
     * 语言对象
     * @var object
     */
    protected $_lang;

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
    protected $_forbiddenAction = array();

    public function __construct()
    {
        
    }

    /**
     * 获取禁用的行为列表
     *
     * @return array
     */
    public function getForbiddenAction()
    {
        return $this->_forbiddenAction;
    }

    /**
     * 设置禁用行为
     *
     * @param string $action 行为名称,即方法名去除'action'标识
     * @return Qwin_Application_Controller 当前对象
     */
    public function setForbiddenAction($action)
    {
        if (method_exists($this, 'action' . $action)) {
            $this->_forbiddenAction[] = strtolower($action);
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
            $this->_view = new $class;
        }
        return $this->_view;
    }
}
