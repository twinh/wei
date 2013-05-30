<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use PDO;

/**
 * A database widget that compatible with Doctrine DBAL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Db extends AbstractWidget
{
    /**
     * The database username
     *
     * @var string
     */
    protected $user;

    /**
     * The database password
     *
     * @var string
     */
    protected $password;

    /**
     * The dsn parameter for PDO constructor
     *
     * @var string
     */
    protected $dsn = 'sqlite::memory:';

    /**
     * The PDO object
     *
     * @var \PDO
     */
    protected $pdo;

    protected $tables = array();

    protected $beforeQuery;

    protected $afterQuery;

    protected $isConnected = false;

    private $driver;

    public function __invoke()
    {
        return $this;
    }

    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        if ($this->pdo) {
            $this->isConnected = true;
            return true;
        }

        $this->pdo = new PDO($this->dsn, $this->user, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);

        $this->driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        $this->isConnected = true;
        return true;
    }

    public function insert($table, array $values)
    {
        $field = implode(', ', array_keys($values));
        $placeholder = implode(', ', array_pad(array(), count($values), '?'));

        $query = "INSERT INTO $table ($field) VALUES ($placeholder)";

        return $this->executeUpdate($query, array_values($values));
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows
     *
     * @param $query
     * @param array $params
     * @return int
     */
    public function executeUpdate($query, $params = array())
    {
        $this->connect();

        if ($params) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $result = $stmt->rowCount();
        } else {
            $result = $this->pdo->exec($query);
        }
        return $result;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function query($query, $params = array())
    {
        $this->connect();

        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, array($query, $params, $this));
        }

        if ($params) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        } else {
            $stmt = $this->pdo->query($query);
        }

        if ($this->afterQuery) {
            call_user_func_array($this->afterQuery, array($this));
        }

        return $stmt;
    }

    public function errorCode()
    {
        $this->connect();
        return $this->pdo->errorCode();
    }

    public function errorInfo()
    {
        $this->connect();
        return $this->pdo->errorInfo();
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
        $params = array();
        $query = "SELECT * FROM $table ";

        if (is_array($where)) {
            $wheres = array();
            foreach ($where as $key => $value) {
                $wheres[] = $key . ' = ?';
            }
            $query .= "WHERE " . implode(' AND ', $wheres);
            $params = array_values($where);
        } else {
            $query .= "WHERE id = :id";
            $params = array(":id" => $where);
        }

        $data = $this->query($query, $params);

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
            'data' => $data ?: array()
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

    public function getTableByField($name)
    {
        return isset($this->tables[$name]['table']) ?
            $this->tables[$name]['table'] : $name . 's';
    }

    public function getSingular($name)
    {
        foreach ($this->tables as $table => $options) {
            if ($table == $name) {
                return $table;
            }
        }
        return rtrim($name, 's');
    }

    public function getPlural()
    {

    }
}

/**
 * @todo move to better position
 */
class Table extends  AbstractWidget
{
    protected $table;

    protected $data = array();

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
    }

    public function __get($name)
    {
        $db = $this->db;
        $fieldName = $this->db->camelCaseToUnderscore($name);

        if (isset($this->data[$fieldName])) {
            return $this->data[$fieldName];
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

    public function __call($name, $args)
    {

    }
}

class Coll extends  \ArrayObject
{
    public function toArray()
    {
        foreach ($this as $record) {
            $data[] = $record->toArray();
        }
        return $data;
    }
}