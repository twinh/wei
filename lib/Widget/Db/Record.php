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

    protected $fullTable;

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
     * Returns a the record data
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
     *
     *
     * @param $field
     * @return $this
     */
    public function clear($field)
    {
        if (isset($this->data[$field])) {
            unset($this->data[$field]);
        }

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

        return (bool)$this->db->insert($this->table, $this->data);
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
     * @return string|Record|Collection|\Widget\WidgetInterface
     */
    public function __get($name)
    {
        // Table columns
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        // Relation
        if (method_exists($this, $name)) {
            return $this->$name = $this->$name();
        }

        return parent::__get($name);
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