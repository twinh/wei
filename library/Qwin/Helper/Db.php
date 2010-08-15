<?php
/**
 * Query
 *
 * 根据所给数组生成相应的 mysql 语句
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
 * @subpackage  Helper
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-24 20:45:11
 */

class Qwin_Helper_Db
{
    // 数据表名,不包含
    public $table;
    public $prefix;
    private $_table;
    
    // 设置数据表名称
    public function setTable($table)
    {
        $this->table = $table;
        $this->_setTable();
    }
    
    // 设置数据表前缀
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        $this->_setTable();
    }
    
    // 设置数据表全称
    private function _setTable()
    {
        $this->_table = $this->prefix . $this->table;
    }
    
    public function getTable()
    {
        return $this->_table;
    }
    
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    // 获取一个记录(或 全部) 提供select, where
    function getOne($array)
    {
        !isset($array['SELECT']) && $array['SELECT'] = '*';
        $sql = "SELECT " . $array['SELECT'] . " FROM " . $this->_table . " WHERE " . $array['WHERE'];
        return $sql;
    }
    
    // 获取统计数目
    function getCount($array, $name = 'count')
    {        
        $sql = "SELECT COUNT(*) AS " . $name . " FROM " . $this->_table ;
        if($array['WHERE'])
        {
            $sql .= " WHERE " .  $array['WHERE'];
        }
        return $sql;
    }
    
    // 获取列表
    function getList($array)
    {
        $array_2 = array
        (
            'SELECT'    => isset($array['SELECT']) ? $array['SELECT'] : '',
            'FROM'        => $this->_table,
            'WHERE'        => isset($array['WHERE']) ? $array['WHERE'] : '',
            'ORDER'        => isset($array['ORDER']) ? $array['ORDER'] : '',
            'LIMIT'        => isset($array['LIMIT']) ? $array['LIMIT'] : '',
        );
        $sql = $this->getQuery($array_2);
        return $sql;
    }
    
    // 删除
    /*
    $query_array = array
    (
        'TABLE'    => '表名',
        'WHERE' => 'id = 10', // 'WHERE' => id IN(2, 3, 4);
    );
    // 一个或多个 TODO LIMIT
    */
    function getDelete($array)
    {
        $sql = "DELETE FROM " . $this->_table . " WHERE " . $array['WHERE'] . ";";
        return $sql;
    }
    
    // 通用
    function getQuery($querys)
    {
        $query_parts = array();
        
        if(!$querys['SELECT'] && !isset($querys['DELETE']))
        {
            $querys['SELECT'] = '*';
        }
        
        // case select
        isset($querys['SELECT']) && $query_parts[] = 'SELECT ' . $querys['SELECT'];
        // case delect
        isset($querys['DELETE']) && $query_parts[] = 'DELETE ';
        
        $query_parts[] = 'FROM ' . $querys['FROM'];
        //
        $querys['WHERE'] && $query_parts[] = 'WHERE ' . $querys['WHERE'];
        $querys['ORDER'] && $query_parts[] = 'ORDER BY ' . $querys['ORDER'];        
        $querys['LIMIT'] && $query_parts[] = 'LIMIT ' . $querys['LIMIT'];
        $query = implode(' ', $query_parts);

        return $query;
    }
    
    // 08.08.14
    function getIU($array , $id = null)
    {
        //转换
        !is_array($array) && $array = array($array);
        // UPDATE
        if($id)
        {
            foreach($array as $key => $val)
            {
                if($key != $id)
                {
                    $array_2[] = "`$key` = '$val'";
                }
            }
            $sql = "UPDATE " . $this->_table . " SET " .
                    implode(', ', $array_2) . 
                    " WHERE `$id` = '$array[$id]';";            
        // INSERT
        } else {
            // TODO sql 配置问题
            unset($array['id']);
            $key = array_keys($array);
            $sql = "INSERT INTO " . $this->_table . " (`" .
                    implode('`, `', $key) . "`) VALUE ('" .
                    implode("', '", $array) . "');";
        }
        return $sql;
    }
    
    function getSelectByField($list)
    {
        $list = qw('-arr')->set($list);
        return '`' . implode('`, `', $list) . '`';
    }
    
    /*
    $aAllowField = array('cName', 'iOrder');
    $ORDER = array
    (
        0 => array
        (
            'name' => 'cName',
            'type' => 'DESC',
        ),
        1 => array
        (
            'name' => 'iOrder',
            'type' => 'ASC',
        ),
    );
    */
    function getOrder($aOrder, $aAllowField, $key = 'id')
    {
        if(!is_array($aOrder))
        {
            return "$key DESC";
        }
        foreach($aOrder as $key => $val)
        {
            if(!in_array($val['name'], $aAllowField))
            {
                continue;
            }
            !in_array($val['type'], array('DESC', 'ASC')) && $val['type'] = 'DESC';
            $cSqlOrder = "$val[name] $val[type], ";
        }
        return $cSqlOrder;
    }
    
    // $aWhere = array('fieldName' => 1, 'fieldName2' => 2, ..);
    function getWhere($aWhere, $aAllowField)
    {
        $aSqlPart = array();
        
        if(!is_array($aWhere))
        {
            return '';
        }
        foreach($aWhere as $key => $val)
        {
            // 剔除非法字段名
            if(!in_array($key, $aAllowField))
            {
                continue;
            }
            $aSqlPart[] = "`$key` = '$val'";
        }
        // 防止使用非法字段名,导致错误
        if(count($aSqlPart) > 0)
        {
            //$cSqlWhere = "AND " . implode(" AND ", $aSqlPart);
            $cSqlWhere = implode(" AND ", $aSqlPart);
        }
        return $cSqlWhere;
    }
    
    
    
    
    /*
    $queryArray = array
    (
        'TABLE'    => '表名',
        'SET'    => 'content = 10',
        'WHERE' => 'id = 10',
    );
    // 一般一个
    */
    /*function toUpdate($queryArray)
    {
        $sql = "UPDATE " . $this->db_prefix . $queryArray['TABLE'] . " SET " . $queryArray['SET'] . " WHERE " . $queryArray['WHERE'] . ";";
        return $sql;
    }*/
    // UPDATE wap.wap_admin_menu SET cUrl = '123.php.' WHERE wap_admin_menu.id =2 LIMIT 1 ;
    /*
    $queryArray = array
    (
        'TABLE'    => '表名',
        'WHERE' => '条件',
    );
    // 计算数目
    */
    /*function toCountQuery($queryArray)
    {
        $sql = "SELECT COUNT(*) AS iCount FROM ". $this->db_prefix . $queryArray['TABLE'] ."
            WHERE 1=1 " . $queryArray['WHERE'];
        return $sql;
    }*/
    
    /***************************************************/
    // 以下数组键名不代表 sql 语句
    /***************************************************/
    /*
    $query = array
    (
        'TABLE'    => '表名',
        '删除的字段名' => '值',
    );
    // 删除一个
    */
    /*function toDeleteOne($queryArray)
    {
        $queryArray = $this->to_sort($queryArray);
        $table = $this->db_prefix . $queryArray[1][0];
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $queryArray[0][1] . ' = \'' . $queryArray[1][1] . '\' LIMIT 1;';
        return $sql;
    }*/
    /*
    $query = array
    (
        'TABLE'    => '表名',
        '更新的字段名' => '值',
    );
    // 更新一个
    */
    
    /*function toUpdateOne($queryArray)
    {
        $queryArray = $this->to_sort($queryArray);
        $table = $this->db_prefix . $queryArray[1][0];
        $sql = 'UPDATE ' . $table . ' SET ' . $queryArray[0][2] . ' = \'' . $queryArray[1][2] . 
            '\' WHERE ' . $queryArray[0][1] . ' = \'' . $queryArray[1][1] . '\' LIMIT 1;';
        return $sql;
    }

    function to_sort($array)
    {
        $r_array = array();
        $i = 0;
        foreach($array as $key => $value)
        {
            $r_array[0][$i] = $key;
            $r_array[1][$i] = $value;
            $i++;
        }
        return $r_array;
    }*/
}
?>
