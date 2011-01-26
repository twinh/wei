<?php
/**
 * Config
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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-12-07 17:31:03
 */

class Common_Config_Controller_Config extends Common_ActionController
{
    // 根据配置分组生成表单
    public function actionRender()
    {
        $request = $this->request;
        $metaHelper = $this->metaHelper;
        
        $groupId = $request->g('groupId');
        
        // 获取当前分组的所有表单项
        $formData = $metaHelper
            ->getQuery($this->_meta)
            ->where('group_id = ?', $groupId)
            ->andWhere('is_enabled = 1')
            ->execute()
            ->toArray();
        if (empty($formData)) {
           return $this->view->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
        }
        
        // 构建域分组
        $groupResult = $metaHelper
            ->getQueryByAsc(array(
                'namespace' => 'Common',
                'module' => 'Config',
                'controller' => 'Group',
            ))
            ->orderBy('order DESC, date_modified DESC')
            ->execute();
        $groupData = array();
        foreach ($groupResult as $row) {
            $groupData[$row['form_name']] = $row['title'];
        }

        // 根据数据构建元数据
        $configMeta = array(
            'field' => array(
                'groupId' => array(
                    'form' => array(
                        '_type' => 'hidden',
                        '_value' => $groupId,
                    ),
                ),
            ),
            'group' => $groupData,
            'page' => array(
                'title' => $this->_meta['page']['title'],
            ),
        );
        $data['groupId'] = $groupId;
        foreach ($formData as $row) {
            $configMeta['field'][$row['form_name']] = array(
                'basic' => array(
                    'title' => $row['form_label'],
                    'group' => $row['group_id'],
                ),
                'form' => array(
                    '_type' => $row['form_type'],
                    '_value' => $row['value'],
                    '_resource' => $row['form_resource'],
                ),
            );
            $data[$row['form_name']] = $row['value'];
        }
        $meta = $metaHelper
            ->getMetadataByAsc(array(
                'namespace' => 'Common',
                'module' => 'Config',
                'controller' => 'Temp',
            ))
            ->parseMetadata($configMeta);
        
        if (empty($_POST)) {
            $this->view->assignList(get_defined_vars());
            $this->view->setProcesser('Common_View_EditForm');
        } else {
            // 保存结果
            $data = $metaHelper->convertOne($_POST, 'db', $meta, $meta, array('view' => false));
            unset($data['groupId']);
            $path = QWIN_ROOT_PATH . '/common/config/global.php';
            $globalConfig = require $path;
            $globalConfig[$groupId] = $data;
            Qwin_Helper_File::writeAsArray($globalConfig, $path);
            
            $url = $this->url->url($this->_asc, array('action' => 'Index'));
            $this->view->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }
    
    public function getMetadataByGroupId($groupId)
    {
        
    }

    public function actionCenter()
    {
        $metaHelper = $this->metaHelper;
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $meta = $this->_meta;

        // 分组的数据
        $data = $metaHelper
            ->getQueryByAsc(array(
                'namespace' => 'Common',
                'module' => 'Config',
                'controller' => 'Group',
            ))
            ->where('is_enabled = 1')
            ->orderBy('order DESC, date_modified DESC')
            ->execute()
            ->toArray();

        $this->view->assignList(get_defined_vars());
    }
}
