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
 * @package     Common
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-13 10:17:58
 */

class Common_Member_Controller_Member extends Common_ActionController
{
    /**
     * 编辑密码
     * @return object 实例化编辑操作
     * @todo 重新登陆
     */
    public function actionEditPassword()
    {
        if('guest' == $this->request->get('id') || 'guest' == $this->request->post('id'))
        {
            return $this->view->setRedirectView($this->_lang->t('MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD'));
        }
        $this->_meta = Qwin_Metadata::get('Common_Member_Metadata_Password');

        if(empty($_POST))
        {
            /**
             * @see Common_Service_View $_config
             */
            $config = array(
                'set' => $this->_asc,
                'data' => array(
                    'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_asc),
                    'asAction' => 'editpassword',
                    'meta' => $this->_meta,
                    'isLink' => false,
                    'isView' => false,
                ),
                'view' => array(
                    'class' => 'Common_View_EditForm',
                ),
                'this' => $this,
            );
            return Qwin::call('Common_Service_View')->process($config);
        } else {
            /**
             * @see Common_Service_Update $_config
             */
            $config = array(
                'set' => $this->_asc,
                'data' => array(
                    'db' => $_POST,
                    'meta' => $this->_meta,
                ),
                'view' => array(
                    'url' => urldecode($this->request->post('_page')),
                ),
                'this' => $this,
            );
            return Qwin::call('Common_Service_Update')->process($config);
        }
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = $this->request->get('id');
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
            return $this->view->setRedirectView($this->_lang->t('MSG_NOT_ALLOW_DELETE'));
        }
        parent::actionDelete();
    }

    /**
     * 用户名是否已使用
     */
    public function actionIsUsernameExists()
    {
        $username = Qwin::call('-gpc')->get('usesrname');
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
        $query = $this->_meta->getQueryByAsc($this->_asc);
        $result = $query->where('username = ?', $username)
            ->fetchOne();
        if(false != $result)
        {
            $result = true;
        }
        return $result;
    }

    /*public function onAfterDb($action, $data)
    {
        if('EditPassword' == $action)
        {
            $url = Qwin::call('-url')->url(array('module' => 'Member', 'controller' => 'Log', 'action' => 'Logout'));
            $this->view->setRedirectView('LOGIN', $url)
                    ->loadView()
                    ->display();
            exit();
        }
    }*/
}
