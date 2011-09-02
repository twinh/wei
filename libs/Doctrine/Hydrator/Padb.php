<?php
/**
 * Padb
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @subpackage  Hydrator
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-01 16:50:51
 */

class Doctrine_Hydrator_Padb extends Doctrine_Hydrator_Graph
{
    protected $_collections = array();
    private $_initializedRelations = array();

    /**
     * Puts the fields of a data row into a new array, grouped by the component
     * they belong to. The column names in the result set are mapped to their
     * field names during this procedure.
     *
     * @return array  An array with all the fields (name => value) of the data row,
     *                grouped by their component (alias).
     */
    protected function _gatherRowData(&$data, &$cache, &$id, &$nonemptyComponents)
    {
        $rowData = array(
            $this->_rootAlias => $data
        );
        return $rowData;
    }

    public function getElementCollection($component)
    {
        $coll = Doctrine_Collection::create($component);
        $this->_collections[] = $coll;

        return $coll;
    }

    public function initRelated(&$record, $name)
    {
        if ( ! isset($this->_initializedRelations[$record->getOid()][$name])) {
            $relation = $record->getTable()->getRelation($name);
            $coll = Doctrine_Collection::create($relation->getTable()->getComponentName());
            $coll->setReference($record, $relation);
            $record[$name] = $coll;
            $this->_initializedRelations[$record->getOid()][$name] = true;
        }
        return true;
    }

    public function registerCollection($coll)
    {
        $this->_collections[] = $coll;
    }

    public function getNullPointer()
    {
        return self::$_null;
    }

    public function getElement(array $data, $component)
    {
        $component = $this->_getClassNameToReturn($data, $component);

        $this->_tables[$component]->setData($data);
        $record = $this->_tables[$component]->getRecord();

        return $record;
    }

    public function getLastKey(&$coll)
    {
        $coll->end();

        return $coll->key();
    }

    /**
     * sets the last element of given data array / collection
     * as previous element
     *
     * @param boolean|integer $index
     * @return void
     * @todo Detailed documentation
     */
    public function setLastElement(&$prev, &$coll, $index, $dqlAlias, $oneToOne)
    {
        if ($coll === self::$_null) {
            unset($prev[$dqlAlias]); // Ticket #1228
            return;
        }

        if ($index !== false) {
            // Link element at $index to previous element for the component
            // identified by the DQL alias $alias
            $prev[$dqlAlias] = $coll[$index];
            return;
        }

        if (count($coll) > 0) {
            $prev[$dqlAlias] = $coll->getLast();
        }
    }

    public function flush()
    {
        // take snapshots from all initialized collections
        foreach ($this->_collections as $key => $coll) {
            $coll->takeSnapshot();
        }
        $this->_initializedRelations = null;
        $this->_collections = null;
        $this->_tables = null;
    }
}
