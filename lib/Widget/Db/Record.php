<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Db;

use Widget\Db;
use Widget\Base;

/**
 * A base database record class
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Record extends Base
{
    /**
     * The record table name
     *
     * @var string
     */
    protected $table;

    /**
     * Whether it's a new record and has not save to database
     *
     * @var bool
     */
    protected $isNew = true;

    /**
     * Whether the record's data is modified
     *
     * @var bool
     */
    protected $isModified = false;

    /**
     * The primary key column
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The record data
     *
     * @var array
     */
    protected $data = array();

    /**
     * The modified record data
     *
     * @var array
     */
    protected $modifiedData = array();

    /**
     * The database widget
     *
     * @var Db
     */
    protected $db;

    /**
     * Return the record table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the record table name
     *
     * @param string $table
     * @return Record
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the record and relative records data as array
     *
     * @param array $returnFields A indexed array specified the fields to return
     * @return array
     */
    public function toArray($returnFields = array())
    {
        $data = array();
        foreach ($this->data as $field => $value) {
            if ($returnFields && !in_array($field, $returnFields)) {
                continue;
            }
            if ($value instanceof Record || $value instanceof Collection) {
                $data[$field] = $value->toArray();
            } else {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    /**
     * Import a PHP array in this record
     *
     * @param $data
     * @return Record
     */
    public function fromArray($data)
    {
        $this->data = $data + $this->data;
        return $this;
    }

    /**
     * Import a PHP array in this record
     *
     * @param array $data
     * @return Record
     */
    public function setData(array $data)
    {
        return $this->fromArray($data);
    }

    /**
     * Save record data to database
     *
     * @param array $data
     * @return bool
     */
    public function save($data = array())
    {
        $data && $this->fromArray($data);

        // Insert
        if ($this->isNew) {
            $result = $this->db->insert($this->table, $this->data);
            if ($result) {
                $this->isNew = false;
                $name = sprintf('%s_%s_seq', $this->table, $this->primaryKey);
                $this->data[$this->primaryKey] = $this->db->lastInsertId($name);
            }
            return (bool)$result;
        // Update
        } else {
            if ($this->isModified) {
                $affectedRows = $this->db->update($this->table, $this->modifiedData, array(
                    $this->primaryKey => $this->data[$this->primaryKey]
                ));
                $result = $affectedRows || '0000' == $this->db->errorCode();
                if ($result) {
                    $this->isModified = false;
                    $this->modifiedData = array();
                }
                return $result;
            } else {
                return true;
            }
        }
    }

    /**
     * Delete the current record
     *
     * @return int
     */
    public function delete()
    {
        return (bool)$this->db->delete($this->table, array($this->primaryKey => $this->data[$this->primaryKey]));
    }

    /**
     * Receives record column value, record, collection or widget instance
     *
     * @param string $name
     * @throws \InvalidArgumentException When column or relation not found
     * @return string|Record|Collection
     */
    public function __get($name)
    {
        // Get table column value
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        // Get relation
        if (method_exists($this, $name)) {
            return $this->$name = $this->$name();
        }

        throw new \InvalidArgumentException(sprintf(
            'Column or relation "%s" not found in record class "%s"',
            $name,
            get_class($this)
        ));
    }

    /**
     * Set record value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        $this->modifiedData[$name] = $value;
        $this->isModified = true;
    }

    /**
     * Remove column value
     *
     * @param string $name The name of column
     * @return Record
     */
    public function __unset($name)
    {
        return $this->remove($name);
    }

    /**
     * Check if column exists
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Remove column value
     *
     * @param string $name The name of column
     * @return Record
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->data)) {
            $this->data[$name] = null;
        }
        return $this;
    }

    /**
     * Check if it's a new record and has not save to database
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * Check if the record's data is modified
     *
     * @return bool
     */
    public function isModified()
    {
        return $this->isModified;
    }

    /**
     * Sets the primary key column
     *
     * @param string $primaryKey
     * @return $this
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * Returns the primary key column
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}