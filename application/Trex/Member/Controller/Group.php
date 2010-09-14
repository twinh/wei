<?php
/**
 * Group
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
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-15 14:43:28
 */

class Trex_Member_Controller_Group extends Trex_ActionController
{
    /**
     * 分配权限
     *
     * @return 当前类
     * @todo 预定权限分配模式,提供一键分配
     * @todo 全选,反选
     * @todo 当父分类被选择时,子分类全选
     */
    public function actionAllocatePermission()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $id = $this->_request->g($primaryKey);

        /**
         * 从模型获取数据
         */
        $query = $meta->getDoctrineQuery($this->_set);
        $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();

        /**
         * 记录不存在,加载错误视图
         */
        if(false == $result)
        {
            return $this->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
        }

        if(empty($_POST))
        {
            $permission = unserialize($result['permission']);
            $appStructure = require QWIN_ROOT_PATH . '/cache/php/application-structure.php';
            $theme = Qwin::run('-ini')->getConfig('interface.theme');
            $this->_view = array(
                'class' => 'Trex_View',
                'element' => array(
                    array('content', QWIN_RESOURCE_PATH . '/view/theme/' . $theme . '/element/member-permission.php'),
                ),
                'data' => get_defined_vars(),
            );
        } else {
            $permission = (array)$this->_request->p('permission');
            /**
             * 剔除子项
             */
            foreach($permission as $nameString => $value)
            {
                $tempName = '';
                $nameList = explode('|', $nameString);
                array_pop($nameList);
                foreach($nameList as $name)
                {
                    '' != $tempName && $tempName .= '|';
                    $tempName .= $name;
                    if(isset($permission[$tempName]))
                    {
                        unset($permission[$nameString]);
                        break;
                    }
                }

            }
            $result['permission'] = serialize($permission);
            $result->save();
            $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
            return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    public function convertViewImagePath($value)
    {
        if(file_exists($value))
        {
            return '<img src="' . $value . '" />';
        }
        return $value . '<em>(' . $this->_lang->t('MSG_FILE_NOT_EXISTS') . ')</em>';
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $html = Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'AllocatePermission', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_ALLOCATE_PERMISSION'), 'ui-icon-person');
        $html .= parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }
}
