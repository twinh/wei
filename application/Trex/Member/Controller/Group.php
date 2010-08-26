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
