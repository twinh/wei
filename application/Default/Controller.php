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
        $set = $this->_set = $ini->getSet();
        $this->_config = $ini->getConfig();
       
        /**
         * 访问控制
         */
        $this->_isAllowVisited();

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

        /*$form = Qwin::run('Qwin_Form');
        foreach($this->_meta['field'] as $field)
        {
            echo '<li><b>' . $field['basic']['title'] .'</b>' . $form->auto($field['form']) . '</li>' . "\r\n";
        }
        exit;
        // 连接相关的元数据,到主元数据中
        //$this->_meta->connetDoctrine($this->_meta, $this->_model);



        // 转换成Doctrine对象
        //$this->_meta->toDoctrine($this->_meta, $this->_model);



        // 如何管理其他
        // 构建Doctrine的对象的几种情况
        // 1. 根据metadata的field和db构建
        // 2. 直接提供field和table构建
        //
        // 连接元数据的几种情况
        // 1. 分解metadata的model链接
        // 2. 提供类名连接?
//Doctrine_Query::create()->

        /*
        $q = Doctrine_Query::create()
    ->from('User u')
    ->leftJoin('u.Phonenumbers p');

echo $q->getSqlQuery();
         */
        //p($users);
        //exit;






        
        /*

        $controller->meta = $metadata;
        // 语言转换
        $this->_loadLang($set, $config);
        $controller->__meta = $metadata->convertLang($controller->__meta, $controller->lang);

        // 获取模型类名称
        
        if(isset($controller->__meta['db']['table']))
        {
            $controller->meta->metadataToModel($controller->__meta, $model);
        }*/
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
        $relatedField = $meta->connectRelatedMetadata($this->_meta);
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
        $relatedField = $meta->connectRelatedMetadata($this->_meta);
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
    public function actionShow()
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
        $data = $result->toArray();
        $data = $meta->convertDataToSingle($data);

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Qwin_Trex_View_Map',
            'data' => array(
                'data' => $data,
            ),
        );
    }

    /**
     * 添加记录
     */
    public function actionAdd()
    {
        
        /*$query = $this->_meta->getDoctrineQuery($this->_set);

        $modelName = $this->_meta->getClassName('Model', $this->_set);
        $query = new $modelName;

        $data = array(
            'id' => 'test',
            'category_id' => '21ef9f4c-878f-46b2-ba45-78b1a80614f2',
            'category_2' => '',
            'category_3' => '',
            'title' => 'Another event',
            'fff' => 'fsd',
            'detail' => array(
                'id' => 'test-conte',
                'article_id' => 'test',
                'content' => 'content',
                'meta' => 'meta',
                'lll' => 'lll',
            )
        );
        $query->fromArray($data);

        p($query);*/

   /* [author] =>
    [jump_to_url] =>
    [thumb] =>
    [hit] => 2
    [page_name] =>
    [template] =>
    [is_posted] => 2001001
    [is_index] => 2001001
    [content_preview] =>
    [order] => 75
        );
(

    [detail] => Array
        (
            [id] => 36de3362-1748-48ab-8f08-666d7a7c8b58
            [article_id] => 9c95c971-1602-4d36-91be-d039e20ee49e
            [content] =>
            [meta] =>
        )

)*/

        
        //$a = $query->fetchOne()->toArray();
        //p($a);
        //var_dump($q);
        exit;
        $ini = Qwin::run('-ini');
        $meta = &$this->_meta;


        // 加载关联模型,元数据
        $this->_meta->loadRelatedData($meta['model']);
        // 获取模型类名称
        $modelName = $ini->getClassName('Model', $this->__query);
        $query = $this->_meta->connectModel($modelName, $meta['model']);
        $meta = $this->_meta->connetMetadata($meta);

        if(!$_POST)
        {
            // 三种模式　1.复制(根据主键获取初始值) 2.从url获取值 3. 获取模型默认值
            // 1. 复制
            // 根据url参数中的值,获取对应的数据库资料
            $id = $this->_request->g($meta['db']['primaryKey']);
            if(null != $id)
            {
                $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
                if(false == $query)
                {
                    $this->Qwin_Helper_Js->show($this->t('MSG_NO_RECORD'));
                }
                $data = $query->toArray();
            // url + 模型默认值
            } else {
                // 从模型配置数组中取出表单初始值
                $data = $this->_meta->getSettingValue($meta['field'], array('form', '_value'));
                // 从url地址参数取出初始值,覆盖原值
                $data = Qwin::run('-url')->getInitalData($data);
            }
            unset($data[$meta['db']['primaryKey']]);

            $data = $this->_meta->convertDataToSingle($data);
            // 根据配置和控制器中的对应方法转换数据
            //$data = $this->_meta->convertSingleData($meta['field'], $this->__query['action'], $data);
            $tip_data = $this->_meta->getTipData($meta['field']);

            // 获取 jQuery Validate 的验证规则
            $validator_rule = Qwin::run('Qwin_JQuery_Validator')->getRule($meta['field']);
            // 排序
            $meta['field'] = $this->_meta->orderSettingArr($meta['field']);
            // 分组
            $meta['field'] = $this->_meta->groupingSettingArr($meta['field']);

            // 初始化视图变量数组
            $this->__view = array(
                'set' => $meta,
                'data' => $data,
                'tip_data' => &$tip_data,
                'tip_name' => &$tip_name,
                'validator_rule' => &$validator_rule,
                'http_referer' => urlencode(Qwin::run('-str')->set($_SERVER['HTTP_REFERER']))
            );

            // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
            $this->__view_element = array(
                'content' => RESOURCE_PATH . '/php/View/Element/DefaultForm.php',
            );
            $this->loadView($ini->load('Resource/View/Layout/DefaultControlPanel', false));
        } else {
            // POST 操作下,设置action为db
            $this->setAction('db');

            // 获取模型类名称
            $modelName = $ini->getClassName('Model', $this->__query);
            $query = new $modelName;
            /**
             * 转换数据
             * 验证数据
             * 填充数据到模型中
             * 保存数据
             */
            $data = $this->_meta->convertSingleData($meta['field'], $this->__query['action'], $_POST);
            $this->_meta->validateData($meta['field'], $data);
            $query = $this->_meta->fillData($meta, $query, $data);
            $query->save();

            // 在数据库操作之后,执行相应的 on 函数
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            if($url)
            {
                Qwin::run('-url')->to($url);
            } else {
                Qwin::run('-url')->to(url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'])));
            }
        }
    }

    /**
     * 编辑记录
     */
    public function actionEdit()
    {
        $query = $this->_meta->getDoctrineQuery($this->_set);


        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('-gpc');
        $meta = &$this->_meta;

        /**
         * 加载关联模型,元数据
         * 连接模型
         * 连接元数据
         */
        $this->_meta->loadRelatedData($meta['model']);
        $modelName = $ini->getClassName('Model', $this->__query);
        $query = $this->_meta->connectModel($modelName, $meta['model']);
        $meta = $this->_meta->connetMetadata($meta);

        if(null == $this->_request->p($meta['db']['primaryKey']))
        {
            // 根据url参数中的值,获取对应的数据库资料
            $id = $this->_request->g($meta['db']['primaryKey']);
            $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
            if(false == $query)
            {
                $this->Qwin_Helper_Js->show($this->t('MSG_NO_RECORD'));
            }
            p($query);exit;
            $dbData = $query->toArray();
            $dbData = $this->_meta->convertDataToSingle($dbData);

            // 根据配置和控制器中的对应方法转换数据
            $dbData = $this->_meta->convertSingleData($meta['field'], $this->__query['action'], $dbData);

            $tip_data = $this->_meta->getTipData($meta['field']);

            // 获取 jQuery Validate 的验证规则
            $validator_rule = Qwin::run('Qwin_JQuery_Validator')->getRule($meta['field']);




            // 排序
            $meta['field'] = $this->_meta->orderSettingArr($meta['field']);

            /*$groupSet = array();
            // 取出所有的分组
            foreach($meta['field'] as $field)
            {
                !isset($field['basic']['group']) && $field['basic']['group'] = 'LBL_GROUP_BASIC_DATA';
            }
            if('custom' == $field['form']['_type'])
            {
                $new_arr['_custom'][$key] = $val;
            } else {
                $new_arr[$val['basic']['group']][$key] = $val;
            }


            $action = Qwin::run('-c')->__query['action'];
        $new_arr = array();
        foreach($field_arr as $key => $val)
        {
            !isset($val['basic']['group']) && $val['basic']['group'] = '';
            // TODO array('List' => true, 'Edit' => true, 'Add' => false ?
            if('Edit' == $action || 'Add' == $action)
            {
                if('custom' == $val['form']['_type'])
                {
                    $new_arr['_custom'][$key] = $val;
                } else {
                    $new_arr[$val['basic']['group']][$key] = $val;
                }
            } else {
                if(isset($val['list']['isShow']) && false == $val['list']['isShow'])
                {
                    $new_arr['_custom'][$key] = $val;
                } else {
                    $new_arr[$val['basic']['group']][$key] = $val;
                }
            }
        }
        return $new_arr;
            p($this->_meta->createLayoutArr($meta['field']));
            exit;*/

            // 分组
            $meta['field'] = $this->_meta->groupingSettingArr($meta['field']);

            // 初始化视图变量数组
            $this->__view = array(
                'set' => &$meta,
                'data' => $dbData,
                'tip_data' => &$tip_data,
                'tip_name' => &$tip_name,
                'validator_rule' => &$validator_rule,
                'http_referer' => urlencode(Qwin::run('-str')->set($_SERVER['HTTP_REFERER']))
            );

            // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
            $this->__view_element = array(
                'content' => RESOURCE_PATH . '/php/View/Element/DefaultForm.php',
            );
            $this->loadView($ini->load('Resource/View/Layout/DefaultControlPanel', false));
        } else {
            // 检查记录是否存在
            // 根据url参数中的值,获取对应的数据库资料
            $id = $this->_request->p($meta['db']['primaryKey']);
            $query = $query->where($meta['db']['primaryKey'] . ' = ?', $id)->fetchOne();
            if(false == $query)
            {
                $this->Qwin_Helper_Js->show($this->t('MSG_NO_RECORD'));
            }

            /**
             * POST 操作下,设置action为db
             * 转换数据
             * 验证数据
             * 填充数据到模型中
             * 保存数据
             */
            $this->setAction('db');
            $data = $this->_meta->convertSingleData($meta['field'], $this->__query['action'], $_POST);
            $this->_meta->validateData($meta['field'], $data);
            $query = $this->_meta->fillData($meta, $query, $data);
            $query->save();

            // 在数据库操作之后,执行相应的 on 函数
            $this->executeOnFunction('afterDb', $this->resetAction(), $data);
            $url = urldecode($this->_request->p('_page'));
            if($url)
            {
                Qwin::run('-url')->to($url);
            } else {
                Qwin::run('-url')->to(url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'])));
            }
        }
    }

    /**
     * 删除记录
     */
    public function actionDelete()
    {
        $ini = Qwin::run('-ini');
        $this->_request = Qwin::run('-gpc');
        $meta = &$this->_meta;

        $id = $this->_request->g($meta['db']['primaryKey']);
        $id = explode(',', $id);

        // 加载关联模型,元数据
        $this->_meta->loadRelatedData($meta['model']);
        // 获取模型类名称
        $modelName = $ini->getClassName('Model', $this->__query);
        $query = $this->_meta->connectModel($modelName, $meta['model']);
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
                $object[$key][$model['asName']]->delete();
            }
            $object[$key]->delete();
        }

        $this->executeOnFunction('afterDb', $this->resetAction(), array());
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if($url)
        {
            Qwin::run('-url')->to($url);
        } else {
            Qwin::run('-url')->to(url(array($this->__query['namespace'], $this->__query['module'], $this->__query['controller'])));
        }
    }

    

    /**
     * 在列表操作下,为操作域设置按钮
     * 
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertListOperation($val, $name, $data, $copyData)
    {
        return '操作区域';
        /*return $this->_meta->getOperationLink(
            $this->_meta['db']['primaryKey'],
            $data[$this->_meta['db']['primaryKey']],
            $this->__query
        );*/
    }

    /**
     * 在列表操作下,初始化排序域的值,依次按5递增
     *
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertAddOrder($val, $name, $data, $copyData)
    {
        $class = Qwin::run('-ini')->getClassName('Model', $this->__query);
        return $this->_meta->getInitalOrder($class);
    }

    /**
     * 在入库操作下,转换编号
     *
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertDbId($val, $name, $data, $copyData)
    {
        return Qwin::run('Qwin_converter_String')->getUuid($val);
    }

    /**
     * 在入库操作下,转换创建时间
     *
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertDbDateCreated($val, $name, $data, $copyData)
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    /**
     * 在入库操作下,转换修改时间
     *
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertDbDateModified()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    /**
     * 在入库操作下,转换分类的值
     *
     * @param <type> 当前域的值
     * @param <type> 当前域的名称
     * @param <type> $data 已转换过的当前记录的值
     * @param <type> $cpoyData 未转换过的当前记录的值
     * @return <type> 当前域的新值
     */
    public function convertDbCategoryId($val)
    {
        '0' == $val && $val = NULL;
        return $val;
    }
}
