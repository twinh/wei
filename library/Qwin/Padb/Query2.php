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
 * @since       2010-09-24 15:19:24
 */

/**
 * @see Qwin_Padb_Query_Exception
 */
require_once 'Qwin/Padb/Query/Exception.php';

class Qwin_Padb_Query2 extends Doctrine_Query
{
    public function showDatabase()
    {
        
    }

    public function showTable($database)
    {
        
    }

    public function showIndex()
    {
        
    }

    public function showStatus()
    {
        
    }

    public function showVariable()
    {
        
    }

    public function showGrant($username)
    {
        
    }

    protected function _execute($params)
    {
        p($this->_dqlParts);exit;
        // Apply boolean conversion in DQL params
        $params = $this->_conn->convertBooleans($params);

        foreach ($this->_params as $k => $v) {
            $this->_params[$k] = $this->_conn->convertBooleans($v);
        }

        $dqlParams = $this->getFlattenedParams($params);

        // Check if we're not using a Doctrine_View
        if ( ! $this->_view)
        {
            $query = $this->getSqlQuery($params);
        } else {
            $query = $this->_view->getSelectSql();
        }
        echo $query;exit;
        
        // Get prepared SQL params for execution
        $params = $this->getInternalParams();

        if ($this->isLimitSubqueryUsed() &&
                $this->_conn->getAttribute(Doctrine_Core::ATTR_DRIVER_NAME) !== 'mysql') {
            $params = array_merge((array) $params, (array) $params);
        }

        if ($this->_type !== self::SELECT) {
            return $this->_conn->exec($query, $params);
        }

        $stmt = $this->_conn->execute($query, $params);

        $this->_params['exec'] = array();

        return $stmt;
    }

    public function exec()
    {
        echo 123;
    }

    /*public function select()
    {
        return $this;
    }

    public function from()
    {
        return $this;
    }

    public function where()
    {

    }

    public function order()
    {

    }

    public function limit()
    {

    }

    public function offset()
    {

    }

    public function andWhere()
    {

    }

    public function orWhere()
    {

    }

    public function leftJoin()
    {
        
    }*/
}