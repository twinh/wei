<?php
/**
 * 数据库管理,备份,导入
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
 * @since       2009-11-21 17:16
 */

class Qwin_Db_Mysql_Ext
{
    static public $cache_path;
    static public $file_name;
    static public $db;
    
    // 获取指定前缀的数据表
    // TODO $prefix
    function getTable($prefix = null)
    {
        $aListDb = $aList = array();
        
        $iLength = strlen($prefix);
        $aListDb = self::$db->getList('SHOW TABLES', MYSQL_NUM);
        foreach($aListDb as $val)
        {
            if(substr($val[0], 0, $iLength) == $prefix)
            {
                $aList[] = $val[0];
            }
        }
        return $aList;
    }
    
    function outSqlFile($table, $name = null)
    {
        $cQuery = self::backupTable($table);
        $cQuery .= self::backupData($table);
        if(!$name)
        {
            $name = self::setFileName(6);
        }
        self::$file_name = self::$cache_path . '/' . $name . '.sql';
        QFile::write(self::$file_name, $cQuery);
    }
    
    function getSqlFile()
    {
        $aFile = self::getFile(self::$cache_path, array('sql'));
        return $aFile;
    }
    /*
    // resource like
    $table = array
    (
        'table01', 'table02', ...
    );
    */
    // 导出数据表
    function backupTable($table)
    {        
        foreach($table as $val)
        {
            $sqlData .= "DROP TABLE IF EXISTS $val;\n";
            $sqlDatas = self::$db->getOne("SHOW CREATE TABLE $val");
            $sqlData .= $sqlDatas['Create Table'] . ";\n";
        }
        return $sqlData;
    
    }
    
    // 导出表中内容
    function backupData($table)
    {
        global $db, $system;
        
        $query->db_prefix = '';
        foreach($table as $val)
        {
            $datas = self::$db->getList("SELECT * FROM $val");
            // 多少行
            $iCount = self::$db->AffectedRows();
            if($iCount == 0)
            {
                continue;
            }
            for($i = 0; $i < $iCount; $i ++)
            {
                $cQuery .= self::toInsert($val, $datas[$i]) . "\n";
            }
        }
        return $cQuery;
    }
    
    function toInsert($table, $codes) {
        //转换
        !is_array($codes) && $codes = array($codes);
        foreach($codes as $key => $value){
            $sql_field_name[] = $key;
            $sql_value[] = self::_secureCode($value);
        }
        $sql_query = 'INSERT INTO '. $this->db_prefix
                    . $table .' (`'.
                    implode('`, `', $sql_field_name) . 
                    '`) values (\'' . 
                    implode("', '", $sql_value) . 
                    '\') ;';    
        return $sql_query;
    }
    
    private function _secureCode($data)
    {
        return str_replace(array("'"), array("\'"), $data);
    }
    
    // 导入sql数据
    function importSqlFile($file_name)
    {           
        $aQuery = array();
        
        $file_name = self::$cache_path . '/' . $file_name;
        $cQuery = QFile::read($file_name);
        //
        $aQuery = explode(";\n", $cQuery);
        
        foreach($aQuery as $val)
        {
            if($val != '')
            {
                self::$db->Query($val);
            }
        }
    }
    
    function delSqlFile($file_name)
    {
        if(@unlink(self::$cache_path . '/' . $file_name))
        {
            return true;
        }
        return false;
    }
    
    function setFileName($iLength)
    {
        $file_name = date('Ymdhis', TIMESTAMP) . self::getRandStr($iLength);
        return $file_name;
    }
    
    /**
     * @abstract  产生指定长度随机字符串
     * @param int $len
     * 具体可修改 $str
    */
    function getRandStr($len){
        $code = '';
        $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$l = strlen($str);
        $l = 62;
        for($i = 1;$i <= $len;$i++){ 
            $num = rand(0, $l-1);
            $code .= $str[$num];
        }
        return $code;
    }
    
    function getFile($path,$exts)
    {
        !is_array($exts) && $exts = array($exts);
        
        //构造正则
        $pattern = '\\'.implode ('|', $exts).'$';
        $files = array();
        $fp = opendir($path);
        $i = 0;
        while($file = readdir ($fp ) ) {
            if(eregi($pattern, $file)){
                $files[$i]['title'] = iconv("GBK", "UTF-8", $file);
                $files[$i]['time'] = @filemtime($path . '/' . $file);
                $i++;
            }
        }
        closedir($fp);
        return $files;
    }
}
