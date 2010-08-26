<?php
/**
 * Member
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
 * @version   2010-5-13 10:17:58 utf-8 中文
 * @since     2010-5-13 10:17:58 utf-8 中文
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
        $gpc = Qwin::run('-gpc');
        if('guest' == $gpc->g('id') || 'guest' == $gpc->p('id'))
        {
            Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD'));
        }
        $this->__meta = $this->meta->passwordMetadata();
        $this->__meta = $this->meta->convertLang($this->__meta, $this->lang);
        return Qwin::run('Qwin_Trex_Action_Edit');
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = $_GET['id'];
        $idArr = explode(',', $id);

        /**
         * @todo 是否在数据库增加一个字段,作为不允许删除的标志
         */
        $banIdArr = array(
            'guest', 'admin'
        );
        $result = array_intersect($idArr, $banIdArr);
        if(!empty($result))
        {
            Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD'));
        }
        return Qwin::run('Qwin_Trex_Action_Delete');
    }

    /**
     * 列表的 json 数据
     */
    public function actionJsonList()
    {
        Qwin::load('Qwin_converter_Time');
        return Qwin::run('Qwin_Trex_Action_JsonList');
    }

    /**
     * 查看
     */
    public function actionShow()
    {
        return Qwin::run('Qwin_Trex_Action_Show');
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

    /**
     * 筛选
     */
    /*public function actionFilter()
    {
        return Qwin::run('Qwin_Trex_Action_Filter');
    }*/

    /*public function convertListOperation($val, $name, $data, $cpoyData)
    {
        $html = '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_EDIT_PASSWORD') .'" href="' . url(array( $this->_set['namespace'],  $this->_set['module'],  $this->_set['controller'], 'EditPassword'), array('id' => $data[$this->__meta['db']['primaryKey']])) . '"><span class="ui-icon ui-icon-key"></span></a>';
        $html .= $this->meta->getOperationLink(
            $this->__meta['db']['primaryKey'],
            $data[$this->__meta['db']['primaryKey']],
            $this->_set
        );
        return $html;
    }*/

    public function convertEditPasswordPassword()
    {
        return '';
    }

    public function convertDbOldPassword($val, $name, $data)
    {
        $query = $this->meta->getDoctrineQuery($this->_set);
        $result = $query->where('id = ?', $data['id'])
            ->fetchOne();
        if(md5($val) != $result['password'])
        {
            Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_OLD_PASSWORD_NOT_CORRECT'));
        }
        return $val;
    }
    
    public function convertDbUsername($val)
    {
        if(true == $this->isUsernameExists($val))
        {
            $this->Qwin_Helper_Js->show($this->t('MSG_USERNAME_EXISTS'));
        }
        return $val;
    }
    
    public function convertDbDetailId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }

    public function convertDbCompanyId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }

    public function convertDbDetailRegTime()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }
    
    public function convertDbEmailAddressId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }
}
