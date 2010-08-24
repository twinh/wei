<?php
/**
 * CustomerFeedback
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
 * @version   2010-7-18 13:57:13
 * @since     2010-7-18 13:57:13
 */

class Trex_CustomerFeedback_Controller_CustomerFeedback extends Qwin_Trex_Controller
{
    /**
     * 列表
     */
    public function actionDefault()
    {
        return Qwin::run('Qwin_Trex_Action_List');
    }

    /**
     * 添加
     */
    public function actionAdd()
    {
        return Qwin::run('Qwin_Trex_Action_Add');
    }

    /**
     * 编辑
     */
    public function actionEdit()
    {
        return Qwin::run('Qwin_Trex_Action_Edit');
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
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

    public function actionChangeStatus()
    {
        $gpc = Qwin::run('-gpc');
        $meta = $this->__meta;
        $query = $this->meta->getQuery($this->_set);

        // 根据url参数中的值,获取对应的数据库资料
        $id = $gpc->g($meta['db']['primaryKey']);
        $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
        if(false == $query)
        {
            $self->Qwin_Helper_Js->show($self->t('MSG_NO_RECORD'));
        }
        if('2001002' == $query['is_processed'])
        {
            $query['is_processed'] = '2001001';
        } else {
            $query['is_processed'] = '2001002';
        }
        $query->save();
        Qwin::run('Qwin_Helper_Js')->show($this->t('MSG_OPERATED_SUCCESS'), url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'])));
    }

    /**
     * 查看
     */
    public function actionShow()
    {
        return Qwin::run('Qwin_Trex_Action_Show');
    }

    public function convertDbDateCreated()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    public function convertDbDateModified()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    public function convertListOperation($val, $name, $data, $cpoyData)
    {
        $html = '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_CHANGE_STATUS') .'" href="' . url(array($this->_set['namespace'], $this->_set['module'], $this->_set['controller'], 'ChangeStatus'), array($this->__meta['db']['primaryKey'] => $data[$this->__meta['db']['primaryKey']])) . '"><span class="ui-icon ui-icon-check"></span></a>';
        $html .= $this->meta->getOperationLink($this->__meta['db']['primaryKey'], $data[$this->__meta['db']['primaryKey']], $this->_set);
        return $html;
    }

    public function convertDbId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
    }


    public function convertListInterestedProduct($val)
    {
        if(!isset($this->_productCategory))
        {
            $this->_productCategory = array();
            $data = $this
                ->meta
                ->getQuery(array(
                    'namespace' => 'Default',
                    'module' => 'Category',
                    'controller' => 'Category',
                ))
                ->select('id, name')
                ->where('parent_id = ?', 'product')
                ->execute()
                ->toArray();
            foreach($data as $row)
            {
                $this->_productCategory[$row['id']] = $row['name'];
            }
        }
        $productArr = explode('|', $val);
        $productTmp = array();
        foreach($productArr as $productId)
        {
            if(isset($this->_productCategory[$productId]))
            {
                $productTmp[] = $this->_productCategory[$productId];
            }
        }
        return implode(', ', $productTmp);
    }
}
