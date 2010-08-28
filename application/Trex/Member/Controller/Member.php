<?php
/**
 * Member
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
 * @since       2010-05-13 10:17:58
 */

class Trex_Member_Controller_Member extends Trex_ActionController
{
    /**
     * 编辑密码
     * @return object 实例化编辑操作
     * @todo 重新登陆
     */
    public function actionEditPassword()
    {
        if('guest' == $this->_request->g('id') || 'guest' == $this->_request->p('id'))
        {
            return $this->setRedirectView($this->_lang->t('MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD'));
        }
        $this->_meta = Qwin_Metadata_Manager::get('Trex_Member_Metadata_Password');
        parent::actionEdit();
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = $this->_request->g('id');
        $idList = explode(',', $id);

        /**
         * @todo 是否在数据库增加一个字段,作为不允许删除的标志
         */
        $banIdList = array(
            'guest', 'admin'
        );
        $result = array_intersect($idList, $banIdList);
        if(!empty($result))
        {
            return $this->setRedirectView($this->_lang->t('MSG_NOT_ALLOW_DELETE'));
        }
        parent::actionDelete();
    }

    /**
     * 用户名是否已使用
     */
    public function actionIsUsernameExists()
    {
        $username = Qwin::run('-gpc')->g('usesrname');
        if(true == $this->isUsernameExists($username))
        {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    public function isUsernameExists($username)
    {
        $query = $this->_meta->getDoctrineQuery($this->_set);
        $result = $query->where('username = ?', $username)
            ->fetchOne();
        if(false != $result)
        {
            $result = true;
        }
        return $result;
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $html = Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'EditPassword', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_EDIT_PASSWORD'), 'ui-icon-key');
        $html .= parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    /**
     * 修改密码时,将原密码置空
     *
     * @return string 空字符串
     */
    public function convertEditPasswordPassword()
    {
        return '';
    }

    public function convertDbOldPassword($value, $name, $data, $copyData)
    {
        $query = $this->_meta->getDoctrineQuery($this->_set);
        $result = $query->where('id = ?', $data['id'])
            ->fetchOne();
        if(md5($value) != $result['password'])
        {
            $this->setRedirectView($this->_lang->t('MSG_OLD_PASSWORD_NOT_CORRECT'))
                    ->loadView()
                    ->display();
            // TODO 是否合法
            exit();
        }
        return $value;
    }
    
    public function convertDbUsername($val)
    {
        if(true == $this->isUsernameExists($val))
        {
            $this->Qwin_Helper_Js->show($this->t('MSG_USERNAME_EXISTS'));
        }
        return $val;
    }

    public function convertDbCompanyId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }

    public function convertDbDetailRegTime()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }
    
    /*public function onAfterDb($action, $data)
    {
        if('EditPassword' == $action)
        {
            $url = Qwin::run('-url')->createUrl(array('module' => 'Member', 'controller' => 'Log', 'action' => 'Logout'));
            $this->setRedirectView('LOGIN', $url)
                    ->loadView()
                    ->display();
            exit();
        }
    }*/
}
