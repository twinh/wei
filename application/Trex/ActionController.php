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
     * 默认首页
     */
    public function actionIndex()
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
        $customLink = $this->createCustomLink();

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Trex_View_JqGrid',
            'data' => get_defined_vars(),
        );
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
     * 列表页
     */
    public function actionList()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];

        /**
         * 从模型获取数据
         */
        $query = $meta->getDoctrineQuery($this->_set);
        $meta->addSelectToQuery($meta, $query)
             ->addOrderToQuery($meta, $query)
             ->addWhereToQuery($meta, $query)
             ->addLimitToQuery($meta, $query);
        $data = $query->execute()->toArray();
        $count = count($data);
        $totalRecord = $query->count();

        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $data = $meta->convertDataToSingle($data);

        // TODO
        if(method_exists($this, 'dataConverter'))
        {
            $data = $this->dataConverter($data);
        }

        $listField = $meta->getListField($relatedField);
        
        // 允许通过参数改变转换方法
        $convertAs = $this->_request->g('_as');
        null == $convertAs && $convertAs = 'list';
        $data = $this->_meta->convertMultiData($listField, $relatedField, $convertAs, $data, true, $meta['model']);

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Trex_View_JqGridJson',
            'data' => get_defined_vars(),
        );
    }

    /**
     * 显示单独一条数据
     */
    public function actionView()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $id = $this->_request->g($primaryKey);

        /**
         * 从模型获取数据
         */
        $query = $meta->getDoctrineQuery($this->_set);
        $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();
        
        /**
         * 记录不存在,加载错误视图
         */
        if(false == $result)
        {
            return $this->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
        }

        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $groupList = $relatedField->getViewGroupList();
        $data = $result->toArray();
        $data = $meta->convertDataToSingle($data);
        $data = $meta->convertSingleData($relatedField, $relatedField, $this->_set['action'], $data, true, $meta['model']);

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Trex_View_View',
            'data' => get_defined_vars(),
        );
    }

    /**
     * 根据元数据,生成添加视图和处理添加操作
     */
    public function actionAdd()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $id = $this->_request->g($primaryKey);
        $query = $meta->getDoctrineQuery($this->_set);
        $relatedField = $meta->connectMetadata($this->_meta);
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');

        if(empty($_POST))
        {
            /**
             * 三种模式　
             * 1.复制,根据主键从模型获取初始值
             * 2.从url获取值
             * 3. 获取模型默认值
             */
            if(null != $id)
            {
                $this->_result = $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();
                if(false == $result)
                {
                    return $this->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
                }
                $data = $result->toArray();
            } else {
                /**
                 * 从配置元数据中取出表单初始值,再从url地址参数取出初始值,覆盖原值
                 */
                $data = $meta->field->getSecondLevelValue(array('form', '_value'));
                $data = $meta->getUrlData($data);
            }
            unset($data[$primaryKey]);

            /**
             * 处理数据
             */
            $data = $meta->convertDataToSingle($data);
            $data = $meta->convertSingleData($relatedField, $relatedField, $this->_set['action'], $data);
            $relatedField->order();
            $groupList = $relatedField->getAddGroupList();

            /**
             * 设置视图
             */
            return $this->_view = array(
                'class' => 'Trex_View_Form',
                'data' => get_defined_vars(),
            );
        } else {
            /**
             * 设置行为为入库,连接元数据
             */
            $this->setAction('db');
            $relatedField = $meta->connectMetadata($meta);
            $addDbField = $relatedField->getAttrList('isDbField');

            /**
             * 转换,验证和还原
             */
            $data = $this->_meta->convertSingleData($relatedField, $relatedField, 'db', $_POST);
            $validateResult = $meta->validateArray($relatedField, $data + $_POST, $this);
            if(true !== $validateResult)
            {
                $message = $this->_lang->t('MSG_ERROR_FIELD')
                    . $this->_lang->t($relatedField[$validateResult->field]['basic']['title'])
                    . '<br />'
                    . $this->_lang->t('MSG_ERROR_MSG')
                    . $meta->format($this->_lang->t($validateResult->message), $validateResult->param);
                return $this->setRedirectView($message);
            }
            $data = $meta->restoreData($relatedField, $relatedField, $data);
            $data = $meta->setForeignKeyData($meta['model'], $data);

            /**
             * 保存关联模型的数据
             */
            $meta->saveRelatedDbData($meta, $data, $query);

            /**
             * 入库
             */
            $ini = Qwin::run('-ini');
            $modelName = $ini->getClassName('Model', $this->_set);
            $this->_result = new $modelName;
            $this->_result->fromArray($data);
            $this->_result->save();

            /**
             * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
             */
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
            return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    /**
     * 编辑记录
     */
    public function actionEdit()
    {
        /**
         * 初始化常用的变量
         */
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $id = $this->_request->g($primaryKey);
        $this->_query = $query = $meta->getDoctrineQuery($this->_set);
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');
        
        if(null == $this->_request->p($meta['db']['primaryKey']))
        {
            /**
             * 从模型获取数据,如果记录不存在,加载错误视图
             */
            $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();
            if(false == $result)
            {
                return $this->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
            }

            /**
             * 处理数据
             */
            $relatedField = $meta->connectMetadata($this->_meta);
            $relatedField->order();
            // TODO
            $this->relatedField = $relatedField;
            $groupList = $relatedField->getEditGroupList();
            $data = $result->toArray();
            $data = $meta->convertDataToSingle($data);
            $data = $meta->convertSingleData($relatedField, $relatedField, $this->_set['action'], $data);

            /**
             * 设置视图
             */
            $this->_view = array(
                'class' => 'Trex_View_Form',
                'data' => get_defined_vars(),
            );
        } else {
            /**
             * 检查记录是否存在
             */
            $id = $this->_request->p($meta['db']['primaryKey']);
            $this->_result = $result = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
            if(false == $result)
            {
                return $this->setRedirectView($this->_lang->t('MSG_NO_RECORD'));
            }

            /**
             * 设置行为为入库,连接元数据
             */
            $this->setAction('db');
            $relatedField = $meta->connectMetadata($meta);
            $editDbField = $relatedField->getAttrList('isDbField', 'isReadonly');

            /**
             * 转换,验证和还原
             */
            $data = $meta->convertSingleData($relatedField, $relatedField, 'db', $_POST);
            $validateResult = $meta->validateArray($relatedField, $data + $_POST, $this);
            // TODO 转变为一个方法
            if(true !== $validateResult)
            {
                $message = $this->_lang->t('MSG_ERROR_FIELD')
                    . $this->_lang->t($relatedField[$validateResult->field]['basic']['title'])
                    . '<br />'
                    . $this->_lang->t('MSG_ERROR_MSG')
                    . $meta->format($this->_lang->t($validateResult->message), $validateResult->param);
                return $this->setRedirectView($message);
            }
            $data = $meta->restoreData($editDbField, $relatedField, $data);

            /**
             * 保存关联模型的数据
             */
            $meta->saveRelatedDbData($meta, $data, $result);

            /**
             * 入库
             * @todo 设置 null 值
             */
            $result->fromArray($data);
            $result->save();

            /**
             * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
             */
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
            return $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
        }
    }

    /**
     * 删除记录
     */
    public function actionDelete()
    {
        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('Qwin_Request');
        $meta = $this->_meta;

        $id = $this->_request->g($meta['db']['primaryKey']);
        $id = explode(',', $id);

        $query = $meta->getDoctrineQuery($this->_set);

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        $object = $query
            //->select($modelName . '.' . $meta['db']['primaryKey'])
            ->whereIn($alias . $meta['db']['primaryKey'], $id)
            ->execute();

        // TODO $object->delete();
        // TODO 统计删除数
        // TODO 删除数据关联的模块
        foreach($object as $key => $value)
        {
            foreach($meta['model'] as $model)
            {
                // 不删除视图模块的记录
                if(isset($object[$key][$model['alias']]) && 'db' == $model['type'])
                {
                    $object[$key][$model['alias']]->delete();
                }
            }
            $value->delete();
        }

        /**
         * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
         */
        $this->executeOnFunction('afterDb', 'Delete', array());
        $url = urldecode($this->_request->p('_page'));
        '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));
        $this->setRedirectView($this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
    }

    
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
        $primaryKey = $this->_meta['db']['primaryKey'];
        $data  = Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_EDIT'), 'ui-icon-tag')
              . Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'View', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_VIEW'), 'ui-icon-lightbulb')
              . Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'Add', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_COPY'), 'ui-icon-transferthick-e-w')
              . Qwin_Helper_Html::jQueryButton('javascript:if(confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE)){window.location=\'' . $this->_url->createUrl($this->_set, array('action' => 'Delete', $primaryKey => $copyData[$primaryKey])) . '\';}', $this->_lang->t('LBL_ACTION_DELETE'), 'ui-icon-closethick');
        return $data;
    }

    /*public function convertPopupOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $data  = Qwin_Helper_Html::jQueryButton($this->_url->createUrl($this->_set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])), $this->_lang->t('LBL_ACTION_EDIT'), 'ui-icon-check');
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
