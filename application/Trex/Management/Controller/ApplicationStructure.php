<?php
/**
 * ApplicationStructure
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
 * @package     Trex
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-11 21:15:01
 */

class Trex_Management_Controller_ApplicationStructure extends Trex_Controller
{
    protected function _getAppPath()
    {
        return QWIN_RESOURCE_PATH . '/application';
    }

    public function actionIndex()
    {
        $theme = Qwin::run('-ini')->getConfig('interface.theme');
        $this->_view = array(
            'class' => 'Trex_View',
            'element' => array(
                array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/mangement-application-structure.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    public function actionCreateNamespace()
    {
        $path = $this->_getAppPath();
        if(empty($_POST))
        {
            $groupList = $this->_meta->field->getGroupList();
            $relatedField = $this->_meta->field;
            $namespace = $this->_getNamespace($path);
            $banNamespace = implode(',', $namespace);

            $theme = Qwin::run('-ini')->getConfig('interface.theme');
            
            $jQueryValidateCode = Qwin::run('-arr')->jsonEncode($this->_meta->getJQueryValidateCode($relatedField));
            $this->_view = array(
                'class' => 'Trex_View',
                'element' => array(
                    array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/mangement-create-namespace.php'),
                ),
                'data' => get_defined_vars(),
            );
        } else {
            $validateResult = $this->_meta->validateArray($this->_meta['field'], $_POST, $this);
            if(true !== $validateResult)
            {
                $message = $this->_lang->t('MSG_ERROR_FIELD')
                    . $this->_lang->t($this->_meta['field'][$validateResult->field]['basic']['title'])
                    . '<br />'
                    . $this->_lang->t('MSG_ERROR_MSG')
                    . $this->_meta->format($this->_lang->t($validateResult->message), $validateResult->param);
                return $this->setRedirectView($message);
            }
            mkdir($path . '/' . $_POST['namespace']);

            $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
            return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
        
    }

    public function actionCreateModule()
    {
        
    }

    /**
     * 更新应用目录结构缓存文件,权限分配页面可以根据应用目录的结构进行权限分配
     */
    public function actionUpdate()
    {
        $path = $this->_getAppPath();
        $namespace = $this->_getNamespace($path);
        $module = $this->_getModule($path, $namespace);
        $controller = $this->_getController($path, $module);
        $action = $this->_getAction($path, $controller);

        Qwin_Helper_File::writeAsArray($action, QWIN_ROOT_PATH . '/cache/php/application-structure.php');

        $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
        $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }

    public function validateNamespace($value, $name, $data)
    {
        $path = $this->_getAppPath();
        $namesapceList = $this->_getNamespace($path);
        if(!in_array($value, $namesapceList))
        {
            return true;
        }
        return new Qwin_Validator_Result(false, 'namespace', 'MSG_VALIDATOR_NAMESPACE_EXISTS');
    }

    /**
     * 根据应用的路径,找出所有命名空间,忽略以.和_开头的命名空间
     * .和_是svn或其他程序的文件
     *
     * @param string $path 应用的路径
     * @return array 命名空间数组
     */
    private function _getNamespace($path)
    {
        $namespace = array();
        $path .= '/';
        foreach(scandir($path) as $file)
        {
            $firstLetter = substr($file, 0, 1);
            if($firstLetter == '.' || $firstLetter == '_')
            {
                continue;
            }
            if(is_dir($path . $file))
            {
                $namespace[] = $file;
            }
        }
        return $namespace;
    }

    /**
     * 根据应用的路径和提供的命名空间数组,找出所有的模块,忽略以.和_开头的命名空间
     *
     * @param string $path 应用的路径
     * @param array $namespaceList 命名空间数组,一般由$this->_getNamespace()获取
     * @return array 模块数组
     */
    private function _getModule($path, $namespaceList)
    {
        $module = array();
        $path .= '/';
        foreach($namespaceList as $namespace)
        {
            foreach(scandir($path . $namespace) as $file)
            {
                $firstLetter = substr($file, 0, 1);
                if($firstLetter == '.' || $firstLetter == '_')
                {
                    continue;
                }
                if(is_dir($path . $namespace . '/' . $file))
                {
                    $module[$namespace][] = $file;
                }
            }
        }
        return $module;
    }

    /**
     * 根据应用的路径和提供的模块数组,找出所有的控制器
     *
     * @param string $path 应用的路径
     * @param array $moduleList 模块数组,一般由$this->_getModule()获取
     * @return array 控制器数组
     */
    private function _getController($path, $moduleList)
    {
        $controller = array();
        foreach($moduleList as $namespace => $moduleList2)
        {
            foreach($moduleList2 as $module)
            {
                foreach(scandir($path . '/' . $namespace . '/' . $module . '/Controller') as $file)
                {
                    $name = basename($file, '.php');
                    if($name != $file)
                    {
                        $controller[$namespace][$module][] = $name;
                    }
                }
            }
        }
        return $controller;
    }

    /**
     * 根据应用的路径和提供的控制器数组,找出所有的行为
     *
     * @param string $path 应用的路径
     * @param array $moduleList 控制器数组,一般由$this->_getController()获取
     * @return array 行为数组
     */
    private function _getAction($path, $controllerList)
    {
        $action = array();
        foreach($controllerList as $namespace => $moduleList)
        {
            foreach($moduleList as $module => $controllerList)
            {
                foreach($controllerList as  $controller)
                {
                    $class = $namespace . '_' . $module . '_Controller_' . $controller;
                    /**
                     * 取出action类型的方法
                     */
                    if(class_exists($class))
                    {
                        $methodList = get_class_methods($class);
                        foreach($methodList as $method)
                        {
                            if('action' == substr($method, 0, 6))
                            {
                                $actionName = substr($method, 6);
//                                $method = new ReflectionMethod('Trex_Article_Controller_Article', 'actionIndex');
//                                preg_match("/\/\*\*[\s]+\*\s(.+?)[\s]+\*/i", $doc, $matches);
//                                p($matches);
                                $action[$namespace][$module][$controller][] = $actionName;
                            }
                        }
                    }
                }
            }
        }
        return $action;
    }
}
