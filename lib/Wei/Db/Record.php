<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Db;

use Wei\Db;
use Wei\Base;

/**
 * A base database record class
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Record extends Base implements \ArrayAccess
{
    /**
     * The database service
     *
     * @var Db
     */
    protected $db;

    /**
     * The record table name
     *
     * @var string
     */
    protected $table;

    /**
     * The table fields
     *
     * @var array
     */
    protected $fields = array();

    /**
     * The primary key field
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Whether it's a new record and has not save to database
     *
     * @var bool
     */
    protected $isNew = true;

    /**
     * The record data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Whether the record's data is modified
     *
     * @var bool
     */
    protected $isModified = false;

    /**
     * The record data before modified
     *
     * @var array
     */
    protected $oldData = array();

    /**
     * The callback triggered after load a record
     *
     * @var callable
     */
    protected $afterLoad;

    /**
     * The callback triggered before save a record
     *
     * @var callable
     */
    protected $beforeSave;

    /**
     * The callback triggered after save a record
     *
     * @var callable
     */
    protected $afterSave;

    /**
     * The callback triggered before insert a record
     *
     * @var callable
     */
    protected $beforeInsert;

    /**
     * The callback triggered after insert a record
     *
     * @var callable
     */
    protected $afterInsert;

    /**
     * The callback triggered after update a record
     *
     * @var callable
     */
    protected $beforeUpdate;

    /**
     * The callback triggered after update a record
     *
     * @var callable
     */
    protected $afterUpdate;

    /**
     * The callback triggered before delete a record
     *
     * @var callable
     */
    protected $beforeDelete;

    /**
     * The callback triggered after delete a record
     *
     * @var callable
     */
    protected $afterDelete;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Clear modified status after created
        $this->oldData = array();
        $this->isModified = false;

        $this->trigger('afterLoad');
    }

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
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the record data as array
     *
     * @param array $returnFields A indexed array specified the fields to return
     * @return array
     */
    public function toArray($returnFields = array())
    {
        if (!$returnFields) {
            $fields = $this->getFields();
            return $this->data + array_combine($fields, array_pad(array(), count($fields), null));
        } else {
            return array_intersect($this->data, array_flip($returnFields));
        }
    }

    /**
     * Returns the record and relative records data as JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Import a PHP array in this record
     *
     * @param array|\ArrayAccess $data
     * @return $this
     */
    public function fromArray($data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
        return $this;
    }

    /**
     * Import a PHP array in this record
     *
     * @param array $data
     * @return $this
     */
    protected function setData(array $data)
    {
        return $this->fromArray($data);
    }

    /**
     * Save record data to database
     *
     * @return bool
     */
    public function save()
    {
        $isNew = $this->isNew;
        $this->trigger('beforeSave');
        $this->trigger($isNew ? 'beforeInsert' : 'beforeUpdate');

        // Insert
        if ($this->isNew) {
            $result = (bool)$this->db->insert($this->table, $this->data);
            if ($result) {
                $this->isNew = false;
                $name = sprintf('%s_%s_seq', $this->table, $this->primaryKey);
                $this->data[$this->primaryKey] = $this->db->lastInsertId($name);
            }
            // Update
        } else {
            if ($this->isModified) {
                $data = array_intersect_key($this->data, $this->oldData);
                $affectedRows = $this->db->update($this->table, $data, array(
                    $this->primaryKey => $this->data[$this->primaryKey]
                ));
                $result = $affectedRows || '0000' == $this->db->errorCode();
            } else {
                $result = true;
            }
        }

        $this->oldData = array();
        $this->isModified = false;

        $this->trigger($isNew ? 'afterInsert' : 'afterUpdate');
        $this->trigger('afterSave');

        return $result;
    }

    /**
     * Delete the current record
     *
     * @return int
     */
    public function delete()
    {
        $this->trigger('beforeDelete');

        $result = (bool)$this->db->delete($this->table, array($this->primaryKey => $this->data[$this->primaryKey]));

        $this->trigger('afterDelete');

        return $result;
    }

    /**
     * Reload the record data from database
     *
     * @return $this
     */
    public function reload()
    {
        $this->data = (array)$this->db->select($this->table, $this->__get($this->primaryKey));
        $this->oldData = array();
        $this->isModified = false;
        $this->trigger('afterLoad');
        return $this;
    }

    /**
     * Receives the record field value
     *
     * @param string $name
     * @throws \InvalidArgumentException When field not found
     * @return string
     */
    public function __get($name)
    {
        if (in_array($name, $this->getFields())) {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        }

        throw new \InvalidArgumentException(sprintf(
            'Field "%s" not found in record class "%s"',
            $name,
            get_class($this)
        ));
    }

    /**
     * Set the record field value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->getFields())) {
            $this->oldData[$name] = isset($this->data[$name]) ? $this->data[$name] : null;
            $this->data[$name] = $value;
            $this->isModified = true;
        } else {
            $this->$name = $value;
        }
    }

    /**
     * Remove field value
     *
     * @param string $name The name of field
     * @return $this
     */
    public function __unset($name)
    {
        return $this->remove($name);
    }

    /**
     * Check if field exists
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Remove field value
     *
     * @param string $name The name of field
     * @return $this
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
     * Check if the record's data or specified field is modified
     *
     * @param string $field
     * @return bool
     */
    public function isModified($field = null)
    {
        if ($field) {
            return array_key_exists($field, $this->oldData);
        }
        return $this->isModified;
    }

    /**
     * Sets the primary key field
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
     * Returns the primary key field
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Returns the name of fields of current table
     *
     * @return array
     */
    public function getFields()
    {
        if (empty($this->fields)) {
            $this->fields = $this->db->getTableFields($this->table);
        }
        return $this->fields;
    }

    /**
     * Return the field data before modified
     *
     * @param string $field
     * @return string|array
     */
    public function getOldData($field = null)
    {
        if ($field) {
            return isset($this->oldData[$field]) ? $this->oldData[$field] : null;
        }
        return $this->oldData;
    }

    /**
     * Trigger a callback
     *
     * @param string $name
     */
    protected function trigger($name)
    {
        $this->$name();
        $this->$name && call_user_func($this->$name, $this, $this->wei);
    }

    /**
     * Check if field exists
     *
     * @param string $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->__isset($name);
    }

    /**
     * Receives record field value
     *
     * @param string $name
     * @return string
     */
    public function offsetGet($name)
    {
        return $this->__get($name);
    }

    /**
     * Set field value
     *
     * @param string $name
     * @param mixed $value
     */
    public function offsetSet($name, $value)
    {
        $this->__set($name, $value);
    }

    /**
     * Remove field value
     *
     * @param string $name The name of field
     */
    public function offsetUnset($name)
    {
        $this->__unset($name);
    }

    /**
     * The method called after load a record
     */
    protected function afterLoad()
    {
    }

    /**
     * The method called before save a record
     */
    public function beforeSave()
    {
    }

    /**
     * The method called after save a record
     */
    public function afterSave()
    {
    }

    /**
     * The method called before insert a record
     */
    public function beforeInsert()
    {
    }

    /**
     * The method called after insert a record
     */
    public function afterInsert()
    {
    }

    /**
     * The method called before update a record
     */
    public function beforeUpdate()
    {
    }

    /**
     * The method called after update a record
     */
    public function afterUpdate()
    {
    }

    /**
     * The method called before delete a record
     */
    public function beforeDelete()
    {
    }

    /**
     * The method called after delete a record
     */
    public function afterDelete()
    {
    }
}
