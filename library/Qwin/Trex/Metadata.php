<?php
/**
 * Metadata
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
 * @package     Qwin
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Trex_Metadata extends Qwin_Metadata
{
    /*
     * 数据缓存,分几种,分别是 category, common_class list
     */
    private $_cache = array();
    
    /**
     * 各个模型实例化对象的数组
     * @var array
     * @todo 相同名称模型的独立性
     */
    protected $_modelObjct = array();

    /**
     * 各个控制器的元数据
     * @var array
     * @todo 访问控制
     */
    public $metaExt = array();

    public $query = array();

    /**
     * 由外键组成的数组
     * @var array
     */
    protected $_foreignKey = array();

    protected $_modelPrimaryKey = array();
    
    /**
     * 语言名称
     * @var string
     */
    public $lang;

    /**
     * 数据表前缀
     * @var string
     */
    public $tablePrefix;

    public function toDoctrine(Qwin_Metadata $meta, Doctrine_Record $model)
    {
        $model->setTableName($meta->tablePrefix . $meta->db['table']);
        $queryField = $meta->field->getAttrList('isSqlQuery');
        foreach($meta->field as $field)
        {
            $model->hasColumn($field['form']['name']);
        }
    }

    /**
     * 获取数据表前缀,方便调用
     *
     * @return string 表前缀
     */
    public function getTablePrefix()
    {
        if(null == $this->tablePrefix)
        {
            $config = Qwin::run('-ini')->getConfig();
            $mainAdapter = $config['database']['mainAdapter'];
            $this->tablePrefix = $config['database']['adapter'][$mainAdapter]['prefix'];
        }
        return $this->tablePrefix;
    }

    /**
     * 获取标准的类名
     *
     * @param string $addition 附加的字符串
     * @param array $set 配置数组
     * @return string 类名
     */
    public function getClassName($addition, $set)
    {
        return $set['namespace'] . '_' . $set['module'] . '_' . $addition . '_' . $set['controller'];
    }

    /**
     * 获取Doctrine的查询对象
     *
     * @param array $set 配置
     * @param boolen $isJoinModel 是否连接关联的模块
     * @return object Doctrine_Record 查询对象
     * @todo 缓存$query
     */
    public function getDoctrineQuery($set, $isJoinModel = true)
    {
        /**
         * 初始元数据和模型主类
         */
        $metaName = $this->getClassName('Metadata', $set);
        Qwin::load($metaName);
        $metaObj = Qwin_Metadata_Manager::get($metaName);
        $queryField = $metaObj->field->getAttrList('isSqlQuery');

        $modelName = $this->getClassName('Model', $set);
        $modelObj = Qwin::run($modelName);
        $modelObj->setTableName($this->getTablePrefix() . $metaObj['db']['table']);
        foreach($queryField as $field)
        {
            $modelObj->hasColumn($field);
        }

        /**
         * 初始化Doctrine查询
         */
        $query = Doctrine_Query::create()->from($modelName);

        /**
         * 加载其他关联的类
         */
        if($isJoinModel)
        {
            foreach($metaObj['model'] as $model)
            {
                Qwin::load($model['metadata']);
                $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);
                $queryField = $linkedMetaObj->field->getAttrList('isSqlQuery');

                $linkedModelObj = Qwin::run($model['name']);
                $linkedModelObj->setTableName($this->getTablePrefix() . $linkedMetaObj['db']['table']);
                foreach($queryField as $field)
                {
                    $linkedModelObj->hasColumn($field);
                }

                // 设置模型关系
                call_user_func(
                    array($modelObj, 'hasOne'),
                    $model['name'] . ' as ' . $model['asName'],
                        array(
                            'local' => $model['local'],
                            'foreign' => $model['foreign']
                        )
                );
                $query->leftJoin($modelName . '.' . $model['asName'] . ' ' . $model['asName']);
            }
        }
        return $query;
    }

    /**
     * 将主元数据关联的元数据加入到主元数据中
     *
     * @param Qwin_Metadata $meta 主元数据
     */
    public function connectRelatedMetadata(Qwin_Metadata $meta)
    {
        $mainMetaField = clone $meta['field'];
        foreach($meta['model'] as $model)
        {
            Qwin::load($model['metadata']);
            $relatedMeta = Qwin_Metadata_Manager::get($model['metadata']);
            $tmpMeta = array();
            foreach($relatedMeta['field'] as $field)
            {
                // 存储原来的名称
                $field['form']['_oldName'] = $field['form']['name'];
                $field['form']['_arrayName'] = $model['asName'] . '[' . $field['form']['name'] . ']';
                $field['form']['name'] = $model['asName'] . '_' . $field['form']['name'];
                if($field['form']['_oldName'] == $field['form']['id'])
                {
                    $field['form']['id'] = str_replace('_', '-', $field['form']['name']);
                }
                $tmpMeta[$field['form']['name']] = $field;
            }
            $mainMetaField->addData($tmpMeta);
        }
        return $mainMetaField;
    }

    /**
     * 计算两个数组的交集,键名来自第一个数组,值来自第二个数组
     *
     * @param array $array1 第一个参数数组
     * @param array $array2
     * @return array
     */
    public function intersect($array1, $array2)
    {
        foreach($array1 as $key)
        {
            if(isset($array2[$key]))
            {
                $array1[$key] = $array2[$key];
            } else {
                $array1[$key] = null;
            }
        }
        return $array1;
    }

    /**
     * 为Doctrine查询对象增加查询语句
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @return Qwin_Metadata 当前类
     * @todo 是否要将主类加入到$meta['model']数组中,减少代码重复
     */
    public function addSelectToQuery(Qwin_Metadata $meta, Doctrine_Query $query)
    {
        /**
         * 设置主类的查询语句
         */
        // 调整主键的属性,因为查询时至少需要选择一列
        $primaryKey = $meta['db']['primaryKey'];
        $meta->field
             //->setAttr($primaryKey, 'isList', true)
             ->setAttr($primaryKey, 'isSqlField', true)
             ->setAttr($primaryKey, 'isSqlQuery', true);
        
        $queryField = $meta->field->getAttrList('isSqlQuery');
        $query->select(implode(', ', $queryField));

        /**
         * 设置关联类的查询语句
         */
        foreach($meta['model'] as $model)
        {
            Qwin::load($model['metadata']);
            $linkedMetaObj = Qwin_Metadata_Manager::get($model['metadata']);

            // 调整主键的属性,因为查询时至少需要选择一列
            $primaryKey = $linkedMetaObj['db']['primaryKey'];
            $linkedMetaObj->field
                          ->setAttr($primaryKey, 'isSqlField', true)
                          ->setAttr($primaryKey, 'isSqlQuery', true);
            
            $queryField = $linkedMetaObj->field->getAttrList('isSqlQuery');
            foreach($queryField as $field)
            {
                $query->addSelect($model['asName'] . '.' . $field);
            }
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加排序语句,优先级为Url地址 > 元数据 > 主键,addOrderToDoctrineQuery的缩写
     * 
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @return Qwin_Metadata 当前类
     * @todo 关联元数据的排序
     * @todo 允许自定义Url的键名
     * @todo 允许多个排序字段
     * @todo 将Url查询并入到元数据中,即元数据中的order数组是动态的
     */
    public function addOrderToQuery(Qwin_Metadata $meta, Doctrine_Query $query)
    {
        $request = Qwin::run('Qwin_Request');
        $arrayHepler = Qwin::run('Qwin_Helper_Array');
        $alias = $query->getRootAlias() . '.';

        // 排序字段名和排序类型
        $orderField = $request->g('orderField');
        $orderType = strtoupper($request->g('orderType'));

        // 数据表字段的域
        $queryField = $meta->field->getAttrList('isSqlQuery');

        if(in_array($orderField, $queryField))
        {
            $orderType = $arrayHepler->forceInArray($orderType, array('DESC', 'ASC'));
            $query->orderBy($alias . $orderField . ' ' .  $orderType);
        } elseif(isset($meta['db']['order']) && !empty($meta['db']['order'])) {
            $orderTempArr = array();
            foreach($meta['db']['order'] as $fieldSet)
            {
                $fieldSet[1] = $arrayHepler->forceInArray($fieldSet[1], array('DESC', 'ASC'));
                $orderTempArr[] = $alias . $fieldSet[0] . ' ' . $fieldSet[1];
            }
            $query->orderBy(implode(', ', $orderTempArr));
        } else {
            $query->orderBy($alias . $meta['db']['primaryKey'] . ' DESC');
        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句,优先级为Url地址 > 元数据
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @return Qwin_Metadata 当前类
     * @todo 补全第二类情况
     * @todo 完善查询类型
     * @todo 同addOrderToDoctrineQuery
     */
    public function addWhereToQuery(Qwin_Metadata $meta, Doctrine_Query $query)
    {
        $request = Qwin::run('Qwin_Request');
        $arrayHepler = Qwin::run('Qwin_Helper_Array');
        $alias = $query->getRootAlias() . '.';

        // 'eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc'
        $searchTypeArr = array(
            'eq' => '=',
            'ne' => '<>',
            'lt' => '<',
            'le' => '<=',
            'gt' => '>',
            'ge' => '>=',
            //'cn' => 'like',
            //'nc' => 'not like'
        );

        $searchField = $request->g('searchField');
        $searchType = $request->g('searchType');
        $searchValue = $request->g('searchValue');

        // 数据表字段的域
        $queryField = $meta->field->getAttrList('isSqlQuery');

        if(in_array($searchField, $queryField))
        {
            $searchType = $arrayHepler->forceInArray($searchType, array_keys($searchTypeArr));
            $query->where($alias . $searchField . ' ' . $searchTypeArr[$searchType] . ' ?', $searchValue);
        } elseif(isset($meta['db']['where']) && !empty($meta['db']['where'])) {

        }
        return $this;
    }

    /**
     * 为Doctrine查询对象增加查找语句,优先级为Url地址 > 元数据
     *
     * @param Qwin_Metadata $meta
     * @param Doctrine_Query $query
     * @param string $rowName Url中,列数的键名
     * @return Qwin_Metadata 当前类
     * @todo 分开为Limit和Offset
     */
    public function addLimitToQuery(Qwin_Metadata $meta, Doctrine_Query $query, $rowName = 'row', $pageName = 'page')
    {
        $request = Qwin::run('Qwin_Request');
        $rowNum = intval($request->g($rowName));
        if($rowNum <= 0)
        {
            $rowNum = $meta['db']['limit'];
        // 最多同时读取500条记录
        } elseif($rowNum > 500) {
            $rowNum = 500;
        }
        $query->limit($rowNum);

        // Offset
        $nowPage = intval($request->g($pageName));
        $nowPage <= 0 && $nowPage = 1;
        $offset = ($nowPage - 1) * $rowNum;
        $query->offset($offset);

        return $this;
    }

    /**
     * 数据转换,用于 edit 等二维数组的数据
     *
     * @param array $set 配置数组的字段子数组 $this->__meta['field']
     * @param string $action Action 的名称,一般为  add, edit, show
     * @param array $data 二维数组,一般是从数据库取出的数组
     * @todo 是否要必要支持多个转换函数/方法, 增加缓存,减少重复判断等
     * @todo 对于非当前控制器下, $self 的问题
     */
    public function convertSingleData($meta, $action, $row, $isListLink = false)
    {
        /**
         * 初始化数据
         * 控制器对象,行为,数据副本
         */
        $ctrler = Qwin::run('-controller');
        $url = Qwin::run('-url');
        $action = strtolower($action);
        $rowCopy = $row;

        foreach($meta as $field => $set)
        {
            $name = $set['form']['name'];
            !isset($row[$name]) && $row[$name] = null;

            /**
             * 使用元数据的转换器进行转换
             */
            if(isset($set['converter'][$action]) && is_array($set['converter'][$action]))
            {
                $param = $set['converter'][$action];
                if(Qwin::isCallable($param[0]))
                {
                    $method = $param[0];
                    $param[0] = $row[$name];
                    // TODO 静态调用和动态调用
                    if(!is_object($method[0]) && !function_exists($method[0]))
                    {
                        $method[0] = Qwin::run($method[0]);
                    }
                    $row[$name] = call_user_func_array($method, $param);
                    continue;
                }
            }

            /**
             * 使用控制器中的方法进行转换
             */
            $methodName = str_replace(array('_', '-'), '', 'convert' . $action . $name);
            if(method_exists($ctrler, $methodName))
            {
                $row[$name] = call_user_func_array(
                    array($ctrler, $methodName),
                    array($row[$name], $name, $row, $rowCopy)
                );
            }

            /**
             * 增加Url查询
             * @todo 是否应该出现在此
             */
            if(true == $isListLink && $set['attr']['isListLink'])
            {
                !isset($rowCopy[$name]) && $rowCopy[$name] = null;
                $row[$name] = '<a href="' . $url->createUrl($ctrler->_set + array('searchField' => $name, 'searchValue' => $rowCopy[$name])) . '">' . $row[$name] . '</a>';
            }
        }
        return $row;
/*
 *
        // fixed 2010-06-27 添加操作时,清空其他主键的值
        // TODO 整理
        //$urlAction = Qwin::run('-gpc')->g('action');

        foreach($set as $field => $val)
        {
            // 防止对非数据库字段域进行转换,导致入库出错
            if('db' == $action && false == $val['list']['isSqlField'])
            {
                continue;
            }
            // fixed 2010-06-27 
            if('Add' == $urlAction && in_array($field, $this->_modelPrimaryKey))
            {
                $row[$field] = null;
            }
            
        }*/
    }

    /**
     * 数据转换,用于 list 等三位数组的数据
     *
     * @param array $meta 配置数组的字段子数组
     * @param string $action Action 的名称,一般为 list
     * @praam array $data 三维数组,一般是从数据库取出的数组
     */
    public function convertMultiData($meta, $action, $data, $isListLink = true)
    {
        foreach($data as &$row)
        {
            $row = $this->convertSingleData($meta, $action, $row, $isListLink);
        }
        return $data;
    }

    /**
     * 获取 url 中的数据
     *
     * @param array $data add.edit等操作传过来的初始数据
     * @param int $mode
     */
    public function getUrlData($data, $mode = 'ovwewrite')
    {
        !in_array($mode, $this->_inital_mode) && $mode = $this->_inital_mode[0];
        // 覆盖 $data 的值
        if($mode == $this->_inital_mode[0])
        {
            foreach($data as $key => $val)
            {
                if(isset($this->_get['data'][$key]) && '' != $this->_get['data'][$key])
                {
                    $data[$key] = $this->_get['data'][$key];
                }
            }
        } else {
            foreach($data as $key => $val)
            {
                if('' == $val)
                {
                    $data[$key] = $this->_get['data'][$key];
                }
            }
        }
        return $data;
    }

    public function translate(Qwin_Trex_Language $lang)
    {
        foreach($this->_data as $data)
        {
            $data->translate($lang);
        }
        return $this;
    }

    public function setLang($lang = null)
    {
        if(null == $lang)
        {
            if(empty($this->lang))
            {
                $this->lang = Qwin::run('-ini')->getConfig('interface.language');
            }
        }
        return true;
    }

    /**
     * 获取通用分类的数据
     *
     * @param string $name 分类名称
     * @return 分类数据
     */
    public function getCommonClassList($name, $lang = null)
    {
        null == $lang && $this->setLang() && $lang = $this->lang;
        if(!isset($this->_cache['common_class'][$lang][$name]))
        {
            
            $this->_cache['common_class'][$lang][$name] = Qwin::run('Qwin_Cache_CommonClass')
                ->getCache($name, $lang);
        }
        return $this->_cache['common_class'][$lang][$name];
    }

    /**
     * 根据主配置元数据,加载相关模型类和元数据类,同时转换关联元数据的语言
     */
    public function loadRelatedData($modelMetadata, $ctrler = null)
    {
        foreach($modelMetadata as $model)
        {
            // 初始化模型
            if(!isset($this->_modelObjct[$model['name']]))
            {
                $this->_modelObjct[$model['name']] = Qwin::run($model['name']);
            }
            // 加载Metadata
            $this->loadMetadataToMetaExt($model['metadata'], $model['name']);
        }
    }  

    public function convertDataToSingle($data)
    {
        if(isset($data[0]))
        {
            foreach($data as $key => $row)
            {
                $data[$key] = $this->convertDataToSingle($row);
            }
        } else {
            foreach($data as $key => $val)
            {
                if(is_array($val))
                {
                    foreach($val as $key2 => $val2)
                    {
                        $data[$key . '_' . $key2] = $val2;
                    }
                    unset($data[$key]);
                }
            }
        }
        return $data;
        /*
        //if(!empty($dbData))
        //{
            // 三维数组(JsonList)
            if(isset($dbData[0]) && is_array($dbData[0]))
            {
                foreach($dbData as $key => $row)
                {
                    $dbData[$key][$model['asName'] . '_' . $fieldMeta['form']['name']] = $str->set($dbData[$key][$model['asName']][$fieldMeta['form']['name']]);
                    unset($dbData[$key][$model['asName']][$fieldMeta['form']['name']]);
                }
            // 二维数组(Edit/Add/Clone)
            } else {
                if(isset($dbData[$model['asName']]))
                {
                    $dbData[$model['asName'] . '_' . $fieldMeta['form']['name']] = $dbData[$model['asName']][$fieldMeta['form']['name']];
                    unset($dbData[$model['asName']][$fieldMeta['form']['name']]);
                }
            }
        //}
        //*/
    }

    /**
     * 还原一维数组为二维数组
     *
     * @param object $meta 转换过的元数据
     * @param array $data 入库数据
     * @return 新数据
     */
    public function restoreData($meta, $data)
    {
        $newData = array();
        foreach($meta as $key => $field)
        {
            if(!isset($data[$key]))
            {
                continue;
            }
            if(!isset($field['form']['_oldName']))
            {
                $newData[$key] = $data[$key];
            } else {
                $modelName = str_replace('_' .$field['form']['_oldName'], '', $key);
                !isset($newData[$modelName]) && $newData[$modelName] = array();
                $newData[$modelName][$field['form']['_oldName']] = $data[$key];
            }
        }
        return $newData;
    }

    /**
     * 数据验证
     *
     * @todo 是否使用单独的助手类,和 jQuery 验证插件配套
     * @todo 象转换一样,允许使用自身的方法, validatePassword
     */
    public function validateData($meta, $data)
    {
        // 初始验证类
        $validator = Qwin::run('Qwin_Validator');
        $validator->add('Qwin_Validator_Common');
        $arr = Qwin::run('-arr');
        
        // 加载验证信息
        $validatorMessage1 = $this->getCommonClassList('validator_message', 'rsc');
        $validatorMessage2 = $this->getCommonClassList('validator_message');
        $validatorMessage = array_combine($validatorMessage1, $validatorMessage2);
        
        foreach($meta as $field)
        {
            if(isset($field['validator']['rule']))
            {
                $field['validator']['rule'] = $this->makeRequiredAtFront($field['validator']['rule']);
                // 转换为数组
                $arr->set($field['validator']['rule']);
                foreach($field['validator']['rule'] as $method => $param)
                {
                    $arr->set($param);
                    // 用于错误提示
                    $msgParam = $param;
                    if(isset($data[$field['form']['name']]))
                    {
                        $val = $data[$field['form']['name']];
                    } else {
                        $val = null;
                    }
                    array_unshift($param, $val);
                    $result = $validator->call($method, $param);
                    if(false == $result)
                    {
                        $msg = $this->format($validatorMessage[$method], $msgParam);
                        $msg = $this->t('MSG_ERROR_FIELD') . $field['basic']['title'] . '\n' . $this->t('MSG_ERROR_MSG') . $msg;
                        Qwin::run('Qwin_Helper_Js')->show($msg);
                    }
                }
            }
        }
    }

    /**
     * 获取命名空间数组
     *
     */
    public function getNamespace()
    {
        $file = scandir(QWIN_ROOT_PATH . '/App');
        $folder = array();
        foreach($file as $val)
        {
            if('.' != $val && '..' != $val)
            {
                $folder[$val] = $val;
            }
        }
        return $folder;
    }

    /**
     * 翻译单独一个代号
     * @param string $code 要翻译的代号
     * @return string 如果存在该代号,返回翻译值,否则返回原代号
     */
    public function t($code)
    {
        !isset($this->langData) && $this->langData = Qwin::run('-c')->lang;
        if(isset($this->langData[$code]))
        {
            return $this->langData[$code];
        }
        return $code;
    }

    /**
     * 根据代码转换出通用分类对应的值
     *
     * @param int $code 分类代码值
     * @param string $name 分类名称
     * @return string 分类的值
     */
    function convertCommonClass($code, $name, $lang = null)
    {
        null == $lang && $this->setLang() && $lang = $this->lang;
        $this->getCommonClassList($name, $lang);
        if(isset($this->_cache['common_class'][$lang][$name][$code]))
        {
            return $this->_cache['common_class'][$lang][$name][$code];
        }
        return '-';
    }
    
    /*function getClassList($name)
    {
        if(!isset($this->_cache['class'][$name]))
        {
            require Qwin::run('ArrayCache')->getClassPath($name);
            $this->_cache['class'][$name] = &$_CACHE['class'][$name];
        }
        return $this->_cache['class'][$name];
    }*/

    function setCache($name, $data, $type = 'class')
    {
        $arr = array('class', 'cc');
        !in_array($type, $arr) && $type = $arr[0];
        $this->_cache[$type][$name] = $data;
    }

    /**
     * 增加点击
     *
     *
     */
    public function addHit($table, $id, $field = 'hit', $num = 1)
    {
        Qwin::run('-qry')->setTable($table);

        $sql = "UPDATE " . Qwin::run('-qry')->getTable() . " SET `$field` = $field+$num WHERE `id` = '$id'; ";
        Qwin::run('-db')->Query($sql);
    }

    public function getUrlList($field, $url_data)
    {
        Qwin::run('-arr')->set($url_data);
        $data = array();
        foreach($url_data as $key => $val)
        {
            if('' != $val && isset($url_data[$field[$key]['form']['name']]))
            {
                $data[$field[$key]['form']['name']] = $field[$key]['form']['name'];
            }
        }
        return $data;
    }


    // TODO !!
    public function convertUrlQuery2($data, $sql_data)
    {
        foreach($data as $key => $val)
        {
            if(isset($this->__meta['field'][$key]['list']['isListLink']) && $this->__meta['field'][$key]['list']['isListLink'] == true)
            {
                $data[$key] = '<a href="' . url(array('admin', $this->__query['controller']), array(_S('url', '_DATA') . '%5B' . $key . '%5D' => $sql_data[$key])) . '">' . $val . '</a>';
            }
        }
        return $data;
    }

    public function getTipData($set)
    {
        $validatorMessage1 = $this->getCommonClassList('validator_message', 'rsc');
        $validatorMessage2 = $this->getCommonClassList('validator_message');
        $validatorMessage = array_combine($validatorMessage1, $validatorMessage2);

        $tipData = array();
        foreach($set as $field)
        {
            // 读取域描述
            if(isset($field['basic']['descrip']) && '' != $field['basic']['descrip'])
            {
                if(is_array($field['basic']['descrip']))
                {
                    foreach($field['basic']['descrip'] as $tip)
                    {
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-info',
                            'data' => $tip,
                            'id' => '',
                        );
                    }
                } else {
                    $tipData[$field['form']['name']][] = array(
                        'icon' => 'ui-icon-info',
                        'data' => $field['basic']['descrip'],
                        'id' => '',
                    );
                }
            }
            // 读取验证信息
            $validator = &$field['validator'];
            if(isset($validator['rule']) && 0 != count($validator['rule']))
            {
                $validator['rule'] = $this->makeRequiredAtFront($validator['rule']);
                foreach($validator['rule'] as $method => $val)
                {
                    if(isset($validator['message'][$method]) && '' != $validator['message'][$method])
                    {
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-alert',
                            'data' => $validator['message'][$method],
                            'id' => 'validator-method-' . $method,
                        );
                    // 错误消息为空,需加载默认消息
                    } else {
                        $msg = '';
                        if(isset($validatorMessage[$method]))
                        {
                            $msg = $validatorMessage[$method];
                        }
                        $msg = $this->format($msg, $val);
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-alert',
                            'data' => $msg,
                            'id' => 'validator-' . $field['form']['name'] . '-' . $method,
                        );
                    }
                }
            }
        }
        return $tipData;
    }

    public function makeRequiredAtFront($rule)
    {
        // 将必填项放在数组第一位
        if(array_key_exists('required', $rule))
        {
            $tmpArr = array(
                'required' => true,
            );
            unset($rule['required']);
            $rule = $tmpArr + $rule;
        }
        return $rule;
    }

    /**
     * 模拟jquery.format转换数据
     * @param string $data
     * @param array $repalce
     * @return string
     * @todo 优化
     */
    public function format($data, $repalce)
    {
        $pos = strpos($data, '{0}');
        if(false !== $pos)
        {
            $repalce = Qwin::run('-arr')->set($repalce);
            $search = array();
            $count = count($repalce);
            for($i = 0;$i < $count; $i++)
            {
                $search[$i] = '{' . $i . '}';
            }
            $data = str_replace($search, $repalce, $data);
        }
        return $data;
    }

    public function metadataToModel($meta, $model)
    {
        $config = Qwin::run('-ini')->getConfig();
        // 设置数据表
        $model->setTableName($config['db']['prefix'] . $meta['db']['table']);
         // 数据库查询的字段数组
        $fieldList = $this->getSettingList($meta['field'], 'isSqlQuery');
        foreach($fieldList as $val)
        {
            $model->hasColumn($val);
        }
        return $model;
    }

    public function getQuery($set)
    {
        $ini = Qwin::run('-ini');
        $metaClassName = $ini->getClassName('Metadata', $set);
        $ctrlerClassName = $ini->getClassName('Controller', $set);
        $modelClassName = $ini->getClassName('Model', $set);
        $meta = $this->loadMetadataToMetaExt($metaClassName, $modelClassName);
        // 加载主模型类
        $model = Qwin::run($modelClassName);
        $this->metadataToModel($meta, $model);

        // 加载关联模型,元数据
        $this->loadRelatedData($meta['model']);
        // 获取模型类名称
        $query = $this->connectModel($modelClassName, $meta['model']);
        return $query;
    }

    /**
     * 加载元数据到metaExt数组中
     *
     * @todo 控制器的语言
     */
    public function loadMetadataToMetaExt($metaClassName, $modelClassName)
    {
        if(!isset($this->metaExt[$modelClassName]))
        {
            $this->metaExt[$modelClassName] = Qwin::run($metaClassName)->defaultMetadata();
            // 语言转换
            $this->metaExt[$modelClassName] = $this->convertLang($this->metaExt[$modelClassName], Qwin::run('-c')->lang, true);
        }
        return $this->metaExt[$modelClassName];
    }

    /**
     * 将数据库类的二维数组转换为表单资源数组
     * @param array $data 从数据库或是缓存文件获取的数组
     * @param string/int $key 第一个键名,作为资源的键名
     * @param string/int $key2 第二个键名,作为资源值的键名
     * @return array 表单资源数组
     * @todo 以conver开头的方法,可能会出现冲突
     */
    public function convertDbDataToFormResource($data, $key, $key2)
    {
        $resource = array();
        foreach($data as  $row)
        {
            $resource[$row[$key]] = $row[$key2];
        }
        return $resource;
    }

    function createLayoutArr($fieldArr, $col = 2)
    {
        $layoutArr = array();
        $x = 0;
        $y = -1;
        $tmpCol = $col - 1;
        $banType = array('hidden', 'custom');
        foreach($fieldArr as $field)
        {
            // 隐藏域不占空间
            if(in_array($field['form']['_type'], $banType))
            {
                continue;
            }
            // 初始化数组
            if(0 == $x)
            {
                $y++;
                $layoutArr[$y] = array();
            }
            $layoutArr[$y][$x] = $field['form']['name'];
            $tmpCol == $x++ && $x = 0;
        }
        return $layoutArr;
    }
}
