<?php
/**
 * ApplicationFile
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
 * @package     Project
 * @subpackage  Helper
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-17 11:39:36
 */

class Project_Helper_ApplicationFile
{
    /**
     * 创建一个控制器文件
     *
     * @param string $namespace 命名空间
     * @param string $module 模块
     * @param string|null $controller 控制器
     */
    public function createControllerFile($namespace, $module, $controller = null)
    {
        null == $controller && $controller = $module;
        $file = QWIN_RESOURCE_PATH . '/template/application/controller.tpl';
        $content = file_get_contents($file);
        $content = $this->construcFile($content, $namespace, $module, $controller);
        file_put_contents(QWIN_RESOURCE_PATH . '/application/' . $namespace . '/' . $module . '/Controller/' . $controller . '.php', $content);
    }

    /**
     * 创建一个元数据文件
     *
     * @param string $namespace 命名空间
     * @param string $module 模块
     * @param string|null $controller 控制器
     */
    public function createMetadataFile($namespace, $module, $controller = null)
    {
        null == $controller && $controller = $module;
        $file = QWIN_RESOURCE_PATH . '/template/application/metadata.tpl';
        $content = file_get_contents($file);
        $content = $this->construcFile($content, $namespace, $module, $controller);
        file_put_contents(QWIN_RESOURCE_PATH . '/application/' . $namespace . '/' . $module . '/Metadata/' . $controller . '.php', $content);
    }

    /**
     * 创建一个模型文件
     *
     * @param string $namespace 命名空间
     * @param string $module 模块
     * @param string|null $controller 控制器
     */
    public function createModelFile($namespace, $module, $controller = null)
    {
        null == $controller && $controller = $module;
        $file = QWIN_RESOURCE_PATH . '/template/application/model.tpl';
        $content = file_get_contents($file);
        $content = $this->construcFile($content, $namespace, $module, $controller);
        file_put_contents(QWIN_RESOURCE_PATH . '/application/' . $namespace . '/' . $module . '/Model/' . $controller . '.php', $content);
    }

    /**
     * 创建一个语言文件
     *
     * @param string $namespace 命名空间
     * @param string $module 模块
     * @param string|null $controller 控制器
     */
    public function createLanguageFile($namespace, $module, $language = 'en-US')
    {
        $language = ucfirst(strtolower($language));
        $file = QWIN_RESOURCE_PATH . '/template/application/language.tpl';
        $content = file_get_contents($file);
        $content = $this->construcFile($content, $namespace, $module, $language);
        file_put_contents(QWIN_RESOURCE_PATH . '/application/' . $namespace . '/' . $module . '/Language/' . $language . '.php', $content);
    }

    /**
     * 构造文件
     *
     * @param string $content 内容
     * @param string $namespace 命名空间
     * @param string $module 模块
     * @param string|null $controller 控制器
     */
    protected function construcFile($content, $namespace, $module, $controller = null)
    {
        null == $controller && $controller = $module;
        $search = array(
            '${name}',
            '${year}',
            '${namespace}',
            '${module}',
            '${author}',
            '${email}',
            '${dateTime}'

        );
        $replace = array(
            $controller,
            date('Y', $_SERVER['REQUEST_TIME']),
            $namespace,
            $module,
            'Twin Huang',
            'twinh@yahoo.cn',
            date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
        );
        $content = str_replace($search, $replace, $content);
        return $content;
    }
}
