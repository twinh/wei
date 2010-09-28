<?php
/**
 * Execute
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
 * @subpackage  Padb
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-25 11:02:00
 */

class Qwin_Padb_Execute
{
    /**
     * Qwin_Padb_Connection连接对象
     * @var object
     */
    protected $_conn;

    /**
     * 数据库根目录
     */
    protected $_root;

    /**
     * 当前数据库目录
     * @var string
     */
    protected $_database;

    /**
     * 错误代码,与Qwin_Padb_Error中的errorMap对应
     * @var int
     */
    protected $_errorCode = 0;

    /**
     * 错误参数
     * @var array
     */
    protected $_errorParam = array();

    /**
     * 原始数据
     * @var array
     */
    protected $_rawData = array();

    protected $_tablePostfix = '_data.php';

    /**
     * 查询语句的类型
     * @var int
     */
    protected $_type;

    public function  __construct(Qwin_Padb_Connection $conn)
    {
        $this->_conn = $conn;
        $this->_root = $conn->getRoot();
    }

    /**
     * 根据参数配置,对数据库进行操作
     *
     * @param array $q
     * @return array|false 查询结果
     * @todo 如何规范$q的参数.
     */
    public function execute($q)
    {
        switch($q['type'])
        {
            case Qwin_Padb::METHOD_SELECT:
                $result = $this->executeSelect($q);
                break;

            case Qwin_Padb::METHOD_UPDATE:
                $result = $this->executeUpdate($q);
                break;

            case Qwin_Padb::METHOD_INSERT:
                $result = $this->executeInsert($q);
                break;

            case Qwin_Padb::METHOD_DELETE:
                $result = $this->executeDelete($q);
                break;

            case Qwin_Padb::METHOD_OTHER:
                switch($q['param'][0])
                {
                    case 'selectDatabase':
                        $result = $this->selectDatabase($q['param'][1]);
                        break;

                    case 'showDatabase':
                        $result = $this->showDatabase();
                        break;

                    case 'showTable':
                        $result = $this->showTable($q['param'][1]);
                        break;

                    case 'createDatabase':
                        $result = $this->createDatabase($q['param'][1]);
                        break;

                    case 'createTable':
                        $result = $this->createTable($q['param'][1], $q['param'][2], $q['param'][3]);
                        break;

                    default:
                        $this->_errorCode = 100;
                        $this->_errorParam = array($q['param'][0]);
                        $result = false;
                        break;
                }
                break;

            default:
                $this->_errorCode = 102;
                $this->_errorParam = array($q['type']);
                $result = false;
                break;
        }
        return $result;
    }

    public function createDatabase($database)
    {
        $dir = $this->_root. '/' . $database;
        if(is_dir($dir))
        {
            $this->_errorCode = 115;
            $this->_errorParam = array($database);
            return false;
        }
        if(false == mkdir($dir))
        {
            $this->_errorCode = 116;
            $this->_errorParam = array($database);
            return false;
        }
        return true;
    }

    public function executeSelect($q)
    {
        $rowData = $this->_getRowData($q['from']);
        if(false === $rowData)
        {
            return false;
        }
        
        $data = $rowData['data'];
        $index = $rowData['index'];
        $schema = $rowData['schema'];
        
        // 字段和键名的对应关系数组 array('field' => 'number');
        $fieldNumMap = array_flip($schema[Qwin_Padb::SCHEMA_FIELD]);

        // 过滤
        $data = $this->_executeWhere($data, $fieldNumMap, $q['where'], $q['from']);
        if(false === $data)
        {
            return false;
        }

        // 排序
        $data = $this->_executeOrderBy($data, $fieldNumMap, $q['orderBy'], $q['from']);
        if(false === $data)
        {
            return false;
        }

        // 限制
        $data = $this->_executeLimit($data, $q['offset'], $q['limit']);

        // select
        $data = $this->_executeSelect($data, $fieldNumMap, $q['select'], $q['from']);
        if(false === $data)
        {
            return false;
        }

        return $data;
    }

    protected function _getRowData($table)
    {
        // 读取数据表数据
        if(!$this->isTableExist($table))
        {
            $this->_errorCode = 112;
            $this->_errorParam = array($table);
            return false;
        }

        // 数据缓存
        if(!isset($this->_rawData[$table]))
        {
            $filePart = $this->_root . '/' . $this->_database . '/' . $table;
            $this->_rawData[$table] = array(
                'data' => require_once $filePart . '_data.php',
                'index' => require_once $filePart . '_index.php',
                'schema' => require_once $filePart . '_schema.php',
            );
        }
        return $this->_rawData[$table];
    }

