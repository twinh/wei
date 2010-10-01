<?php
/**
 * Parser
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
 * @since       2010-09-25 16:59:24
 */

class Qwin_Padb_Parser
{
    /**
     * TODO 增加静默模式和异常模式
     * 静默模式下,当解析错误时,程序尝试使用默认值替换,例如排序类型的DESC,如果错误过分严重,或者经过替换后,语义变动大,那将直接抛出异常
     * 异常模式下,当解析错误时,程序直接抛出异常.
     */
    const SILEN_MODE        = 1;
    const EXCPTION_MODE     = 2;

    protected $_deafultSetting = array(
        'orderType' => 'DESC',
        'operator' => 'AND',
    );

    // 长操作符置于前方
    protected $_operator = array(
        '>=',
        '<=',
        '<>',
        '!=',
        '=',
        '>',
        '<',
        //'IS',
        //'LIKE',
        //'REGEXP',
        //'IN',
    );

    /**
     * 当前类的实例化对象
     * @var object
     */
    protected static $_instance;


    protected $_setOperator = array(
        '+',
        '-',
        '*',
        '/',
        '%',
        '.',
    );

    /**
     * 字段和表格的对应关系,用于检查字段是否存在
     * @var <type>
     */
    protected $_fieldMap = array(

    );

    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function parse(array $query)
    {
        foreach($query as $key => $part)
        {
            // parse不是关键字,防止死循环
            if('parse' != $key && method_exists($this, $key))
            {
                $part = $this->$key($part);
                $query[$key] = $part;
            }
        }
        return $query;
    }

    /**
     * 解析查询中的字段字符串为单独一个字段.
     *
     * @param array $field
     * @return array 由单独字段组成的数组
     */
    public function select($fieldList)
    {
        if(empty($fieldList))
        {
            return array('*');
        }

        $allField = array();
        foreach($fieldList as $field)
        {
            $field = explode(',', $field);
            foreach($field as $key)
            {
                $key = trim($key);
                // *表示查询所有的字段,直接返回
                if('*' == $key)
                {
                    return array('*');
                }
                if('' != $key)
                {
                    $allField[] = $key;
                }
            }
        }
        return $allField;
    }

    /**
     * 解析数据表名称
     *
     * @param array $table 数据表名称
     * @return array 数据表名称
     */
    public function from($table)
    {
        return trim($table);
    }

    /**
     * 解析查找语句
     *
     * @param string $condition 条件字符串
     * @return array 解析过的语句
     */
    public function where($condition)
    {
        $where = array();
        $count = count($condition);
        // 最后一个连接操作符号的位置
        $lastOpPosition = $count - 2;
        if(1 != ($count % 2))
        {
            return $where;
        }

        foreach($condition as $key => $value)
        {
            // 连接操作符
            if($key >= 1 && $key <= $lastOpPosition && 1 == $key % 2)
            {
                $value[0] = strtoupper($value[0]);
                if(!in_array($value[0], array('AND', 'OR')))
                {
                    throw new Qwin_Padb_Parser_Exception('The link operator should be "AND" or "OR".');
                    //$value = $this->_deafultSetting['operator'];
                }
            } else {
                $isPass = false;
                foreach($this->_operator as $operator)
                {
                    // 存在合法的操作符,对查询内容进行解析
                    $position = strpos($value[0], $operator);
                    if(false !== $position)
                    {
                        $field = trim(substr($value[0], 0, $position));
                        $value[0] = trim(substr($value[0], $position + strlen($operator)));
                        // TODO 多个参数
                        if('?' == $value[0])
                        {
                            $value[0] = $value[1];
                        }
                        $value = array($field, $operator, $value[0]);
                        $isPass = true;
                        break;
                    }
                }
                // 不包含任何操作符,抛出异常
                // 是否需要详细检查?
                if(false == $isPass)
                {
                    throw new Qwin_Padb_Parser_Exception('The where clause does not contain a operator. The clause is ' . $value . '.');
                }
            }
            $where[$key] = $value;
        }
        return $where;
    }

    /**
     * 解析排列语句
     *
     * @param string $condition 排列条件
     * @return array 解析过的排列数组
     */
    public function orderBy($condition)
    {
        $orderBy = array();

        if(empty($condition))
        {
            return $orderBy;
        }

        $condition = explode(',', $condition);
        foreach($condition as $set)
        {
            $set = trim($set);
            $pos = strpos($set, ' ');
            if(false !== $pos)
            {
                $field = trim(substr($set, 0, $pos));
                $type = strtoupper(trim(substr($set, $pos)));
                if(!in_array($type, array('DESC', 'ASC')))
                {
                    throw new Qwin_Padb_Parser_Exception('The order type should be "DESC", "ASC" or left it blank.The error field is "' . $field . '";');
                    //$type = $this->_deafultSetting['orderType'];
                }
            } else {
                $field = trim($set);
                $type = $this->_deafultSetting['orderType'];
            }
            if('DESC' == $type)
            {
                $type = SORT_DESC;
            } else {
                $type = SORT_ASC;
            }
            $orderBy[] = array($field, $type);
        }
        return $orderBy;
    }

    /**
     * 解析限制语句
     *
     * @param int/string $num 限制数目
     * @return int 限制数目
     */
    public function limit($num)
    {
        if(null == $num)
        {
            $num = 999999;
        } else {
            $num = intval($num);
            if($num <= 0)
            {
                $num = 1;
            }
        }
        return $num;
    }

    /**
     * 解析位移语句
     *
     * @param int/string $num 位移数目
     * @return int 位移数目
     */
    public function offset($num)
    {
        $num = intval($num);
        if($num <= 0)
        {
            $num = 1;
        }
        return $num;
    }

    public function set($data)
    {
        if(empty($data))
        {
            return $data;
        }

        $result = array();
        foreach($data as $key => $set)
        {
            $set[0] = trim($set[0]);
            $set[1] = trim($set[1]);
            // $set[1]应该是问号或简单算术表达式
            if('?' != $set[1])
            {
                foreach($this->_setOperator as $operator)
                {
                    // 存在合法的操作符,对查询内容进行解析
                    $position = strpos($set[1], $operator);
                    if(false !== $position)
                    {
                        $set[1] = array(
                            trim(substr($set[1], 0, $position)),
                            $operator,
                            trim(substr($set[1], $position + strlen($operator))),
                        );
                        break;
                    }
                }
                // 未找到合适运算符
                if(false === $position)
                {
                    throw new Qwin_Padb_Parser_Exception('The set clause does not contain a operator.');
                }
            }
            $result[] = $set;
        }
        return $result;
    }

    public function value($value)
    {
        $result = array();
        foreach($value as $field => $set)
        {
            $result[] = array(
                trim($set[0]),
                $set[1],
            );
        }
        return $result;
    }

    public function parseCreateTable($q)
    {
        $q['param'][1] = trim($q['param'][1]);
        $fieldSet = array();
        foreach($q['param'][2] as $key => $value)
        {
            if(is_numeric($key) && is_string($value))
            {
                $fieldSet[$value] = array(
                    'default' => null,
                );
            } else {
                $fieldSet[$key] = $value;
                if(isset($value['primary']) && true == $value['primary'])
                {
                    $q['param'][3]['primary'] = $key;
                }
            }
        }
        // 设置主键
        if(!isset($q['param'][3]['primary']))
        {
            $q['param'][3]['primary'] = key($fieldSet);
        }
        $q['param'][2] = $fieldSet;
        return $q;
    }
}
