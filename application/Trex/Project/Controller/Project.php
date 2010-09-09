<?php
/**
 * Project
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
 * @subpackage  Project
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-06-12 11:25:10
 */

class Trex_Project_Controller_Project extends Trex_ActionController
{
    public function convertDbStateTime()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function convertListEndTime($value, $name, $data, $copyData)
    {
        return substr($value, 0, 10);
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        return Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('controller' => 'Bug', 'action' => 'Add', '_data[project_id]' => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_ADD_BUG'), 'ui-icon-plusthick')
                . Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('controller' => 'Status', 'action' => 'Index', 'searchField' => 'project_id', 'searchValue' => $copyData['id'])), $this->_lang->t('LBL_ACTION_VIEW_STATUS'), 'ui-icon-lightbulb')
                . parent::convertListOperation($value, $name, $data, $copyData);
    }

    public function convertDbStatusId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
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
