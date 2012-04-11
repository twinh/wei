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
    public function indexAction()
    {
        if ($this->isAjax()) {
            $rows = $this->getInt('rows', 1, 500);

            $page = $this->getInt('page', 1);

            $query = $this->query()
                ->select('u.*, g.name')
                ->from('User_Record u')
                ->leftJoin('u.group g')
                ->addRawOrder(array($this->get('sidx'), $this->get('sord')))
                ->offset(($page - 1) * $rows)
                ->limit($rows);

            $data = $query->fetchArray();
            foreach ($data as &$row) {
                if ($row['group']) {
                    $row['group_id'] = $row['group']['name'];
                }
            }

            $total = $query->count();

            return $this->jqGridJson(array(
                'columns' => array('id', 'group_id', 'username', 'email', 'sex', 'created_by', 'created_at', 'updated_by', 'updated_at', 'operation'),
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
            $user = $this->record();

            $user->fromArray($this->post->toArray());

            $user->save();

            return json_encode(array(
                'code' => 0,
                'message' => 'User added successfully'
            ));
        } else {
            // 分组选项
            $options = $this->record('group')->getParentOptions();
            $options = json_encode($options);

            $this->view->assign(get_defined_vars());
        }
    }

    public function editAction()
    {
        $id = $this->get('id');

        $user = $this->query()
            ->where('id = ?', $id)
            ->fetchOne();

        if ($this->isPost()) {
            if (!$user) {
                return json_encode(array(
                    'code' => -1,
                    'message' => 'User was not found.'
                ));
            }

            $user->fromArray($this->post->toArray());

            $user->save();

            return json_encode(array(
                'code' => 0,
                'message' => 'User edited successfully'
            ));
        } else {
            if (!$user) {
                return $this->error('User was not found.');
            }

            // 分组选项
            $options = $this->record('group')->getParentOptions();
            $options = json_encode($options);

            $data = json_encode($user->toArray());

            $this->view->assign(get_defined_vars());
        }
    }

    public function deleteAction()
    {
        $id = $this->get('id');

        $user = $this->query()
            ->where('id = ?', $id)
            ->fetchOne();

        if (!$user) {
            $this->error('User is not exists');
        } else {
            $user->delete();
            return json_encode(array(
                'code' => 0,
                'message' => 'User deleted successfully',
            ));
        }
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
            ->from('User_Record u')
            ->leftJoin('u.group g')
            ->leftJoin('g.acl a')
            ->where('username = ? AND password = ?', array($username, $password))
            ->fetchOne();

        if (!$user) {
            return json_encode(array(
                'code' => -1,
                'message' => 'Username or password error!',
            ));
        }

        $data = $user->toArray();
        $data['acl'] = $user['group']['acl']['resources'];

        $this->user->fromArray($data);

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
