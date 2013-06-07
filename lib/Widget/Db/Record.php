<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Db;

use Widget\Db;
use Widget\AbstractWidget;

/**
 * A base database record class
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Record extends AbstractWidget
{
    /**
     * The record table name
     *
     * @var string
     */
    protected $table;

    /**
     * Whether it's a new record and have not save to database
     *
     * @var bool
     */
    protected $isNew = true;

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
     * Set record data
     *
     * @param $data
     * @return Record
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
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
                $this->data[$this->primaryKey] = $this->db->lastInsertId();
            }
            return (bool)$result;
        // Update
        } else {
            // TODO update only changed field
            return (bool)$this->db->update($this->table, $this->data, array(
                $this->primaryKey => $this->data[$this->primaryKey]
            ));
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
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
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
     * Remove column value
     *
     * @param string $name The name of column
     * @return Record
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->data)) {
            unset($this->data[$name]);
        }
        return $this;
    }

    /**
     * A helper method to find a record
     *
     * @param $id
     * @return Record
     */
    public function find($id)
    {
        return $this->db->find($this->getTable(), $id);
    }
}