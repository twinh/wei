<?php
/**
 * Padb
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
 * @since       2010-09-24 13:45:18
 */

class Doctrine_Adapter_Padb implements Doctrine_Adapter_Interface
{
    protected $_lastInsertId;

    protected $_errorCode;

    protected $_errorInfo;

    protected $_attribute;

    /*
     * the root path of the padb
     * @var string
     */
    protected $_rootPath;

    /**
     * current database
     * @var string
     */
    protected $_database;

    public function __construct($config = array(), $username = null, $password = null)
    {
        $dsn = $this->_parseDsn($config);
        $this->_conn = new Qwin_Padb_Connection($this->_rootPath, $username, $password);
        $this->_conn->selectDatabase($this->_database);
    }

    /**
     * Parse the dsn string, set root path and current database
     *
     * @param string $config 配置字符串
     * @return object 当前对象
     */
    protected function _parseDsn($config)
    {
        $partList = explode('/', $config);
        $this->_database = array_pop($partList);
        $this->_rootPath = array_pop($partList);
        return $this;
    }

    /**
     * Prepare a query statement
     *
     * @param string $query
     * @return string
     */
    public function prepare($prepareString)
    {
        throw new Doctrine_Adapter_Exception("unsupported");
    }

    /**
     * Execute a query string.
     */
    public function query($queryString)
    {
        echo 'execute' . $queryString;
    }

    /**
     * Quote a value.
     *
     * @param <type> $input
     * @return <type>
     */
    public function quote($input)
    {
        return $input;
    }

    /**
     * execcute a query setting
     *
     * @param array $setting
     */
    public function exec($setting)
    {
        //return $this->_query->call($setting);
    }

    /**
     * Get the last inserted id
     *
     * @return <type>
     */
    public function lastInsertId()
    {
        return $this->_lastInsertId;
    }
    
    public function beginTransaction()
    {
        throw new Doctrine_Adapter_Exception("unsupported");
    }
    
    public function commit()
    {
        throw new Doctrine_Adapter_Exception("unsupported");
    }

    public function rollBack()
    {
        throw new Doctrine_Adapter_Exception("unsupported");
    }

    public function errorCode()
    {
        return $this->_errorCode;
    }

    public function errorInfo()
    {
        return $this->_errorInfo;
    }

    public function setAttribute($attribute, $value)
    {
        $this->_attribute[$attribute] = $value;
    }

    public function getAttribute($attribute)
    {
        if(isset($this->_attribute[$attribute]))
        {
            return $this->_attribute[$attribute];
        }
        return null;
    }
}
