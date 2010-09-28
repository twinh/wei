<?php
/**
 * Query
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
 * @since       2010-09-27 9:35:37
 */

class Qwin_Padb_Query
{
    /**
     * 解析类名称
     * @var string
     */
    protected $_parser = 'Qwin_Padb_Parser';

    /**
     * 执行查询类名称
     * @var string
     */
    protected $_executor = 'Qwin_Padb_Execute';

    /**
     * 查询配置数组
     * @var array
     */
    protected $_query = array(
        'type' => null,
        'select' => null,
        'where' => array(),
        'orderBy' => null,
        'offset' => null,
        'limit' => null,
        'param' => null,
        'set' => array(),
        'value' => array(),
    );

    public function __construct(Qwin_Padb_Connection $conn)
    {
        $this->_conn = $conn;
        $this->_root = $conn->getRoot();
        $this->_exe = new $this->_executor($conn);
    }

    /**
     * 构建显示数据库的查询
     *
     * @param boolen $execute 是否立刻执行查询
     * @return object 当前对象
     */
    public function showDatabase($execute = true)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_OTHER;
        $this->_query['param'] = array('showDatabase');

        if(true == $execute)
        {
            return $this->execute();
        }
        return $this;
    }
    
    /**
     * 构建选择数据库的查询
     *
     * @param string $database
     * @param boolen $execute 是否立刻执行查询
     * @return boolen 是否选择成功
     */
    public function selectDatabase($database, $execute = true)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_OTHER;
        $this->_query['param'] = array('selectDatabase', $database);

        if(true == $execute)
        {
            return $this->execute();
        }
        return $this;
    }

    /**
     * 找出数据库所有的表
     *
     * @param string|null $database 数据库名称
     * @param boolen $execute 是否立刻执行查询
     * @return array 表数组
     */
    public function showTable($database = null, $execute = true)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_OTHER;
        $this->_query['param'] = array('showTable', $database);

        if(true == $execute)
        {
            return $this->execute();
        }
        return $this;
    }

    /**
     * 创建一个数据库
     *
     * @param string $database 数据库名称
     * @param boolen $execute 是否立刻执行查询
     * @return mixed 创建结果或当前对象
     */
    public function createDatabase($database, $execute = true)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_OTHER;
        $this->_query['param'] = array('createDatabase', $database);

        if(true == $execute)
        {
            return $this->execute();
        }
        return $this;
    }

    /**
     * 创建一个数据表
     *
     * @param string $name 数据表名称
     * @param array $fieldSet 字段配置
     * @param string|null $primaryKey 主键类型
     * @param boolen $execute 是否立即执行查询
     * @return mixed 创建结果或当前对象
     */
    public function createTable($name, $fieldSet, $option = null, $execute = true)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_OTHER;
        $this->_query['param'] = array('createTable', $name, $fieldSet, $option);

        if(true == $execute)
        {
            return $this->execute();
        }
        return $this;
    }

    /**
     * 获取错误代码
     *
     * @return int 错误代码
     */
    public function getErrorCode()
    {
        return $this->_exe->getErrorCode();
    }

    /**
     * 获取错误信息
     *
     * @return string 错误信息
     */
    public function getError()
    {
        return $this->_exe->getError();
    }

    /**
     * 设置删除表操作
     *
     * @param string $table 数据表名称
     * @return object 当前对象
     */
    public function delete($table)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_DELETE;
        $this->_query['from'] = $table;
        return $this;
    }

    /**
     * 设置插入表操作
     *
     * @param string $table 数据表名称
     * @return object 当前对象
     */
    public function insert($table)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_INSERT;
        $this->_query['from'] = $table;
        return $this;
    }

    /**
     * 设置插入操作的值
     *
     * @param string $field 字段名称
     * @param string $value 该字段的值
     * @return object 当前对象
     */
    public function value($field, $value)
    {
        $this->_query['value'][$field] = array($field, $value);
        return $this;
    }

    /**
     * 清除已经设置的插入操作的值
     *
     * @param string $field 字段名称
     * @return object 当前对象
     */
    public function clearValue($field)
    {
        if(isset($this->_query['value'][$field]))
        {
            unset($this->_query['value'][$field]);
        }
        return $this;
    }

    /**
     * 设置更新操作
     *
     * @param string $table 数据表名称
     * @return object 当前对象
     */
    public function update($table)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_UPDATE;
        $this->_query['from'] = $table;
        return $this;
    }

    /**
     * 设置更新操作的值
     *
     * @param string $field 字段名称
     * @param string $equation 算式,例如 ?, field + 1
     * @param string $value 该字段的值
     * @return object 当前对象
     */
    public function set($field, $equation, $value = null)
    {
        $this->_query['set'][$field] = array($field, $equation, $value);
        return $this;
    }

    /**
     * 清除更新操作的值
     *
     * @param string $field 字段名称
     * @return object 当前对象
     */
    public function clearSet($field)
    {
        if(isset($this->_query['set'][$field]))
        {
            unset($this->_query['set'][$field]);
        }
        return $this;
    }

    /**
     * 设置选择操作
     *
     * @param string $data 选择的字段域,多个域由,隔开
     * @return object 当前对象
     */
    public function select($data)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_SELECT;
        // 清空原有数组
        $this->_query['select'] = array();
        $this->_query['select'][] = $data;
        return $this;
    }

    /**
     * 增加选择操作的字段
     *
     * @param string $data 选择的字段域,多个域由,隔开
     * @return object 当前对象
     */
    public function andSelect($data)
    {
        $this->_query['type'] = Qwin_Padb::METHOD_SELECT;
        $this->_query['select'][] = $data;
        return $this;
    }

    /**
     * 设置选择操作的数据表名称
     *
     * @param string $table 数据表名称
     * @return object 当前对象
     */
    public function from($table)
    {
        $this->_query['from'] = $table;
        return $this;
    }

    /**
     * 增加选择操作的数据表名称
     *
     * @param string $table 数据表名称
     * @return object 当前对象
     */
    public function addFrom($table)
    {
        $this->_query['from'] .= $table;
        return $this;
    }

    /**
     * 设置查询条件
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     */
    public function where($condition)
    {
        $this->_query['where'] = array();
        $this->_query['where'][] = $condition;
        return $this;
    }

    /**
     * 增加AND类型查询条件
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     */
    public function andWhere($condition)
    {
        $this->_query['where'][] = 'AND';
        $this->_query['where'][] = $condition;
        return $this;
    }

    /**
     * 增加OR类型查询条件
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     */
    public function orWhere($condition)
    {
        $this->_query['where'][] = 'OR';
        $this->_query['where'][] = $condition;
        return $this;
    }

    /**
     * 增加子查询
     *
     * @return object 当前对象
     * @todo todo
     */
    public function addSubWhere()
    {
        return $this;
    }

    /**
     * 增加父查询
     *
     * @return object 当前对象
     * @todo todo
     */
    public function addParentWhere()
    {
        return $this;
    }

    /**
     * 设置IN类型查询
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     * @todo todo
     */
    public function whereIn($condition)
    {
        return $this;
    }

    /**
     * 设置NOT, IN类型查询
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     * @todo todo
     */
    public function whereNotIn($condition)
    {
        return $this;
    }

    /**
     * 设置AND, IN类型查询
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     * @todo todo
     */
    public function andWhereIn($condition)
    {
        return $this;
    }

    /**
     * 设置AND, NOT, IN类型查询
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     * @todo todo
     */
    public function andWhereNotIn($condition)
    {
        return $this;
    }

    /**
     * 增加AND类型查询条件
     *
     * @param string $condition 查询条件
     * @return object 当前对象
     */
    public function addWhere($condition)
    {
        return $this->andWhere($condition);
    }

    /**
     * 设置排序操作
     *
     * @param string $condition 排序键名和类型,例如field DESC,field2 ASC
     * @return object 当前对象
     */
    public function orderBy($condition)
    {
        $this->_query['orderBy'] = $condition;
        return $this;
    }

    /**
     * 添加排序操作
     *
     * @param string $condition 排序条件
     * @return object 当前对象
     */
    public function addOrderBy($condition)
    {
        $this->_query['orderBy'] .= $condition;
        return $this;
    }

    /**
     * 设置限制操作
     *
     * @param int $num 限制个数
     * @return object 当前对象
     */
    public function limit($num)
    {
        $this->_query['limit'] = $num;
        return $this;
    }

    public function offset($num)
    {
        $this->_query['offset'] = $num;
        return $this;
    }

    /**
     * 解析查询,返回查询结果
     *
     * @return mixed
     */
    public function execute()
    {
        switch($this->_query['type'])
        {
            case Qwin_Padb::METHOD_SELECT:
            case Qwin_Padb::METHOD_INSERT:
            case Qwin_Padb::METHOD_UPDATE:
            case Qwin_Padb::METHOD_DELETE:
                $parser = $this->_parser;
                $parser = $parser::getInstance();
                $this->_standardQuery = $parser->parse($this->_query);
                break;

            case Qwin_Padb::METHOD_OTHER:
                $this->_standardQuery = $this->_query;
                if('createTable' == $this->_query['param'][0])
                {
                    $parser = $this->_parser;
                    $this->_standardQuery = $parser::getInstance()->parseCreateTable($this->_standardQuery);
                }
                break;

            default:
                return array();
        }
        return $this->_exe->execute($this->_standardQuery);
    }
}
/*
$queryStmt = array(
    array(
        'showDatabase',
        // 无参数
    ),
    array(
        'selectDatabase',
        'database',
    ),
    array(
        'showTable',
        'database/null'
    ),
    array(
        'type' => 'select',
        'select' => array(
            'table.field', 'field',
        ),
        'from' => 'table',
        'where' => array(
            array(
                array('id', '=', '1'),
                'OR',
                array('id', '=', '2'),
            ),
            'AND',
            array('type', '=', '2'),
        ),
        'orderBy' => array(
            array('table.field', 'type'),
            array('table.field', 'type'),
        ),
        'offset' => 'num',
        'limit' => 'num',
    ),
);
*/