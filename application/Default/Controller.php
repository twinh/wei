<?php
/**
 * Abstract
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
 * @version   2010-7-28 15:19:18
 * @since     2010-7-28 15:19:18
 */

class Default_Controller extends Qwin_Trex_Controller
{
    /**
     * 初始化各类和数据
     */
    public function __construct()
    {
        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('Qwin_Request');
        $this->_url = Qwin::run('Qwin_Url');
        $set = $this->_set = $ini->getSet();
        $this->_config = $ini->getConfig();
       
        /**
         * 访问控制
         */
        $this->_isAllowVisited();

        /**
         * 加载语言,同时将该命名空间下的通用模块语言类加入到当前模块的语言类下
         */
        $languageName = $this->getLanguage();
        $commonLanguageName = $set['namespace'] . '_Common_Language_' . $languageName;
        $languageName = $set['namespace'] . '_' . $set['module'] . '_Language_' . $languageName;
        Qwin::load('Default_Language');
        $this->_lang = Qwin::run($languageName);
        if(null == $this->_lang)
        {
            $languageName = 'Default_Language';
            $this->_lang = Qwin::run($languageName);
        }
        $this->_commonLang = Qwin::run($commonLanguageName);
        if(null != $this->_commonLang)
        {
            $this->_lang->merge($this->_commonLang);
        }
        Qwin::addMap('-lang', $languageName);

        /**
         * 加载元数据
         */
        $metadataName = $ini->getClassName('Metadata', $set);
        Qwin::load('Default_Metadata');
        Qwin::load($metadataName);
        $this->_meta = Qwin_Metadata_Manager::get($metadataName);
        if(null == $this->_meta)
        {
            $metadataName = 'Default_Metadata';
            $this->_meta = Qwin::run($metadataName);
        }
        Qwin::addMap('-meta', $metadataName);

        /**
         * 加载模型
         */
        $modelName = $ini->getClassName('Model', $set);
        $this->_model = Qwin::run($modelName);
        if(null == $this->_model)
        {
            $modelName = 'Qwin_Trex_Model';
            $this->_model = Qwin::run($modelName);
        }
        Qwin::addMap('-model', $modelName);

        /**
         * 加载视图父类
         */
        Qwin::load('Default_View');
    }

    /**
     * 是否有权限浏览该页面
     *
     * @return boolen
     */
    private function _isAllowVisited()
    {
        return true;
    }

