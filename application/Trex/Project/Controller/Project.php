<?php
/**
 * Project
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-6-12 11:25:10 utf-8 中文
 * @since     2010-6-12 11:25:10 utf-8 中文
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