    public function executeDelete($q)
    {
        $rowData = $this->_getRowData($q['from']);
        if(false === $rowData)
        {
            return false;
        }

        $data = $rowData['data'];
        $index = $rowData['index'];
        $schema = $rowData['schema'];

        // 字段和键名的对应关系数组 array('field' => 'number');
        $fieldNumMap = array_flip($schema[Qwin_Padb::SCHEMA_FIELD]);

        // 过滤
        $data = $this->_executeWhere($data, $fieldNumMap, $q['where'], $q['from']);
        if(false === $data)
        {
            return false;
        }

        $data = array_diff_key($rowData['data'], $data);
        return $this->_updateDataFile($data, $q['from']);
    }

    public function executeUpdate($q)
    {
        $rowData = $this->_getRowData($q['from']);
        if(false === $rowData)
        {
            return false;
        }

        $data = $rowData['data'];
        $index = $rowData['index'];
        $schema = $rowData['schema'];

        // 字段和键名的对应关系数组 array('field' => 'number');
        $fieldNumMap = array_flip($schema[Qwin_Padb::SCHEMA_FIELD]);

        // 过滤
        $data = $this->_executeWhere($data, $fieldNumMap, $q['where'], $q['from']);
        if(false === $data)
        {
            return false;
        }

        foreach($q['set'] as $set)
        {
            $field = $set[0];
            if(!isset($fieldNumMap[$field]))
            {
                $this->_errorCode = 113;
                $this->_errorParam = array($field, $q['from']);
                return false;
            }
            foreach($data as $key => $row)
            {
                $temp = $set;
                if('?' == $temp[1])
                {
                    $row[$fieldNumMap[$field]] = $temp[2];
                } else {
                    if(isset($fieldNumMap[$temp[1][0]]))
                    {
                        $temp[1][0] = $row[$fieldNumMap[$temp[1][0]]];
                    }
                    if(isset($fieldNumMap[$temp[1][2]]))
                    {
                        $temp[1][2] = $row[$fieldNumMap[$temp[1][2]]];
                    }
                    !is_int($temp[1][0]) && $temp[1][0] = "'" . $temp[1][0] . "'";
                    !is_int($temp[1][2]) && $temp[1][2] = "'" . $temp[1][2] . "'";
                    $eval = 'return ' . $temp[1][0] . ' ' . $temp[1][1] . ' ' . $temp[1][2] . ';';
                    $row[$fieldNumMap[$field]] = eval($eval);
                }
                $data[$key] = $row;
            }
        }
        $this->_updateDataFile($data, $q['from']);
        return $data;
    }

    public function executeInsert($q)
    {
        $rowData = $this->_getRowData($q['from']);
        if(false === $rowData)
        {
            return false;
        }

        $data = $rowData['data'];
        $index = $rowData['index'];
        $schema = $rowData['schema'];

        // 字段和键名的对应关系数组 array('field' => 'number');
        $fieldNumMap = array_flip($schema[Qwin_Padb::SCHEMA_FIELD]);

        $newRow = array_pad(array(), count($fieldNumMap), null);
        foreach($q['value'] as $value)
        {
            if(!isset($fieldNumMap[$value[0]]))
            {
                $this->_errorCode = 113;
                $this->_errorParam = array($value[0], $q['from']);
                return false;
            }
            foreach($newRow as $key => $value2)
            {
                if($key == $fieldNumMap[$value[0]])
                {
                    $newRow[$key] = $value[1];
                }
            }
        }
       
        // 判断主键是否重复
        $primaryKeyValue = $newRow[$schema[Qwin_Padb::SCHEMA_PRIMARY_KEY]];
        if(isset($data[$primaryKeyValue]))
        {
            $this->_errorCode = 114;
            $this->_errorParam = array($primaryKeyValue);
            return false;
        }

        $data[$primaryKeyValue] = $newRow;
        return $this->_updateDataFile($data, $q['from']);
    }

