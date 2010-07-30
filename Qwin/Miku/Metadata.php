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
 * @subpackage  Miku
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Miku_Metadata extends Qwin_Metadata
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

    function  __construct() {
        //p(get_class_methods($this));exit;
    }

    /**
     * 当配置类不存在的时候,使用的配置数组来自该方法
     *
     * @return array
     */
    public function defaultMetadata()
    {
        return array();
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
    public function converSingleData(&$set, $action, $row, $isUrlQuery = false)
    {
        // TODO
        $str = Qwin::run('-str');
        $self = Qwin::run('-c');
        $action = strtolower($action);
        $row_copy = $row;

        // fixed 2010-06-27 添加操作时,清空其他主键的值
        // TODO 整理
        $urlAction = Qwin::run('-gpc')->g('action');

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
                $row[$field] = NULL;
            }
            $conver = $str->set($val['conversion'][$action]);
            $name = $val['form']['name'];
            $str->set($row[$name]);
            $str->set($row_copy[$name]);
            if(is_array($conver))
            {
                // 判断方法/函数是否存在
                if(is_array($conver[0]))
                {
                    /**
                     * $conver[0][0]为类名,尝试加载该类
                     * @todo 静态调用和动态调用
                     */
                    //Qwin::load($conver[0][0]);
                    if(!is_object($conver[0][0]))
                    {
                        $conver[0][0] = Qwin::run($conver[0][0]);
                    }
                    if(!method_exists($conver[0][0], $conver[0][1]))
                    {
                        continue;
                    }
                } else {
                    if(!function_exists($conver[0]))
                    {
                        continue;
                    }
                }
                // 第一个是方法/函数名
                $function = $conver[0];
                // 转换数据
                $conver[0] = $row[$name];
                $row[$name] = call_user_func_array($function, $conver);
            }// else {
                $method_name = str_replace(array('_', '-'), '', 'conver' . $action . $name);
                if(method_exists($self, $method_name))
                {
                    $row[$name] = call_user_func_array(
                        array($self, $method_name),
                        array($row[$name], $name, $row, $row_copy)
                    );
                }
            //}
            // 将转换结果加入元数据中,方便引用
            $set[$field]['form']['_value'] = $row[$name];
            // 转换 url
            if(true == $isUrlQuery && isset($val['list']['isUrlQuery']) && true == $val['list']['isUrlQuery'])
            {
                $row[$name] = '<a href="' . url(array($self->__query['namespace'], $self->__query['module'], $self->__query['controller']), array('searchField' => $name, 'searchValue' => $row_copy[$name])) . '">' . $row[$name] . '</a>';
            }
        }
        return $row;
    }

    /**
     * 数据转换,用于 list 等三位数组的数据
     *
     * @param array $set 配置数组的字段子数组 $this->__meta['field']
     * @param string $action Action 的名称,一般为 list
     * @praam array $data 三维数组,一般是从数据库取出的数组
     */
    public function converMultiData($set, $action, $data, $isUrlQuery = true)
    {
        foreach($data as &$row)
        {
            $row = $this->converSingleData($set, $action, $row, $isUrlQuery);
        }
        return $data;
    }

    public function setLang($lang = null)
    {
        if(null == $lang)
        {
            if(empty($this->lang))
            {
                $config = Qwin::run('-ini')->getConfig();
                $this->lang = $config['i18n']['language'];
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
    
    /**
     * 连接和元数据和模型,方便控制器通过模型取数据
     * @param array $mainModelName 主模型名称
     * @param array $mainModelMetadata 主元数据的模型键,如$meta['model']
     * @return object
     */
    public function connectModel($mainModelName, $mainModelMetadata)
    {
        $config = Qwin::run('-ini')->getConfig();
        $modelObject = Qwin::run($mainModelName);

        $query = Doctrine_Query::create()
           ->from($mainModelName . ' t');
        
        foreach($mainModelMetadata as $model)
        {
            // 设置数据表
            $this->_modelObjct[$model['name']]->setTableName($config['db']['prefix'] . $this->metaExt[$model['name']]['db']['table']);
            // 设置字段关系
            $queryField = $this->getSettingList($this->metaExt[$model['name']]['field'], 'isSqlQuery');
            foreach($queryField as $field)
            {
                $this->_modelObjct[$model['name']]->hasColumn($field);
            }
            // 设置模型关系
            /**
             * @todo $model['type'] 合法性检查
             * @todo asName 可选
             */
            call_user_func(
                array($modelObject, 'hasOne'),
                $model['name'] . ' as ' . $model['asName'],
                    array(
                        'local' => $model['local'],
                        'foreign' => $model['foreign']
                    )
            );
            $query->leftJoin('t.' . $model['asName']);
        }
        if(!isset($this->query[$mainModelName]))
        {
            $this->query[$mainModelName] = $query;
        }
        return $query;
    }

    /**
     * 将关联控制器的元数据加入到主控制器的元数据中,转换id
     * @param array $meta 配置元数据数组
     * @param array $dbData
     * @todo  是否需要销毁数组
     */
    public function connetMetadata(&$meta)
    {
        $str = Qwin::run('Qwin_Converter_String');
        $c = Qwin::run('-c');
        foreach($meta['field'] as $name =>$fieldMeta)
        {
            // 删除只读项
            if('Edit' == $c->__query['action'] && isset($fieldMeta['list']['isReadonly']) && true == $fieldMeta['list']['isReadonly'])
            {
                unset($meta['field'][$name]);
                continue;
            }
            if(!isset($fieldMeta['form']['id']))
            {
                $meta['field'][$name]['form']['id'] = str_replace('_', '-', $fieldMeta['form']['name']);
            }
        }
        foreach($meta['model'] as $model)
        {
            $tmpMeta = array();
            $this->_modelPrimaryKey[] = $model['asName'] . '_' . $model['local'];
            $this->_foreignKey[] = $model['asName'] . '_' . $model['foreign'];
            foreach($this->metaExt[$model['name']]['field'] as $name => $fieldMeta)
            {
                // 跳过只读项
                if('Edit' == $c->__query['action'] && isset($fieldMeta['list']['isReadonly']) && true == $fieldMeta['list']['isReadonly'])
                {
                    continue;
                }
                // 存储原来的名称
                $fieldMeta['form']['_old_name'] = $fieldMeta['form']['name'];
                // 利用asName作为前缀,以后可通过asName加密
                $fieldMeta['form']['name'] = $model['asName'] . '_' . $fieldMeta['form']['name'];
                $fieldMeta['form']['id'] = str_replace('_', '-', $fieldMeta['form']['name']);
                $tmpMeta[$fieldMeta['form']['name']] = $fieldMeta;
            }
            $meta['field'] += $tmpMeta;
        }
        return $meta;
    }

    public function converDataToSingle($data)
    {
        if(isset($data[0]))
        {
            foreach($data as $key => $row)
            {
                $data[$key] = $this->converDataToSingle($row);
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
     * 填充数据到模型中
     * 如果未设置某字段的值,表示不更改
     * 如果设置了某字段的值,且为null,对应数据库的NULL
     * 其他值对应其他值.
     * @todo isset == false 的变量,如何知道是否为false.
     */
    public function fillData($meta, $query, $data)
    {
        $gpc = Qwin::run('-gpc');
        $dbData = array();
        foreach($meta['field'] as $fieldName => $field)
        {
            if(isset($data[$fieldName]))
            {
                if('NULL' == $data[$fieldName])
                {
                    $data[$fieldName] = NULL;
                }
                // 主元数据
                if(!isset($field['form']['_old_name']))
                {
                    $field['list']['isSqlField'] && $query[$fieldName] = $data[$fieldName];
                // 关联元数据
                } else {
                    //p($this->_foreignKey);
                    // 强制外键的值和主键一致,主要用于Edit操作
                    if(in_array($fieldName, $this->_foreignKey))
                    {
                        $data[$fieldName] = $data[$meta['db']['primaryKey']];
                    }
                    $modelName = str_replace('_' .$field['form']['_old_name'], '', $fieldName);
                    // 一对一,有时另一个表没有对应数据,需要初始化该对象
                    // TODO 使用 fromArray
                    if(!isset($query[$modelName]))
                    {
                        foreach($meta['model'] as $model)
                        {
                            if($modelName == $model['asName'])
                            {
                                $query[$modelName] = new $model['name'];
                            }
                        }
                    }
                    $query[$modelName][$field['form']['_old_name']] = $data[$fieldName];
                }
            }
        }
        return $query;
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
            if(isset($field['validation']['rule']))
            {
                $field['validation']['rule'] = $this->makeRequiredAtFront($field['validation']['rule']);
                // 转换为数组
                $arr->set($field['validation']['rule']);
                foreach($field['validation']['rule'] as $method => $param)
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
        $file = scandir(ROOT_PATH . '/App');
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
     * 翻译元数据中的名称
     * @param array $set 元数据
     * @param array $lang 语言数据
     * @return array 元数据
     */
    public function converLang($set, $lang, $addTitle = false)
    {
        if(!isset($set['field']))
        {
            return $set;
        }
        foreach($set['field'] as &$val)
        {
            // 转换字段名称
            isset($lang[$val['basic']['title']])
            && $val['basic']['title'] = $lang[$val['basic']['title']];

            // 转换分组名称
            if(isset($val['basic']['group']) && empty($val['basic']['group']))
            {
                $val['basic']['group'] = 'LBL_GROUP_BASIC_DATA';
            }
            isset($lang[$val['basic']['group']])
            && $val['basic']['group'] = $lang[$val['basic']['group']];

            // TODO 转换描述,不应该给转换数据结构
            if(isset($val['basic']['descrip']) && !empty($val['basic']['descrip']))
            {
                if(!is_array($val['basic']['descrip']))
                {
                    $val['basic']['descrip'] = array($val['basic']['descrip']);
                }
                foreach($val['basic']['descrip'] as &$descrip)
                {
                    isset($lang[$descrip]) && $descrip = $lang[$descrip];
                }
            }

            // 添加模块标题在字段标题之前
            //true == $addTitle
            //&& isset($lang[$set['page']['title']])
            //&& $val['basic']['title'] = $lang[$set['page']['title']] . '- ' . $val['basic']['title'];
        }
        isset($lang[$set['page']['title']])
        && $set['page']['title'] = $lang[$set['page']['title']];
        return $set;
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

    // 获得模型指定类型的值
    public function getSettingValue($set, $type)
    {
        $data = array();
        foreach($set as $val)
        {
            $data[$val['form']['name']] = Qwin::run('-str')->set($val[$type[0]][$type[1]]);
        }
        return $data;
    }

    /*
    *  根据提供的2, 3参数,在模型配置数组中, 筛选出合适的 list 属性的值
    *
    * @param $set array 模型配置数组的field属性数组
    * @param $allow_attr array 搜索list中值为true的属性
    * @param $ban_attr array 搜索list中值为false的属性
    * @todo 直接提供 temp_arr 参数比较
    */
    public function getSettingList($set, $allow_attr = array(), $ban_attr = array())
    {
        $allow_attr = Qwin::run('-arr')->set($allow_attr);
        $ban_attr = Qwin::run('-arr')->set($ban_attr);

        // 根据提供的 allow, ban 属性列表,生成比较的数组
        $temp_arr = array();
        foreach($allow_attr as $val)
        {
            $temp_arr[$val] = true;
        }
        foreach($ban_attr as $val)
        {
            $temp_arr[$val] = false;
        }
        $data = array();
        foreach($set as $val)
        {
            if(true == Qwin::run('-arr')->isSubset($temp_arr, $val['attr']))
            {
                $data[$val['form']['name']] = $val['form']['name'];
                //$data[] = $val['form']['name'];
            }
        }
        return $data;
    }

    /**
     * 根据代码转换出通用分类对应的值
     *
     * @param int $code 分类代码值
     * @param string $name 分类名称
     * @return string 分类的值
     */
    function converCommonClass($code, $name, $lang = null)
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
     * 加载模型文件
     *
     * @param string $controller 控制器名称
     * @param string $namespace 命名空间
     */
    public function loadSettingFile($controller, $namespace = 'default')
    {
        require_once ROOT_PATH . Qwin::run('-str')->toPathSeparator('\app\\' . $namespace . '\setting\\' . $controller . '.php');
        $name = 'Setting_' . $namespace . '_' . $controller;
        $class = new $name;
        return $class->setting();
    }

    /**
     * 自动生成顺序值
     *
     * @param string $table 数据表名称
     * @param string $field 顺序的字段名称
     * @param int $increment 顺序增量
     */
    public function getInitalOrder($table, $field = 'order', $increment = 5, $where = NULL)
    {
        $mainModelName = Qwin::run('-ini')->getClassName('Model', Qwin::run('-c')->__query);
        $query = $this->query[$mainModelName]
            ->select('Max(`' .  $field . '`) as max_order');
        if(NULL != $where)
        {
            call_user_func_array(array($query, 'where'), $where);
        }
        $data = $query
            ->fetchOne()
            ->toArray();
        null == $data['max_order'] && $data['max_order'] = 0;
        return null == $data['max_order'] ? $increment : $data['max_order'] + $increment;
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

    

    /**
     * 根据 order 从小到大排序
     *
     * @param array $field_arr $this->__meta['field']
     * @todo 转为QArray类的n维数组排序
     */
    public function orderSettingArr(&$field_arr)
    {
        $new_arr = array();
        foreach($field_arr as $key => $val)
        {
            $temp_arr[$key] = isset($val['basic']['order']) && is_numeric($val['basic']['order']) ? $val['basic']['order'] : 0;
        }
        // 倒序再排列,因为 asort 会使导致倒序
        $temp_arr = array_reverse($temp_arr);
        asort($temp_arr);
        foreach($temp_arr as $key => $val)
        {
            $new_arr[$key] = $field_arr[$key];
        }
        $field_arr = $new_arr;
        return $field_arr;
    }

    /**
     * 分组(主要用于 Add,Edit,Show)
     *
     * @todo custom 组
     */
    public function groupingSettingArr($field_arr)
    {
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
    }

    // TODO !!
    public function converUrlQuery2($data, $sql_data)
    {
        foreach($data as $key => $val)
        {
            if(isset($this->__meta['field'][$key]['list']['isUrlQuery']) && $this->__meta['field'][$key]['list']['isUrlQuery'] == true)
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
            $validation = &$field['validation'];
            if(isset($validation['rule']) && 0 != count($validation['rule']))
            {
                $validation['rule'] = $this->makeRequiredAtFront($validation['rule']);
                foreach($validation['rule'] as $method => $val)
                {
                    if(isset($validation['message'][$method]) && '' != $validation['message'][$method])
                    {
                        $tipData[$field['form']['name']][] = array(
                            'icon' => 'ui-icon-alert',
                            'data' => $validation['message'][$method],
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

    /**
     *
     * @param <type> $pk
     * @param <type> $id
     * @param <type> $query
     * @return <type>
     * @todo 优化, JQuery Button Creator
     */
    public function getOperationLink($pk, $id, $query)
    {
        $data = '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_EDIT') .'" href="' . url(array($query['namespace'], $query['module'], $query['controller'], 'Edit'), array($pk => $id)) . '"><span class="ui-icon ui-icon-tag"></span></a>';
        $data .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_SHOW') .'" href="' . url(array($query['namespace'], $query['module'], $query['controller'], 'Show'), array($pk => $id)) . '"><span class="ui-icon ui-icon-lightbulb"></span></a>';
        $data .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_COPY') .'" href="' . url(array($query['namespace'], $query['module'], $query['controller'], 'Add'), array($pk => $id)) . '"><span class="ui-icon ui-icon-transferthick-e-w"></span></a>';
        $data .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_DELETE') .'" href="' . url(array($query['namespace'], $query['module'], $query['controller'], 'Delete'), array($pk => $id)) . '" onclick="javascript:return confirm(Qwin.Lang.MSG_CONFIRM_TO_DELETE);"><span class="ui-icon ui-icon-closethick"></span></a>';
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
            $this->metaExt[$modelClassName] = $this->converLang($this->metaExt[$modelClassName], Qwin::run('-c')->lang, true);
        }
        return $this->metaExt[$modelClassName];
    }

    /**
     * 为$query增加排序,优先级为url > 元数据 > 主键
     * @param array $meta 配置元数据
     * @param object $query Dcotrine查询对象
     * @return object Dcotrine查询对象
     * @todo 字段间隔标志
     * @todo 关联模块的排序
     * @todo orderField 冲突 ?namespace=Admin&module=Member&controller=Member&orderField=username&action=JsonList&_search=false&nd=1275278100347&row=10&page=1&orderField=id&orderType=desc
     */
    public function addOrderToQuery($meta, $query)
    {
        $arr = Qwin::run('-arr');
        $gpc = Qwin::run('-gpc');
        //$alias = $query->getRootAlias() . '.';
        $alias = 't.';

        // 排序字段名和排序类型
        $urlOrderField = $gpc->g('urlOrderField');
        $urlOrderType = $gpc->g('urlOrderType');
        $orderField = $gpc->g('orderField');
        $orderType = strtoupper($gpc->g('orderType'));

        // 数据表字段的域
        $sqlField = $this->getSettingList($meta['field'], 'isSqlField');
        if(in_array($urlOrderField, $sqlField))
        {
            $urlOrderType = $arr->forceInArray($urlOrderType, array('DESC', 'ASC'));
            $query->orderBy($alias . $urlOrderField . ' ' .  $urlOrderType);
        } elseif(in_array($orderField, $sqlField)) {
            $orderType = $arr->forceInArray($orderType, array('DESC', 'ASC'));
            $query->orderBy($alias . $orderField . ' ' .  $orderType);
        } elseif(isset($meta['db']['order']) && !empty($meta['db']['order'])) {
            $orderTempArr = array();
            foreach($meta['db']['order'] as $fieldSet)
            {
                $fieldSet[1] = $arr->forceInArray($fieldSet[1], array('DESC', 'ASC'));
                $orderTempArr[] = $alias . $fieldSet[0] . ' ' . $fieldSet[1];
            }
            $query->orderBy(implode(', ', $orderTempArr));
        } else {
            $query->orderBy($alias . $meta['db']['primaryKey'] . ' DESC');
        }
        return $query;
    }

    public function addWhereToQuery($meta, $query)
    {
        $arr = Qwin::run('-arr');
        $gpc = Qwin::run('-gpc');
        $alias = 't.';
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

        $searchField = $gpc->g('searchField');
        $searchType = $gpc->g('searchType');
        $searchValue = $gpc->g('searchValue');

        // 数据表字段的域
        $sqlField = $this->getSettingList($meta['field'], 'isSqlField');        
        if(in_array($searchField, $sqlField))
        {
            $searchType = $arr->forceInArray($searchType, array_keys($searchTypeArr));
            $query->where($alias . $searchField . ' ' . $searchTypeArr[$searchType] . ' ?', $searchValue);
        } elseif(isset($meta['db']['where']) && !empty($meta['db']['where'])) {
            
        }
        //是’eq’,'ne’,'lt’,'le’,'gt’,'ge’,'bw’,'bn’,'in’,'ni’,'ew’,'en’,'cn’,'nc’，
        
        return $query;
    }

    /**
     * 将数据库类的二维数组转换为表单资源数组
     * @param array $data 从数据库或是缓存文件获取的数组
     * @param string/int $key 第一个键名,作为资源的键名
     * @param string/int $key2 第二个键名,作为资源值的键名
     * @return array 表单资源数组
     * @todo 以conver开头的方法,可能会出现冲突
     */
    public function converDbDataToFormResource($data, $key, $key2)
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
