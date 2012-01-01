<?php
/**
 * Package
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
 * @package     Com
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-16 16:12:40
 */

class Com_Management_Controller_Package extends Com_Controller
{
    public function  __construct()
    {
        $this->_app = Qwin::call('Qwin_Application_Application');
        $this->_path = $this->_app->getDefultPath();
        $this->_rootAppPath = $this->_app->getRootAppPath();
        parent::__construct();
    }

    /**
     * 查看命名空间列表
     *
     * @return object 当前对象
     */
    public function actionIndex()
    {
        $meta = $this->_meta;
        $theme = Qwin::call('-ini')->getConfig('interface.theme');
        $package[$this->_path] = $this->_app->getPackage($this->_path);
        $package[$this->_rootAppPath] = $this->_app->getPackage($this->_rootAppPath);

        // 构建数组
        $data = array();
        $key = 1;
        foreach($package as $path => $value)
        {
            foreach($value as $name)
            {
                $data[] = array(
                    'id' => $key++,
                    'package' => $name,
                    'path' => $path,
                );
            }
        }
        $listField = $meta['field']->getAttrList('isList');
        $data = $this->metaHelper->sanitiseArray($data, 'list', $meta, $meta);

        // 设置视图
        $this->_view = array(
            'class' => 'Com_View',
            'element' => array(
                array('content', QWIN_PATH . '/view/theme/' . $theme . '/element/management/package-list.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    /**
     * 创建命名空间
     *
     * @return object 当前对象
     */
    public function actionAdd()
    {
        $app = Qwin::call('Qwin_Application_Application');
        if(empty($_POST))
        {
            // 初始化常用的变量
            $metaHelper = Qwin::call('Qwin_Application_Meta');
            $meta = $this->_meta;
            $primaryKey = $meta['db']['primaryKey'];
            $primaryKeyValue = $config['data']['primaryKeyValue'];

            $package = $this->_app->getPackage($this->_path);
            $banPackage = implode(',', $package);

            $theme = Qwin::call('-ini')->getConfig('interface.theme');

            $jQueryValidateCode = Qwin::call('-arr')->jsonEncode($this->_meta->getJQueryValidateCode($meta['field']));
            $this->_view = array(
                'class' => 'Com_View',
                'element' => array(
                    array('content', QWIN_PATH . '/view/theme/' . $theme . '/element/management/add-package.php'),
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
                return $this->view->redirect($message);
            }
            mkdir($this->_path . '/' . $_POST['package']);

            $url = Qwin::call('-url')->url($this->_asc, array('action' => 'Index'));
            return $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    public function actionDelete()
    {
        $name = $this->_request->get('package_value');
        $path = $this->_path . '/' . $name;
        
        // 不存在的目录
        if(!is_dir($path))
        {
            return $this->view->redirect($this->_lang->t('MSG_NAMESAPCE_NOT_EXISTS'));
        }

        // 目录不为空
        $file = scandir($path);
        if(array('.', '..') != $file)
        {
            return $this->view->redirect($this->_lang->t('MSG_NAMESPACE_NOT_EMPTY'));
        }

        // 删除目录,跳转回列表页
        rmdir($path);
        $url = Qwin::call('-url')->url($this->_asc, array('action' => 'Index'));
        return $this->view->redirect($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }
}
