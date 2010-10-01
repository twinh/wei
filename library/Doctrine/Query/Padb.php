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
 * @package     Doctrine
 * @subpackage  Query
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-29 11:17:42
 */

class Doctrine_Query_Padb extends Doctrine_Query
{
    /**
     * Padb查询对象
     * @var Qwin_Padb_Query
     */
    public $_query;

    /**
     * 最后一次查询获取的数据
     * @var mixed
     */
    protected $_lastData;

    /**
     * Dql查询的数据表名称
     */
    protected $_dqlTable;

    public function  __construct(Doctrine_Connection $connection = null,
        Doctrine_Hydrator_Abstract $hydrator = null)
    {
        parent::__construct($connection, $hydrator);

        // 连接Padb
        $this->_conn->connect();
        $this->_query = $this->_conn->getPadbQuery();
    }

    public function from($from)
    {
        $this->_dqlTable = $from;
        $table = $this->_conn->getTable($from)->getTableName();
        $this->_query->from($table);
        return $this;
    }

    public function select($select = null)
    {
        $this->_query->select($select);
        return $this;
    }

    public function addSelect($select = null)
    {
        $this->_query->addSelect($select);
        return $this;
    }

    public function orderBy($orderby)
    {
        $this->_query->orderBy($orderby);
        return $this;
    }

    public function where($where, $params = array())
    {
        $this->_query->where($where, $params);
        return $this;
    }

    public function limit($limit)
    {
        $this->_query->limit($limit);
        return $this;
    }

    public function getError()
    {
        return $this->_query->getError();
    }

    public function getErrorCode()
    {
        return $this->_query->getErrorCode();
    }
    

    /**
     * execute
     * executes the query and populates the data set
     *
     * @param array $params
     * @return Doctrine_Collection            the root collection
     */
    public function execute($params = array(), $hydrationMode = 'padb')
    {
        // Clean any possible processed params
        $this->_execParams = array();

        if (false == $this->_query->issetFrom()) {
            throw new Doctrine_Query_Exception('You must have at least one component specified in your from.');
        }

        if ($hydrationMode !== null) {
            $this->_hydrator->setHydrationMode($hydrationMode);
        }

        // set query components
        $table = $this->loadRoot($this->_dqlTable, $this->_dqlTable);

        $hydrationMode = $this->_hydrator->getHydrationMode();

        if ($this->_resultCache && $this->_type == self::SELECT) {
            $cacheDriver = $this->getResultCacheDriver();
            $hash = $this->getResultCacheHash($params);
            $cached = ($this->_expireResultCache) ? false : $cacheDriver->fetch($hash);

            if ($cached === false) {
                // cache miss
                $stmt = $this->_execute($params);
                $this->_hydrator->setQueryComponents($this->_queryComponents);
                $result = $this->_hydrator->hydrateResultSet($stmt, $this->_tableAliasMap);

                $cached = $this->getCachedForm($result);
                $cacheDriver->save($hash, $cached, $this->getResultCacheLifeSpan());
            } else {
                $result = $this->_constructQueryFromCache($cached);
            }
        } else {
            $stmt = $this->_execute($params);
            if (is_integer($stmt)) {
                $result = $stmt;
            } else {
                $this->_hydrator->setQueryComponents($this->_queryComponents);
                if ($this->_type == self::SELECT && $hydrationMode == Doctrine_Core::HYDRATE_ON_DEMAND) {
                    $hydrationDriver = $this->_hydrator->getHydratorDriver($hydrationMode, $this->_tableAliasMap);
                    $result = new Doctrine_Collection_OnDemand($stmt, $hydrationDriver, $this->_tableAliasMap);
                } else {
                    $result = $this->_hydrator->hydrateResultSet($stmt, $this->_tableAliasMap);
                }
            }
        }
        if ($this->getConnection()->getAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS)) {
            $this->free();
        }
        
        return $result;
    }

    /**
     * @todo
     */
    protected function _execute($params)
    {
        return $this->_query;
    }

    public function count()
    {
        return $this->_query->count();
    }

    public function fetchOne($params = array(), $hydrationMode = 'padb')
    {
        $this->_query->limit(1);
        return parent::fetchOne($params, $hydrationMode);
    }
}
