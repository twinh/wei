<?php
/**
 * Module
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
 * @package     Com
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-16 17:11:16
 */

class Com_Management_Controller_Module extends Com_Controller
{
    /**
     * Qwin_Application_Application对象
     * @var object
     */
    protected $_app;

    /**
     * 应用目录的路径
     * @var string
     */
    protected $_path;

    /**
     * 命名空间列表
     * @var array
     */
    protected $_packageList;

    /**
     * 当前的命名空间
     * @var string
     */
    protected $_package;

    /**
     * 当前命名空间的模块列表
     * @var array
     */
    protected $_moduleList;

    /**
     * 命名空间是否存在
     * @var boolen
     */
    protected $_isPackageExists;

    public function  __construct()
    {
        $this->_app = Qwin::call('Qwin_Application_Application');
        $this->_path = $this->_app->getDefultPath();

        // 初始化部分常用对象
        parent::__construct();

        // 检查命名空间是否存在
        $this->_packageList = $this->_app->getPackage($this->_path);
        $this->_package = $this->request->r('package_value');
        if(!in_array($this->_package, $this->_packageList))
        {
            $this->_isPackageExists = false;
        }

        $allModule = $this->_app->getModule($this->_path, $this->_packageList);

        $this->_moduleList = empty($allModule[$this->_package]) ? array() : $allModule[$this->_package];
    }

    /**
     * 查看指定命名空间的模块列表
     *
     * @return object 当前对象
     */
    public function actionIndex()
    {
        if(false === $this->_isPackageExists)
        {
            return $this->view->redirect($this->_lang->t('MSG_NAMESAPCE_NOT_EXISTS'));
        }

        $meta = $this->_meta;
        $theme = Qwin::call('-ini')->getConfig('interface.theme');
        $package = $this->_package;

        // 构建数组
        $data = array();
        foreach($this->_moduleList as $key => $value)
        {
            $data[] = array(
                'id' => $key + 1,
                'module' => $value,
            );
        }
        $listField = $meta['field']->getAttrList('isList');
        $data = $this->metaHelper->sanitiseArray($data, 'list', $meta, $this);

        $this->_view = array(
            'class' => 'Com_View',
            'element' => array(
                array('content', QWIN_PATH . '/view/theme/' . $theme . '/element/management/module-list.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    public function actionAdd()
    {
        if(empty($_POST))
        {
            $layout = $this->metaHelper->getAddLayout($meta);
            $meta['field'] = $this->_meta->field;
            $meta['field']->set('package_value.form._value', $this->_package);
            $banModule = implode(',', $this->_moduleList);

            $theme = Qwin::call('-ini')->getConfig('interface.theme');

            $jQueryValidateCode = Qwin::call('-arr')->jsonEncode($this->_meta->getJQueryValidateCode($meta['field']));
            $this->_view = array(
                'class' => 'Com_View',
                'element' => array(
                    array('content', QWIN_PATH . '/view/theme/' . $theme . '/element/management/add-module.php'),
                ),
                'data' => get_defined_vars(),
            );
        } else {
            $module = $this->request->post('module');
            
            if(false === $this->_isPackageExists)
            {
                return $this->view->redirect($this->_lang->t('MSG_NAMESAPCE_NOT_EXISTS'));
            }

            // 模块已存在
            if(in_array($module, $this->_moduleList))
            {
                return $this->view->redirect($this->_lang->t('MSG_MODULE_EXISTS'));
            }

            // 创建模块,同时创建默认目录结构
            $path = $this->_path . '/' . $this->_package . '/' . $module;
            mkdir($path);
            mkdir($path . '/Controller');
            mkdir($path . '/Meta');
            mkdir($path . '/Model');
            mkdir($path . '/Language');

            // 创建默认控制器,元数据,模型,语言类
            $applicationFile = Qwin::call('Project_Helper_ApplicationFile');
            $applicationFile->createControllerFile($this->_package, $module);
            $applicationFile->createMetaFile($this->_package, $module);
            $applicationFile->createModelFile($this->_package, $module);
            $applicationFile->createLanguageFile($this->_package, $module, $this->getLanguage());

            $url = Qwin::call('-url')->url($this->_asc, array('action' => 'Index', 'package_value' => $this->_package));
            return $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }
}
