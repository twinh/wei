<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A database widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Db extends AbstractWidget
{
    protected $tables = array(

    );

    public function __invoke()
    {
        return $this;
    }

    /**
     * Return a new table instance
     */
    public function getTable($name)
    {
        return  new Table(array(
            'widget' => $this->widget,
            'db' => $this,
            'table' => $this->camelCaseToUnderscore($name),
        ));
    }

    public function findAll($table, $where)
    {
        $query = $this->prepareQuery($table, $where);

        $data = $query->fetchAll();

        $records = array();
        foreach ($data as $row) {
            $records[] = new Table(array(
                'widget' => $this->widget,
                'db' => $this,
                'table' => $table,
                'data' => $row
            ));
        }

        return new Coll($records);
    }

    public function prepareQuery($table, $where)
    {
        $query = $this->widget
            ->dbal()
            ->createQueryBuilder()
            ->select('*')
            ->from($table, 't');

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $query
                    ->andWhere($key . ' = :' . $key)
                    ->setParameter($key, $value);
            }
        } else {
            $query
                ->where('id = :id')
                ->setParameter('id', $where);
        }

        $data = $query
            ->execute();

        return $data;
    }

    public function find($table, $id)
    {
        $data = $this->prepareQuery($table, $id)
            ->fetch();

        $table = new Table(array(
            'widget' => $this->widget,
            'db' => $this,
            'table' => $table,
            'data' => $data
        ));

        return $table;
    }

    /**
     * Returns a new table instance, alias of `getTable`
     *
     * @param string $name
     * @return Table
     */
    public function __get($name)
    {
        return $this->getTable($name);
    }

    public function __call($name, $args)
    {
        return call_user_func_array(array($this->getTable($name), 'find'), $args);
    }

    public function camelCaseToUnderscore($name)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }
}

/**
 * @todo move to better position
 */
class Table extends  AbstractWidget
{
    protected $table;

    /**
     * @var Db
     */
    protected $db;

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

    public function toArray()
    {
        return $this->data;
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
    }

    public function __get($name)
    {
        $fieldName = $this->db->camelCaseToUnderscore($name);

        if (isset($this->data[$fieldName])) {
            return $this->data[$fieldName];
        // has one
        } elseif (isset($this->data[$name . '_id'])) {
            return $this->data[$fieldName] = $this->db->find($name . 's', array(
                'id' => $this->data[$name . '_id']
            ));
        // one to many
        } elseif (substr($name, -1) == 's') {
            return $this->data[$fieldName] = $this->db->findAll($name, array(
                rtrim($this->table, 's') . '_id' => $this->data['id']
            ));
        // belong to
        } else {
            return $this->data[$fieldName] = $this->db->find($name . 's', array(
                rtrim($this->table, 's') . '_id' => $this->data['id']
            ));
        }
    }
}

class Coll extends  \ArrayObject
{

}