    public function createTable($name, $fieldSet, $option)
    {
        if(true == $this->isTableExist($name))
        {
            $this->_errorParam = array($name);
            $this->_errorCode = 117;
            return false;
        }

        $blankContent = "<?php\r\nreturn array();";
        $filePart = $this->_root . '/' . $this->_database . '/' . $name . '_';

        $dataFile = $filePart . 'data.php';
        file_put_contents($dataFile, $blankContent);

        $indexFile = $filePart . 'index.php';
        file_put_contents($indexFile, $blankContent);

        $schemaFile = $filePart . 'schema.php';
        $schemaContent = array(
            Qwin_Padb::SCHEMA_FIELD => array_keys($fieldSet),
            Qwin_Padb::SCHEMA_FIELD_SETTING => $fieldSet,
            Qwin_Padb::SCHEMA_PRIMARY_KEY => $option['primary'],
        );
        $schemaContent = "<?php\r\nreturn " . Qwin_Padb_Array::decode($schemaContent) . ";";
        file_put_contents($schemaFile, $schemaContent);

        return true;
    }

    protected function _updateDataFile($data, $table)
    {
        $content = Qwin_Padb_Array::decode($data);
        $content = "<?php\r\nreturn " . $content . ";";
        file_put_contents($this->_root . '/' . $this->_database . '/' . $table . '_data.php', $content);
        return true;
    }

    /**
     * 执行select查询
     *
     * @param array $data 从数据库取出的数据
     * @param array $fieldNumMap 字段和序号的对应列表
     * @param array $select 显示的字段
     * @param string $table 数据表的名称,用于提示错误信息
     * @return array 经过过滤的数据
     * @todo 数字键名和字段键名
     */
    protected function _executeSelect($data, $fieldNumMap, $select, $table)
    {
        $selectField = array();
        $selectKeyField = array();
        // 全部显示
        if('*' == $select[0])
        {
            $selectField = array_values($fieldNumMap);
            $selectKeyField = array_keys($fieldNumMap);
        } else {
            foreach($select as $field)
            {
                if(!isset($fieldNumMap[$field]))
                {
                    $this->_errorCode = 113;
                    $this->_errorParam = array($field, $table);
                    return false;
                }
                $selectField[] = $fieldNumMap[$field];
                $selectKeyField[] = $field;
            }
        }

        foreach($data as $key => $row)
        {
            $data[$key] = array_combine($selectKeyField, array_intersect_key($row, $selectField));
        }
        return $data;
    }

    protected function _executeLimit($data, $offset, $limit)
    {
        return array_slice($data, $offset - 1, $limit);
    }

    /**
     * 执行排序查询
     *
     * @param array $data 从数据库取出的数据
     * @param array $fieldNumMap 字段和序号的对应列表
     * @param array $condition 过滤条件
     * @param string $table 数据表的名称,用于提示错误信息
     * @return array 经过过滤的数据
     * @todo 对多个排序的支持,只有最后一个排序有效
     */
    protected function _executeOrderBy($data, $fieldNumMap, $condition, $table)
    {
        $condition = array_reverse($condition);
        foreach($condition as $order)
        {
            if(!isset($fieldNumMap[$order[0]]))
            {
                $this->_errorCode = 113;
                $this->_errorParam = array($order[0], $table);
                return false;
            }
            $data = Qwin_Padb_Array::orderBy($data, $fieldNumMap[$order[0]], $order[1]);
        }
        return $data;
    }

    /**
     * 执行过滤查询
     *
     * @param array $data 从数据库取出的数据
     * @param array $fieldNumMap 字段和序号的对应列表
     * @param array $condition 过滤条件
     * @param string $table 数据表的名称,用于提示错误信息
     * @return array 经过过滤的数据
     */
    protected function _executeWhere($data, $fieldNumMap, $condition, $table)
    {        
        // 交集的标识
        $isAnd = false;
        // 并集的标识
        $isOr = false;
        foreach($condition as $where)
        {
            // 筛选数据或设置数据合并的标识
            if(is_array($where))
            {
                if(!isset($fieldNumMap[$where[0]]))
                {
                    $this->_errorCode = 113;
                    $this->_errorParam = array($where[0], $table);
                    return false;
                }
                $where[0] = $fieldNumMap[$where[0]];
                $filterData = $this->_filter($data, $where);
            } else {
                if('AND' == $where)
                {
                    $isAnd = true;
                } elseif('OR' == $where) {
                    $isOr = true;
                }
            }

            // 合并数据
            if(true == $isAnd)
            {
                $data = array_intersect($filterData, $data);
                $isAnd = false;
            } elseif(true == $isOr) {
                $data = $data + $filterData;
                $isOr = false;
            } else {
                $data = $filterData;
            }
        }
        return $data;
    }
    