    /**
     * 默认首页
     */
    public function actionDefault()
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
        $listField = $relatedField->getAttrList('isList');

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Default_Common_View_JqGrid',
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
        
        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $data = $meta->convertDataToSingle($data);
        $data = $this->_meta->convertMultiData($relatedField, 'list', $data);
        $listField = $relatedField->getAttrList('isList');

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Default_Common_View_JqGridJson',
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
            return $this->setView('alert', 'MSG_NO_RECORD');
        }

        /**
         * 处理数据
         */
        $relatedField = $meta->connectMetadata($this->_meta);
        $relatedField->order();
        $groupList = $relatedField->getViewGroupList();
        $data = $result->toArray();
        $data = $meta->convertDataToSingle($data);

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Default_Common_View_View',
            'data' => get_defined_vars(),
        );
    }

    /**
     * 根据元数据,生成添加视图和处理添加操作
     *
     * @return array 视图配置数组
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
                $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();
                if(false == $result)
                {
                    return $this->setView('alert', $this->_lang->t('MSG_NO_RECORD'));
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
            $data = $meta->convertSingleData($relatedField, $this->_set['action'], $data);
            $relatedField->order();
            $groupList = $relatedField->getAddGroupList();

            /**
             * 设置视图
             */
            $this->_view = array(
                'class' => 'Default_Common_View_Form',
                'data' => get_defined_vars(),
            );
        } else {
            /**
             * 设置行为为入库,连接元数据
             */
            $this->setAction('db');
            $relatedField = $meta->connectMetadata($meta);
            $addDbField = $relatedField->getAddDbField();

            /**
             * 转换,验证和还原
             */
            $data = $this->_meta->convertSingleData($addDbField, 'db', $_POST);
            $this->_meta->validateData($addDbField, $data);
            $data = $meta->restoreData($addDbField, $data);
            $data = $meta->setForeignKeyData($meta['model'], $data);

            /**
             * 入库
             */
            $ini = Qwin::run('-ini');
            $modelName = $ini->getClassName('Model', $this->_set);
            $query = new $modelName;
            $query->fromArray($data);
            $query->save();

            /**
             * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
             */
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Default'));
            $this->setView('alert', $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
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
        $query = $meta->getDoctrineQuery($this->_set);

        if(null == $this->_request->p($meta['db']['primaryKey']))
        {
            /**
             * 从模型获取数据,如果记录不存在,加载错误视图
             */
            $result = $query->where($primaryKey . ' = ?', $id)->fetchOne();
            if(false == $result)
            {
                return $this->setView('alert', $this->_lang->t('MSG_NO_RECORD'));
            }

            /**
             * 处理数据
             */
            $relatedField = $meta->connectMetadata($this->_meta);
            $relatedField->order();
            $groupList = $relatedField->getEditGroupList();
            $data = $result->toArray();
            $data = $meta->convertDataToSingle($data);

            /**
             * 设置视图
             */
            $this->_view = array(
                'class' => 'Default_Common_View_Form',
                'data' => get_defined_vars(),
            );
        } else {
            /**
             * 检查记录是否存在
             */
            $id = $this->_request->p($meta['db']['primaryKey']);
            $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
            if(false == $query)
            {
                return $this->setView('alert', $this->_lang->t('MSG_NO_RECORD'));
            }

            /**
             * 设置行为为入库,连接元数据
             */
            $this->setAction('db');
            $relatedField = $meta->connectMetadata($meta);
            $editDbField = $relatedField->getEditDbField();
            
            /**
             * 转换,验证和还原
             */
            $data = $meta->convertSingleData($editDbField, 'db', $_POST);
            $this->_meta->validateData($editDbField, $data);
            $data = $meta->restoreData($editDbField, $data);

            /**
             * 入库
             */
            $query->fromArray($data);
            $query->save();
            
            /**
             * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
             */
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Default'));
            return $this->setView('alert', $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
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
        $alias = $query->getRootAlias() . '.';
        $object = $query
            //->select($modelName . '.' . $meta['db']['primaryKey'])
            ->whereIn($alias . $meta['db']['primaryKey'], $id)
            ->execute();

        // TODO $object->delete();
        // TODO 统计删除数
        foreach($object as $key => $val)
        {
            foreach($meta['model'] as $model)
            {
                if(isset($object[$key][$model['asName']]))
                {
                    $object[$key][$model['asName']]->delete();
                }
            }
            $object[$key]->delete();
        }

        /**
         * 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
         */
        $this->executeOnFunction('afterDb', 'delete', array());
        $url = urldecode($this->_request->p('_page'));
        '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Default'));
        $this->setView('alert', $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'), $url);
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
        $data = '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->_lang->t('LBL_ACTION_EDIT') .'" href="' . $this->_url->createUrl($this->_set, array('action' => 'Edit', $primaryKey => $copyData[$primaryKey])) . '"><span class="ui-icon ui-icon-tag">Edit</span></a>'
              . '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->_lang->t('LBL_ACTION_VIEW') .'" href="' . $this->_url->createUrl($this->_set, array('action' => 'View', $primaryKey => $copyData[$primaryKey])) . '"><span class="ui-icon ui-icon-lightbulb">View</span></a>'
              . '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->_lang->t('LBL_ACTION_COPY') .'" href="' . $this->_url->createUrl($this->_set, array('action' => 'Add', $primaryKey => $copyData[$primaryKey])) . '"><span class="ui-icon ui-icon-transferthick-e-w">Clone</span></a>'
              . '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->_lang->t('LBL_ACTION_DELETE') .'" href="' . $this->_url->createUrl($this->_set, array('action' => 'Delete', $primaryKey => $copyData[$primaryKey])) . '" onclick="javascript:return confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE);"><span class="ui-icon ui-icon-closethick">Delete</span></a>';
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
        $query = $this->_meta->getDoctrineQuery($this->_set);
        $result = $query->select('Max(`order`) as max_order')->fetchOne();
        if(false != $result)
        {
            return $result['max_order'];
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
        '0' == $value && $value = NULL;
        return $value;
    }
}
