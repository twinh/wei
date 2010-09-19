<?php
/**
 * Task
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
 * @subpackage  Task
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-19 09:34:20
 */

class Trex_Task_Controller_Task extends Trex_ActionController
{
    /**
     * 查看分配给我的任务
     */
    public function actionListAssignToMe()
    {
        $_GET['searchField'] = 'assign_to';
        $_GET['searchValue'] = $this->_member['id'];
        $this->_meta['page']['title'] = 'LBL_MODULE_TASK_ASSIGN_TO_ME';
        parent::actionIndex();
    }

    /**
     * 查看我分配的任务
     */
    public function actionListAssignByMe()
    {
        $_GET['searchField'] = 'assign_by';
        $_GET['searchValue'] = $this->_member['id'];
        $this->_meta['page']['title'] = 'LBL_MODULE_TASK_ASSIGN_BY_ME';
        parent::actionIndex();
    }

    public function actionListMyNewTask()
    {
        //..
    }

    /**
     * 添加任务
     */
    public function actionAdd()
    {
        $this->_meta['field']->unlink(array(
            'assign_to', 'assign_by',
        ));
        parent::actionAdd();
    }

    /**
     * 分配任务
     */
    public function actionAssignTo()
    {
        $this->_meta['field']->unlink(array(
            'name', 'description',
        ));
        parent::actionEdit();
    }

    public function actionResolve()
    {
        $primaryKey = $this->_meta['db']['primaryKey'];

        // TODO
        $_POST[$primaryKey] = $this->_request->g($primaryKey);
        $_POST['status'] = 5;

        // 删除无关键名
        $exceptKey = array($primaryKey, 'status', 'date_modified', 'modified_by');
        $this->_meta['field']->unlinkExcept($exceptKey);
        $this->_meta['model']->unlinkExcept($exceptKey);
        
        parent::actionEdit();
    }

    /**
     * 编辑任务
     */
    public function actionEdit()
    {
        $this->_meta['field']->unlink(array(
            'assign_to', 'assign_by',
        ));
        $this->_meta['model']->unlink(array(
            'assign_to', 'assign_by',
        ));
        parent::actionEdit();
    }

    public function createCustomLink()
    {
        $html = parent::createCustomLink();
        $html = Qwin_Helper_Html::jQueryLink($this->_url->createUrl($this->_set, array('action' => 'ListAssignToMe')), $this->_lang->t('LBL_MODULE_TASK_ASSIGN_TO_ME'), 'ui-icon-script')
              . Qwin_Helper_Html::jQueryLink($this->_url->createUrl($this->_set, array('action' => 'ListAssignByMe')), $this->_lang->t('LBL_MODULE_TASK_ASSIGN_BY_ME'), 'ui-icon-script');
        return $html;
    }

    public function convertDbAssignBy($value, $name, $data, $copyData)
    {
        return $this->_member['id'];
    }

    public function convertDbStatusId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    public function convertDbStatus($value, $name, $data, $copyData)
    {
        $action = $this->getLastAction();
        switch($action)
        {
            // 操作为新建任务时,设置状态为新建
            case 'Add':
                $value = 1;
                break;
            // 操作为分配时,设置状态为分配
            case 'AssignTo':
                $value = 3;
                break;
            default:
                break;
        }
        return $value;
    }

    public function convertViewStatus($value, $name, $data, $copyData)
    {
        if(3 == $copyData['status'])
        {
            $primaryKey = $this->_meta['db']['primaryKey'];
            $value = Qwin::run('Project_Helper_CommonClass')->convert($value, 'task-status')
                   . ' '
                   . Qwin_Helper_Html::jQueryLink($this->_url->createUrl($this->_set, array('action' => 'Resolve', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_RESOLVED'), 'ui-icon-check', 'ui-view-button');
        }
        return $value;
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $html  = Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'AssignTo', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_ASSIGN_TO'), 'ui-icon-person')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function onAfterData()
    {
        
    }

    public function isSaveStatusData($data, $query)
    {
        if(isset($data['status']) && isset($query->status) && $data['status'] == $query['status'])
        {
            return false;
        }
        return true;
    }
}