<?php
/**
 * User
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
 * @package     Qwin
 * @subpackage  Application
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-13 10:17:58
 */

class User_Controller extends Qwin_Controller
{
    /**
     * 锁定的核心帐号，防止恶意修改
     * @var array
     */
    protected $_lock = array(
        'guest', 'admin', '7641b5b1-c727-6c07-e11f-9cb5b74ddfc9',
    );

    public function indexAction()
    {
        if ($this->isAjax()) {
            $rows = $this->getInt('rows', 1, 500);

            $page = $this->getInt('page', 1);

            $query = $this->query()
                ->addRawOrder(array($this->get('sidx'), $this->get('sord')))
                ->offset(($page - 1) * $rows)
                ->limit($rows);
            $data = $query->fetchArray();
            $total = $query->count();

            return $this->jQGridJson(array(
                'columns' => array('id', 'group_id', 'username', 'email', 'sex', 'date_modified', 'operation'),
                'data' => $data,
                'page' => $page,
                'rows' => $rows,
                'total' => $total,
            ));
        }
    }

    public function addAction()
    {
        if ($this->isPost()) {
            var_dump($_FILES);
        } else {
            $this->view->assign('form', $this->option('form'));
        }
    }

    public function editAction()
    {

    }

    public function loginAction()
    {
        $user = $this->user();

        if (!$this->isPost()) {
            return;
        }

        // check whether user logged in
        if ($user['username'] && 'guest' != $user['username']) {
            return json_encode(array(
                'code' => -1,
                'message' => 'You have logged in!'
            ));
        }

        $username = $this->post('username');
        $password = md5($this->post('password'));

        $user = $this->query()
            ->where('username = ? AND password = ?', array($username, $password))
            ->fetchOne();

        if (!$user) {
            return json_encode(array(
                'code' => -1,
                'message' => 'Username or password error!',
            ));
        }

        //
        $this->user->fromArray($user->toArray());

        return json_encode(array(
            'code' => 0,
            'message' => 'Login success!',
        ));
    }

    public function logoutAction()
    {
        $this->user->logout();
        return json_encode(array(
            'code' => 0,
            'message' => 'Logout success!',
        ));
    }

    public function isLoginAction()
    {
        $user = $this->user();

        if ($user->isLogin()) {
            return json_encode(array(
                'code' => 0,
                'message' => 'You have logged in!',
                'username' => $user['username'],
            ));
        } else {
            return json_encode(array(
                'code' => -1,
                'message' => 'You have not logged in!'
            ));
        }
    }

    /**
     * 编辑密码
     * @return object 实例化编辑操作
     * @todo 重新登陆
     */
    public function actionEditPassword()
    {
        $request = $this->_request;
        $id = $request->request('id');
        if (in_array($id, $this->_lock)) {
            $lang = Qwin::call('-lang');
            return $this->getView()->alert($lang->t('MSG_User_LOCKED'));
        }
        $meta = Qwin_Meta::getInstance()->get('Com_User_PasswordMeta');

        if (!$request->isPost()) {
            return Qwin::call('-widget')->get('View')->execute(array(
                'module'    => $this->_module,
                'meta'      => $meta,
                'id'        => $request->get('id'),
                'asAction'  => 'edit',
                'isView'    => false,
            ));
        } else {
            return Com_Widget::getByModule('com/User', 'editPassword')->execute(array(
                'data'      => $_POST,
            ));
        }
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = $this->_request->get('id');
        $idList = explode(',', $id);

        /**
         * @todo 是否在数据库增加一个字段,作为不允许删除的标志
         */
        $result = array_intersect($idList, $this->_lock);
        if (!empty($result)) {
            $lang = Qwin::call('-lang');
            return $this->getView()->alert($lang->t('MSG_NOT_ALLOW_DELETE'));
        }
        parent::actionDelete();
    }

    /**
     * 用户名是否已使用
     */
    public function actionIsUsernameExists()
    {
        $username = $this->_request->get('usesrname');
        if (true == $this->isUsernameExists($username)) {
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
        if (false != $result) {
            $result = true;
        }
        return $result;
    }
}