    /**
     * 显示所有的数据库
     *
     * @return array 数据库列表
     */
    public function showDatabase()
    {
        $database = scandir($this->_root);
        if(isset($database[0]) && '.' == $database[0])
        {
            array_shift($database);
            array_shift($database);
        }
        return $database;
    }

    /**
     * 选择一个数据库
     *
     * @param string $database
     * @return boolen 是否选择成功
     */
    public function selectDatabase($database)
    {
        if(is_dir($this->_root . '/' . $database))
        {
            $this->_database = $database;
            return true;
        }
        $this->_errorCode = 110;
        $this->_errorParam = array($database);
        return false;
    }

    /**
     * 找出数据库所有的表
     *
     * @param string|null $database 数据库名称
     * @return array 表数组
     */
    public function showTable($database = null)
    {
        // 检查是否设置数据表
        if(null == $database && !isset($this->_database))
        {
            $this->_errorParam = array($database);
            $this->_errorCode = 111;
            return false;
        }

        // 设置新的数据表
        if(null != $database)
        {
            $result = $this->selectDatabase($database);
            if(false == $result)
            {
                return $result;
            }
        } else {
            $database = $this->_database;
        }

        // 找出数据表
        $result = array();
        $path = $this->_root . '/' . $database;
        $databaseList = scandir($path);

        foreach($databaseList as $value)
        {
            if('_data.php' == substr($value, -9))
            {
                $result[] = substr($value, 0, -9);
            }
        }
        return $result;
    }

    /**
     * 获取错误的信息
     *
     * @return string 错误的信息
     */
    public function getError()
    {
        // 保持错误信息参数不被污染
        $error = $this->_errorParam;
        array_unshift($error, Qwin_Padb_Error::getError($this->_errorCode));
        return call_user_func_array('sprintf', $error);
    }

    /**
     * 获取错误的代码
     *
     * @return int 错误的代码
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }


    /**
     * 根据查询配置筛选数据
     *
     * @param array $data 筛选的数据
     * @param array $where 查询配置
     * @return array 筛选过的数据
     */
    public function _filter($data, $where)
    {
        switch($where[1])
        {
            case '=':
                $data = $this->_filterEqData($data, $where[0], $where[2]);
                break;
            case '>':
                $data = $this->_filterGtData($data, $where[0], $where[2]);
                break;
            case '<':
                $data = $this->_filterLtData($data, $where[0], $where[2]);
                break;
            case '>=':
                $data = $this->_filterGeData($data, $where[0], $where[2]);
                break;
            case '<=':
                $data = $this->_filterLeData($data, $where[0], $where[2]);
                break;
            case '!=':
            case '<>':
                $data = $this->_filterNeData($data, $where[0], $where[2]);
                break;
        }
        return $data;
    }

    /**
     * 筛选出等于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterEqData($data, $field, $value)
    {
        foreach($data as $key => $row)
        {
            if($row[$field] != $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 筛选出大于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterGtData($data, $field, $value)
    {
        foreach($data as $key => $row)
        {
            if($row[$field] <= $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 筛选出大于等于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterGeData($data, $field, $value)
    {
        foreach($data as $key => $row)
        {
            if($row[$field] < $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 筛选出小于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterLtData($data, $field, $value)
    {
        foreach($data as $key => $row)
        {
            if($row[$field] > $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 筛选出小于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterLeData($data, $field, $value)
    {
        foreach($data as $key => &$row)
        {
            if($row[$field] > $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 筛选出不等于某值的数据
     *
     * @param array $data 从数据库取出的数据
     * @param int $field 键名编号
     * @param string|int $value 做比较的值
     * @return array 筛选后的数据
     */
    public function _filterNeData($data, $field, $value)
    {
        foreach($data as $key => &$row)
        {
            if($row[$field] == $value)
            {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public function isTableExist($table)
    {
        $fileList = array(
            $this->_root . '/' . $this->_database . '/' . $table . '_data.php',
            $this->_root . '/' . $this->_database . '/' . $table . '_index.php',
            $this->_root . '/' . $this->_database . '/' . $table . '_schema.php',
        );
        foreach($fileList as $file)
        {
            if(!file_exists($file))
            {
                return false;
            }
        }
        return true;
    }
}