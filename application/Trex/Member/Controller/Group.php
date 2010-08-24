<?php
/**
 * Group
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
 * @version   2010-7-15 14:43:28
 * @since     2010-7-15 14:43:28
 */

class Trex_Member_Controller_Group extends Trex_Controller
{
    public function actionAllocatePermission()
    {
        if(!$_POST)
        {
            
        } else {
            
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

    /*public function convertListOperation($val, $name, $data, $cpoyData)
    {
        //$html = '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_ADD_SUBCATEGORY') .'" href="' . url(array( $this->_set['namespace'],  $this->_set['module'],  $this->_set['controller'], 'AllocatePermission'), array($this->__meta['db']['primaryKey'] => $data[$this->__meta['db']['primaryKey']])) . '"><span class="ui-icon ui-icon-person"></span></a>';
        $html = $this->meta->getOperationLink(
            $this->__meta['db']['primaryKey'],
            $data[$this->__meta['db']['primaryKey']],
            $this->_set
        );
        return $html;
    }*/
}
