<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * App
 *
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2009-11-24 20:45:11
 * @todo        remove timezone option and add to qwin class
 */
class Qwin_App extends Qwin_Widget
{
    /**
     * @var array           options
     *
     *       dirs           application root dirs
     *
     *       module         default module value
     *
     *       action         default action value
     *
     *       timezone       timezone
     */
    public $options = array(
        'dirs'          => array(),
        'module'        => 'index',
        'action'        => 'index',
        'timezone'      => 'Asia/Shanghai',
    );

    /**
     * 启动应用
     *
     * @param array $options 选项
     * @return Qwin_App
     * @todo 记录状态,防止二次加载?
     * @todo 根据是否debug等抛出不同的错误信息
     */
    public function __invoke(array $options = array())
    {
        $this->marker('appStartup');

        // 合并选项
        $this->option($options);
        $options = &$this->options;

        // 设置默认应用目录
        if (!is_array($options['dirs'])) {
            $options['dirs'] = (array)$options['dirs'];
        }
        if (empty($options['dirs'])) {
            $options['dirs'][] = dirname(dirname(dirname(__FILE__))) . '/apps/';
        }

        // 默认时区
        date_default_timezone_set($options['timezone']);

        // 触发应用启动事件
        $this->trigger('appStartup');

        // 获取模块和行为对象
        $options['module'] = $this->module();
        $options['action'] = $this->action();

        $controller = $this->controller($options['module']);

        if (!$controller || !method_exists($controller, $options['action'] . 'Action')) {
            return $this->error('The page your reuqested not found.', 404);
        }

        //
        $result = $controller->execute($options['action']);

        // 展示视图
        $this->view($result);

        $this->marker('appTermination');

        // 触发应用结束事件
        $this->trigger('appTermination');

        // 返回当前对象
        return $this;
    }

    public function execute($module, $action, $view = true)
    {
        $controller = $this->controller($module);
        if (!$controller) {
            return false;
        }

        $actionMethod = $action . 'Action';
        if (!method_exists($controller, $actionMethod)) {
            return false;
        }

        $result = $controller->execute($action);

        $this->view->renderBy($module, $action);

        return $this;
    }
}