<?php
/**
 * Metadata
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-28 17:26:04
 */

class Trex_Metadata extends Qwin_Metadata
{
    protected $_set;

    /**
     * 需要进行链接转换的行为
     * @var array
     */
    protected $_linkAction = array('list', 'view');

    /**
     * 元数据助手类,负责一系列的转换工作
     * @var Qwin_Trex_Metadata
     */
    public $metaHelper;

    public function  __construct()
    {
        $this->metaHelper = Qwin::run('Qwin_Trex_Metadata');
    }

    /**
     * 根据类名获取配置(set)
     *
     * @return array 配置
     * @todo 应该允许自定义set
     */
    public function getSetFromClass()
    {
        return Qwin::run('-ini')->getSet();
        if(null != $this->_set)
        {
            return $this->_set;
        }
        
        $name = get_class();
        // 必需是子类的名称才有set结构
        if($name == 'Trex_Metadata') {
            $this->_set = array();
        } else {
            $name = explode('_', $name);
            // 名称不是合法的set结构
            if(4 != count($name) || 'Metadata' != $name[2])
            {
                $this->_set = array();
            } else {
                $this->_set = array(
                    'namespace' => $name[0],
                    'module' => $name[1],
                    'controller' => $name[3],
                );
            }
        }
        return $this->_set;
    }

    /**
     * 设置元数据各个细节,包括域,分组,关联模型等等,每个元数据类应该包含该方法
     */
    public function setMetadata()
    {
        return null;
    }

    /**
     * 设置基本的元数据,包括编号,创建时间,修改时间和操作.
     */
    public function setCommonMetadata()
    {
        return $this
            ->setIdMetadata()
            ->setCreatedData()
            ->setModifiedData()
            ->setOperationMetadata();
    }

    /**
     * 设置编号域的元数据配置,编号是最为常见的域
     *
     * @return obejct 当前对象
     */
    public function setIdMetadata()
    {
        $this->addField(array(
            'id' => array(
                'basic' => array(
                    'order' => -1,
                ),
                'form' => array(
                    //'_type' => 'hidden',
                    '_type' => 'text',
                    '_widgetDetail' => array(
                        array(
                            array('Qwin_Widget_JQuery_CustomValue', 'render'),
                        ),
                    ),
                    'name' => 'id',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                    'isReadonly' => 0,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置创建域,包括创建人和创建时间
     *
     * @return object 当前对象
     */
    public function setCreatedData()
    {
        $this->addField(array(
            'created_by' => array(
                'basic' => array(
                    'order' => 1020,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'db' => array(
                    'type' => 'date',
                ),
                'attr' => array(
                    'isList' => 0,
                    'isReadonly' => 1,
                ),
            ),
            'date_created' => array(
                'basic' => array(
                    'order' => 1060,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'db' => array(
                    'type' => 'date',
                ),
                'attr' => array(
                    'isReadonly' => 1,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置修改域,包括修改人和修改时间
     *
     * @return object 当前对象
     */
    public function setModifiedData()
    {
        $this->addField(array(
            'modified_by' => array(
                'basic' => array(
                    'order' => 1040,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isList' => 0,
                ),
            ),
            'date_modified' => array(
                'basic' => array(
                    'order' => 1080,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置操作域的元数据配置,操作域主要用于列表
     *
     * @return obejct 当前对象
     */
    public function setOperationMetadata()
    {
        $this->addField(array(
            'operation' => array(
                'basic' => array(
                    'order' => 1100,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isDbField' => 0,
                    'isDbQuery' => 0,
                    'isView' => 0,
                ),
            ),
        ));
        return $this;
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
        return date('Y-m-d', strtotime($value));
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
        return date('Y-m-d', strtotime($value));
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
        $primaryKey = $this->db['primaryKey'];
        $url = Qwin::run('-url');
        $lang = Qwin::run('-lang');
        $set = $this->getSetFromClass();
        $data  = Qwin_Helper_Html::jQueryButton($url->createUrl($set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])), $lang->t('LBL_ACTION_EDIT'), 'ui-icon-tag')
              . Qwin_Helper_Html::jQueryButton($url->createUrl($set, array('action' => 'View', $primaryKey => $copyData[$primaryKey])), $lang->t('LBL_ACTION_VIEW'), 'ui-icon-lightbulb')
              . Qwin_Helper_Html::jQueryButton($url->createUrl($set, array('action' => 'Add', $primaryKey => $copyData[$primaryKey])), $lang->t('LBL_ACTION_COPY'), 'ui-icon-transferthick-e-w')
              . Qwin_Helper_Html::jQueryButton('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . $url->createUrl($set, array('action' => 'Delete', $primaryKey => $copyData[$primaryKey])) . '\';}', $lang->t('LBL_ACTION_DELETE'), 'ui-icon-closethick');
        return $data;
    }

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
        $query = $this->metaHelper->getQuery($this);
        $result = $query
            ->select($this->db['primaryKey'] . ', order')
            ->orderBy('order DESC')
            ->fetchOne();
        if(false != $result)
        {
            return $result['order'] + 5;
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
        if(null == $value)
        {
            $value = Qwin_Helper_Util::getUuid();
        }
        return $value;
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
        $member = Qwin::run('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function convertDbModifiedBy($value, $name, $data, $copyData)
    {
        $member = Qwin::run('Qwin_Session')->get('member');
        return $member['id'];
    }

    public function setIsLink($value, $name, $data, $dataCopy, $action)
    {
        if (in_array($action, $this->_linkAction)) {
            $set = $this->getSetFromClass();
            !isset($this->url) && $this->url = Qwin::run('-url');
            $value = '<a href="' . $this->url->createUrl($set, array('action' => 'Index', 'searchField' => $name, 'searchValue' => $dataCopy[$name])) . '">' . $value . '</a>';
        }
        return $value;
    }
}
