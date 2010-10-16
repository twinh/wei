<?php
/**
 * Company
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
 * @subpackage  Company
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-18 23:15:46
 */

class Trex_Company_Controller_Company extends Trex_ActionController
{
    public function actionMyCompany()
    {
        $_GET['searchField'] = 'member_id';
        $_GET['searchValue'] = $this->member['id'];
        $this->_meta['page']['title'] = 'LBL_MODULE_MY_COMPANY';
        parent::actionIndex();
    }

    public function createCustomLink()
    {
        $html = parent::createCustomLink();
        $html = Qwin_Helper_Html::jQueryLink($this->url->createUrl($this->_set, array('action' => 'MyCompany')), $this->_lang->t('LBL_MODULE_MY_COMPANY'), 'ui-icon-script');
        return $html;
    }

    public function convertEditMemberId($value, $name, $data, $copyData)
    {
        $member = $this->_meta->getDoctrineQuery(array(
            'namespace' => 'Trex',
            'module' => 'Member',
            'controller' => 'Member',
        ), false)->where('id = ?', $value)->fetchOne();
        $this->_meta['field']->set('member_id.form._value2', $member['username']);
        return $value;
    }
}