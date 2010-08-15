<?php
/**
 * Db
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
 * @subpackage  Db
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-04-25 20:45:11
 * @todo        扩展性等..
 */


class Qwin_Db_Mysql
{                 
    public    $db_host; 
    public    $db_port; 
    public    $db_user; 
    public    $db_pass; 
    public    $db_name; 
    public    $db_charset;
    public    $db_link;
    public  $error_output=true;
    public  $query_error_die=true;
    
    function __construct($db)
    {
        $this->db_host = $db['server']; 
        $this->db_port = $db['port']; 
        $this->db_user = $db['username']; 
        $this->db_pass = $db['password']; 
        $this->db_name = $db['database']; 
        $this->db_charset = $db['charset']; 
        
        $real_host = $this->db_host.":".$this->db_port;
        $this->db_link = @mysql_connect($real_host,$this->db_user,$this->db_pass,false,131072);
        if($this->db_link){
            if(false == @mysql_select_db($this->db_name, $this->db_link))
            {
                return false;
                //or die("select database failed");
            }
            @mysql_query("set names ".$this->db_charset);
        }                
    }
    
    function Close()
    {
        //
    }
    
    function showErr($sql = "") 
    { 
        if(!$this->error_output) return "";
        echo "error ".mysql_errno()." : ".mysql_error( ); 
    
        if (1 != $sql) $this->DatabaseClose($this->db_link);        
        exit("<div style=\" font-family: Arial, Helvetica, sans-serif; font-size: 10pt; font-style: normal; color: Red; font-weight: bold\">Error ".@mysql_errno($this->db_link).": ".@mysql_error($this->db_link)." on ".__FILE__." line ". __LINE__." Sql ".$sql."</div>");
    } 
    
    function Query($sql)
    {
        $result = @mysql_query($sql,$this->db_link);
        if(!$result)
        {
            if($this->query_error_die)
            {
                die("query failed".$this->showErr($sql));            
            } else  {
                echo "query failed";
            }
        }
        return $result; 
    }
    
    /**
     * Query sql sentences
     *
     * @param string array $sql
     * @return boolean
     */
    function QueryUnderTransaction($array_sql)
    {
        $arr_sql = $array_sql;
        $result = @mysql_query("begin",$this->db_link);
        
        if($result)
        {
            for( $i=0; $i< count($arr_sql); $i++)
            {
                $result = @mysql_query(trim($arr_sql[$i]),$this->db_link) or die("query failed".$this->showErr(trim($arr_sql[$i])));
                if( $result == false)
                {
                    break;
                }
            }
        }
        
        if($result)
        {
            $result = @mysql_query("commit",$this->db_link);
        }
        else
        {
            @mysql_query("bollback",$this->db_link);            
            $result = false;
        }
        
        return $result; 
    }
    
    function FetchArray($result, $type = MYSQL_ASSOC) 
    {
        // 不显示数字数组
        $row = @mysql_fetch_array($result, $type); 
        return $row; 
    }
    
    function FetchRow($result) 
    { 
        $row = @mysql_fetch_row($result); 
        return $row; 
    }
    
    function FetchObject($result) 
    { 
        $row = @mysql_fetch_object($result); 
        return $row; 
    }
    
    function FreeResult(&$result) 
    { 
        return @mysql_free_result($result); 
    }
    
    function NumRows($result) 
    { 
        $number = @mysql_num_rows($result); 
        if (!($number)) $number = 0;
        return $number;
    }
    
    function AffectedRows() 
    { 
        $result = @mysql_affected_rows($this->db_link); 
        return $result; 
    }
    
    function DatabaseClose()
    {
        @mysql_close($this->db_link);
    }
    
    function getInsertId()
    {
        return @mysql_insert_id($this->db_link);
    }
    
    function DataSeek($result,$number)
    {
        @mysql_data_seek($result,$number);
    }
    
    // 获取某个字段的内容
    function getFieldContent($tableName, $fieldName, $condition, $type = MYSQL_ASSOC)
    {
        $sql = "select ".$fieldName." from ".$tableName." where 1=1 ". $condition ." Limit 0, 1";
        //echo $sql;
        $result = $this->Query($sql);
        $fieldContent = "";
        $temp = "";
        if($result)
        {
            if($this->NumRows($result) > 0)
            {
                $temp = $this->FetchArray($result, $type);
                
                $fieldContent = $temp[$fieldName];
            }
        }
        return $fieldContent;
    }
    
    // 获取一条记录
    function getOne($sql, $type = MYSQL_ASSOC)
    {
        $result = $this->Query($sql);
        if($result)
        {
            if($this->NumRows($result) > 0)
            {
                return $this->FetchArray($result, $type);
            }
        }
        return false;
    }    
    
    // 获取一条记录
    function getFirstField($sql, $type = MYSQL_ASSOC)
    {
        $result = $this->Query($sql);
        if($result)
        {
            if($this->NumRows($result) > 0)
            {
                $result = $this->FetchArray($result, $type);
                return $result[0];
            }
        }
        return false;
    }
    
    // 获取多条记录
    function getList($sql, $type = MYSQL_ASSOC)
    {
        $array = array();
        $result = $this->Query($sql);
        
        if($result){
            $i = 0;
            while($row = $this->FetchArray($result, $type))
            {
                $array[$i] = $row;
                $i++;
            }
            return $array;
        }else{
            return false;
        }                
    }        
    
    // 获取查询结果的纪录数
    function getNum($sql)
    {
        $result = $this->Query($sql);
        if($result)
        {
            return $this->NumRows($result);    
        }
        return false; 
    }
    
    // 执行insert操作的函数
    function Insert($sql)
    {
        $result = $this->Query($sql);
        if( $result ){                
            if($this->AffectedRows() > 0)
            {
                return true;
            }
        }
        return false; 
    }
    
    // 执行delete操作的函数
    function Delete($sql)
    {
        $result = $this->Query($sql);
        if($result)
        {                
            if($this->AffectedRows() > 0)
            {
                return true;
            }
        }
        return false; 
    }
    
    function getField($cTable)
    {
        $aData = $aData2 = array();
        
        $sql = "DESCRIBE " . $cTable;
        $aData = $this->getArray($sql);
        foreach($aData as $val)
        {
            $aData2[] = $val['Field'];
        }
        
        return $aData2;
    }
}
?>
