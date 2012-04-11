<?php
/**
 * Config
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
 * @package     QWIN_PATH
 * @subpackage
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-12-07 17:31:03
 */

class Config_Controller extends Qwin_Controller
{
    // 根据配置分组生成表单
    public function actionRender()
    {
        $request = $this->request;

        $groupId = $request->get('groupId');

        // 获取当前分组的所有表单项
        $formData = $this->_meta
            ->getQuery()
            ->where('group_id = ?', $groupId)
            ->andWhere('is_enabled = 1')
            ->execute()
            ->toArray();
        if (empty($formData)) {
           return $this->view->alert($this->_lang->t('MSG_NO_RECORD'));
        }

        // 构建域分组
        $groupResult = Com_Meta::getQueryByAsc(array(
                'package' => 'Common',
                'module' => 'Config',
                'controller' => 'Group',
            ))
            ->orderBy('order DESC, updated_at DESC')
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
        $meta = Com_Meta::getByAsc(array(
                'package' => 'Common',
                'module' => 'Config',
                'controller' => 'Temp',
            ))
            ->merge($configMeta);

        if (empty($_POST)) {
            $primaryKey = $groupId;
            $view = Qwin::call('Com_View_Edit');
            $view->assign(get_defined_vars());
        } else {
            // 保存结果
            $data = $meta->sanitise($_POST, 'db', array('view' => false));
            unset($data['groupId']);
            $path = Qwin::config('root') . 'config/global.php';
            $globalConfig = require $path;
            $globalConfig[$groupId] = $data;
            Qwin_Util_File::writeArray($path, $globalConfig);

            $url = $this->url->url($this->_asc, array('action' => 'Index'));
            $this->view->success(Qwin::call('-lang')->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    public function getMetaByGroupId($groupId)
    {

    }

    public function actionCenter()
    {
        $url = Qwin::call('-url');
        $lang = Qwin::call('-lang');
        $meta = Com_Meta::getByModule('ide/config');

        // 分组的数据
        $data = Com_Meta::getQueryByModule('Ide/Config/Group')
            ->where('is_enabled = 1')
            ->orderBy('order DESC, updated_at DESC')
            ->execute()
            ->toArray();

        $this->_View->assign(get_defined_vars());
    }
}
