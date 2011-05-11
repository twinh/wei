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

    /**
     * Prepare a query statement
     *
     * @param string $query
     * @return string
     */
    public function prepare($prepareString)
    {
        return false;
    }

    /**
     * Execute a query string.
     */
    public function query($queryString)
    {
        echo 'Query:' . p($queryString);
    }

    /**
     * Quote a value.
     *
     * @param <type> $input
     * @return <type>
     */
    public function quote($input)
    {
        echo 'Quote:' . p($input);
        return $input;
    }

    /**
     * execcute a query setting
     *
     * @param array $setting
     */
    public function exec($setting)
    {
        echo 'Exec:' . p($setting);
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
        return false;
    }
    
    public function commit()
    {
        return false;
    }

    public function rollBack()
    {
        return false;
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
