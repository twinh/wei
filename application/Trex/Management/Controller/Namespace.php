<?php
/**
 * Namespace
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
 * @subpackage  Management
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-16 16:12:40
 */

class Trex_Management_Controller_Namespace extends Trex_Controller
{
    public function  __construct()
    {
        $this->_app = Qwin::run('Qwin_Trex_Application');
        $this->_path = $this->_app->getDefultPath();
        parent::__construct();
    }

    /**
     * 查看命名空间列表
     *
     * @return object 当前类
     */
    public function actionIndex()
    {
        $meta = $this->_meta;
        $theme = Qwin::run('-ini')->getConfig('interface.theme');
        $namespace = $this->_app->getNamespace($this->_path);

        // 构建数组
        $data = array();
        foreach($namespace as $key => $value)
        {
            $data[] = array(
                'id' => $key + 1,
                'namespace' => $value,
            );
        }
        $listField = $meta['field']->getAttrList('isList');
        $data = $meta->convertMultiData($listField, $meta['field'], 'list', $data, false);

        $this->_view = array(
            'class' => 'Trex_View',
            'element' => array(
                array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/management/namespace-list.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    /**
     * 创建命名空间
     *
     * @return object 当前类
     */
    public function actionAdd()
    {
        $app = Qwin::run('Qwin_Trex_Application');
        if(empty($_POST))
        {
            $groupList = $this->_meta->field->getAddGroupList();
            $relatedField = $this->_meta->field;
            $namespace = $this->_app->getNamespace($this->_path);
            $banNamespace = implode(',', $namespace);

            $theme = Qwin::run('-ini')->getConfig('interface.theme');

            $jQueryValidateCode = Qwin::run('-arr')->jsonEncode($this->_meta->getJQueryValidateCode($relatedField));
            $this->_view = array(
                'class' => 'Trex_View',
                'element' => array(
                    array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/management/add-namespace.php'),
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
            mkdir($this->_path . '/' . $_POST['namespace']);

            $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
            return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    public function actionDelete()
    {
        $name = $this->_request->g('namespace_value');
        $path = $this->_path . '/' . $name;
        
        // 不存在的目录
        if(!is_dir($path))
        {
            return $this->setRedirectView($this->_lang->t('MSG_NAMESAPCE_NOT_EXISTS'));
        }

        // 目录不为空
        $file = scandir($path);
        if(array('.', '..') != $file)
        {
            return $this->setRedirectView($this->_lang->t('MSG_NAMESPACE_NOT_EMPTY'));
        }

        // 删除目录,跳转回列表页
        rmdir($path);
        $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
        return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }

    /**
     * 在列表操作下,为操作域设置按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertListOperation($value, $name, $data, $copyData)
    {
        return Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('controller' => 'Module', 'action' => 'Index', 'namespace_value' => $copyData['namespace'])), $this->_lang->t('LBL_ACTION_VIEW_MODULE'), 'ui-icon-lightbulb')
            . Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('controller' => 'Module', 'action' => 'Add', 'namespace_value' => $copyData['namespace'])), $this->_lang->t('LBL_ACTION_ADD_MODULE'), 'ui-icon-plus')
            . Qwin_Helper_Html::jQueryButton('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . $this->_url->createUrl($this->_set, array('action' => 'Delete', 'namespace_value' => $copyData['namespace'])) . '\';}', $this->_lang->t('LBL_ACTION_DELETE'), 'ui-icon-closethick');
    }
}
