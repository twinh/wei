<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A base database record class
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Record extends  AbstractWidget
{
    protected $table;

    protected $fullTable;

    protected $data = array();

    /**
     * @var Db
     */
    protected $db;

    /**
     * The helper method to create a new record object
     *
     * @param $id
     * @return Record
     */
    public function find($id)
    {
        return $this->db->find($this->getTable(), $id);
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function create($data, $id = null)
    {
        if ($id) {

        }
    }

    public function save($data = array())
    {
        $data && $this->fromArray($data);

        $dbData = array();
        $columns = $this->db->getColumns($this->table);
        if ($columns) {
            foreach ($data as $field => $value) {
                if (isset($columns[$field])) {
                    $dbData[$columns[$field]] = $value;
                } else {
                    $dbData[$field] = $value;
                }
            }
        } else {
            $dbData = $this->data;
        }


        return $this->db->insert($this->table, $dbData);
    }

    public function toArray()
    {
        $data = array();
        foreach ($this->data as $field => $value) {
            if ($value instanceof Table || $value instanceof Coll) {
                $data[$field] = $value->toArray();
            } else {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    public function fromArray($data)
    {
        $this->data = $data + $this->data;

        return $this;
    }

    public function clear($field)
    {
        if (isset($this->data[$field])) {
            unset($this->data[$field]);
        }

        return $this;
    }

    public function setData($data)
    {
        $columns = $this->db->getColumns($this->table);
        if ($columns) {
            $dbData = array();
            $columns = array_flip($columns);
            foreach ($data as $column => $value) {
                if (isset($columns[$column])) {
                    $dbData[$columns[$column]] = $value;
                } else {
                    $dbData[$column] = $value;
                }
            }
            $this->data = $dbData;
        } else {
            $this->data = $data;
        }
        return $this;
    }

    public function __get($name)
    {
        $db = $this->db;
        $fieldName = $this->db->camelCaseToUnderscore($name);
        $relations = $this->db->getTableRelation($this->table);

        if (isset($this->data[$fieldName])) {
            return $this->data[$fieldName];
        } elseif (isset($relations[$name])) {
            return $this->data[$fieldName] = $this->db->find($relations[$name]['table'], array(
                'id' => $this->data[$relations[$name]['column']]
            ));
            // has one
        } elseif (isset($this->data[$name . '_id'])) {
            return $this->data[$fieldName] = $this->db->find($db->getTableByField($name), array(
                'id' => $this->data[$name . '_id']
            ));
            // one to many
        } elseif (substr($name, -1) == 's') {
            $table = substr($name, 0, -1);
            return $this->data[$fieldName] = $this->db->findAll($db->getTableByField($table), array(
                $db->getSingular($this->table) . '_id' => $this->data['id']
            ));
            // belong to
        } else {
            return $this->data[$fieldName] = $this->db->find($db->getTableByField($name), array(
                $db->getSingular($this->table) . '_id' => $this->data['id']
            ));
        }
    }
}