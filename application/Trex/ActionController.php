<?php
/**
 * ActionController
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @subpackage  ActionController
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-26 15:31:26
 */

class Trex_ActionController extends Trex_Controller
{
    /**
     * 控制器默认首页,Trex命名空间的默认首页是数据列表
     *
     * @return array 服务处理结果
     */
    public function actionIndex()
    {
        /**
         * @see Trex_Service_Index $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'list' => $this->_meta->getUrlListField(),
            ),
            'trigger' => array(
                'beforeViewLoad' => array(
                    array($this, 'createCustomLink'),
                ),
            ),
        );
        return Qwin::run('Trex_Service_Index')->process($config);
    }

    public function actionPopup()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];

        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $listField = $meta->getListField($relatedField);

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Trex_View_Popup',
            'data' => get_defined_vars(),
        );
    }

    /**
     * 查看多条记录,以列表的形式展示
     *
     * @return array 服务处理结果
     */
    public function actionList()
    {
        /**
         * @see Trex_Service_List $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'list' => $this->metaHelper->getUrlListField(),
                'order' => $this->metaHelper->getUrlOrder(),
                'where' => $this->metaHelper->getUrlWhere(),
                'offset'=> $this->metaHelper->getUrlOffset(),
                'limit' => $this->metaHelper->getUrlLimit(),
                'converAsAction'=> $this->request->g('_as'),
            ),
            'trigger' => array(
                'dataConverter' => array(
                    array($this, 'dataConverter'),
                ),
            ),
        );
        return Qwin::run('Trex_Service_List')->process($config);
    }

    /**
     * 查看一条记录
     *
     * @return array 服务处理结果
     */
    public function actionView()
    {
        /**
         * @see Trex_Service_View $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
            ),
        );
        return Qwin::run('Trex_Service_View')->process($config);
    }

    /**
     * 添加记录
     *
     * @return array 服务处理结果
     */
    public function actionAdd()
    {
        if(empty($_POST))
        {
            /**
             * @see Trex_Service_View $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
                ),
                'view' => array(
                    'class' => 'Trex_View_Form',
                ),
            );
            return Qwin::run('Trex_Service_Form')->process($config);
        } else {
            /**
             * @see Trex_Service_Insert $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'db' => $_POST,
                ),
                'trigger' => array(
                    'afterDb' => array($this, 'onAfterDb'),
                ),
            );
            return Qwin::run('Trex_Service_Insert')->process($config);
        }
    }

    /**
     * 编辑记录
     *
     * @return array 服务处理结果
     */
    public function actionEdit()
    {
        if(empty($_POST))
        {
            /**
             * @see Trex_Service_View $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
                    'convertAsAction' => 'edit',
                    'isLink' => false,
                ),
                'view' => array(
                    'class' => 'Trex_View_Form',
                ),
            );
            return Qwin::run('Trex_Service_View')->process($config);
        } else {
            /**
             * @see Trex_Service_Update $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'db' => $_POST,
                ),
                'trigger' => array(
                    'afterDb' => array($this, 'onAfterDb'),
                ),
            );
            return Qwin::run('Trex_Service_Update')->process($config);
        }
    }

    /**
     * 删除记录
     *
     * @return array 服务处理结果
     */
    public function actionDelete()
    {
        /**
         * @see Trex_Service_Delete $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
            ),
            'trigger' => array(
                'afterDb' => array($this, 'onAfterDb'),
            ),
        );
        return Qwin::run('Trex_Service_Delete')->process($config);
    }

    /**
     * 创建在列表页显示的自定义链接
     * @return null
     * @todo 标准化
     */
    public function createCustomLink()
    {
        return null;
    }

    /**
     * 在列表操作下,设置记录添加时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function convertListDateCreated($value, $name, $data, $copyData)
    {
        return substr($value, 0, 10);
    }

    /**
     * 在列表操作下,设置记录修改时间的格式为年月日
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string Y-m-d格式的日期
     */
    public function convertListDateModified($value, $name, $data, $copyData)
    {
        return substr($value, 0, 10);
    }

    /**
     * 在列表操作下,为操作域设置按钮
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     * @todo 简化,重利用
     */
    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->metaHelper->getPrimaryKeyName($this->_set);
        $data  = Qwin_Helper_Html::jQueryButton($this->url->createUrl($this->_set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_EDIT'), 'ui-icon-tag')
              . Qwin_Helper_Html::jQueryButton($this->url->createUrl($this->_set, array('action' => 'View', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_VIEW'), 'ui-icon-lightbulb')
              . Qwin_Helper_Html::jQueryButton($this->url->createUrl($this->_set, array('action' => 'Add', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_COPY'), 'ui-icon-transferthick-e-w')
              . Qwin_Helper_Html::jQueryButton('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . $this->url->createUrl($this->_set, array('action' => 'Delete', $primaryKey => $copyData[$primaryKey])) . '\';}', $this->_lang->t('LBL_ACTION_DELETE'), 'ui-icon-closethick');
        return $data;
    }

    /*public function convertPopupOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $data  = Qwin_Helper_Html::jQueryButton($this->url->createUrl($this->_set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_EDIT'), 'ui-icon-check');
        return $data;
    }*/

    /**
     * 在列表操作下,初始化排序域的值,依次按5递增
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return int 当前域的新值
     */
    public function convertAddOrder($value, $name, $data, $copyData)
    {
        $query = $this->_meta->getDoctrineQuery($this->_set);
        $result = $query
            ->select($this->_meta['db']['primaryKey'] . ', order')
            ->orderBy('order DESC')
            ->fetchOne();
        if(false != $result)
        {
            return $result['order'] + 20;
        }
        return 0;
    }

    /**
     * 在入库操作下,转换编号
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    /**
     * 在入库操作下,转换详细信息的编号
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbDetailId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    /**
     * 在入库操作下,转换创建时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbDateCreated($value, $name, $data, $copyData)
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 在入库操作下,转换修改时间
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbDateModified()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 在入库操作下,转换分类的值
     *
     * @param mixed 当前域的值
     * @param string 当前域的名称
     * @param array $data 已转换过的当前记录的值
     * @param array $cpoyData 未转换过的当前记录的值
     * @return string 当前域的新值
     */
    public function convertDbCategoryId($value)
    {
        '0' == $value && $value = null;
        return $value;
    }

    public function convertDbCreatedBy($value, $name, $data, $copyData)
    {
        return $this->_member['id'];
    }

    public function convertDbModifiedBy($value, $name, $data, $copyData)
    {
        return $this->_member['id'];
    }

    public function convertDbContactId($value, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($value);
    }

    public function convertDbContactCreatedBy($value, $name, $data, $copyData)
    {
        return $this->_member['id'];
    }

    public function convertDbContactModifiedBy($value, $name, $data, $copyData)
    {
        return $this->_member['id'];
    }

    public function convertDbContactDateCreated()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    public function convertDbContactDateModified()
    {
        return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }
}
