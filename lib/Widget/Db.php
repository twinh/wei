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

    /**
     * The callback triggers before execute query
     *
     * @var callback
     */
    protected $beforeQuery;

    /**
     * The callback triggers after execute query
     *
     * @var callback
     */
    protected $afterQuery;

    /**
     * Whether connected to the database server
     *
     * @var bool
     */
    protected $isConnected = false;

    /**
     * The name of PDO driver
     *
     * @var string
     */
    private $driver;

    public function __invoke()
    {
        return $this;
    }

    /**
     * Connect to the database server
     *
     * @return boolean
     */
    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        if (!$this->pdo) {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        $this->driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $this->isConnected = true;

        return true;
    }

    /**
     * Inserts specified data into table
     *
     * @param string $table The name of table
     * @param array $values An associative array containing column-value pairs
     * @return int The number of affected rows
     */
    public function insert($table, array $values)
    {
        $field = implode(', ', array_keys($values));
        $placeholder = implode(', ', array_pad(array(), count($values), '?'));

        $query = "INSERT INTO $table ($field) VALUES ($placeholder)";

        return $this->executeUpdate($query, array_values($values));
    }

    /**
     * Executes a UPDATE query
     *
     * @param string $table The name of table
     * @param array $data An associative array containing column-value pairs
     * @param array $identifier The criteria to search records
     * @return int The number of affected rows
     */
    public function update($table, $data, $identifier)
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $where = implode(' = ? AND ', array_keys($identifier)) . ' = ?';

        $query = "UPDATE $table SET $set WHERE $where";
        $params = array_merge(array_values($data), array_values($identifier));

        return $this->executeUpdate($query, $params);
    }

    /**
     * Exeuctes a DELETE query
     *
     * @param string $table The name of table
     * @param array $identifier The criteria to search records
     * @return int The number of affected rows
     */
    public function delete($table, $identifier)
    {
        $query = "DELETE FROM $table WHERE " . implode(' = ? AND ', array_keys($identifier)) . ' = ?';

        return $this->executeUpdate($query, array_values($identifier));
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows
     *
     * @param $query The sql query
     * @param array $params
     * @return int The number of affected rows
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

    /**
     * Fetch the SQLSTATE associated with the last operation on the database handle
     *
     * @return mixed
     */
    public function errorCode()
    {
        $this->connect();
        return $this->pdo->errorCode();
    }

    /**
     * Fetch extended error information associated with the last operation on the database handle
     *
     * @return array
     */
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

    /**
     * Fetch data by specified
     *
     * @param string $table
     * @param array $where
     * @return \Widget\Coll
     */
    public function findAll($table, $where = null, $orderBy = null, $limit = null, $offset = null)
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
        } elseif ($where !== null) {
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

        if ($data) {
            $table = new Table(array(
                'widget' => $this->widget,
                'db' => $this,
                'table' => $table,
                'data' => $data ?: array()
            ));
            return $table;
        } else {
            return $data;
        }
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
        return $this->find($this->camelCaseToUnderscore($name), $args[0]);
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

    public function from()
    {

    }

    public function select()
    {

    }

    public function getColumns($table)
    {
        if (isset($this->tables[$table]['columns'])) {
            return $this->tables[$table]['columns'];
        }
        return false;
    }
}

class QueryBuilder
{

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
}

class Coll extends \ArrayObject
{
    public function toArray()
    {
        foreach ($this as $record) {
            $data[] = $record->toArray();
        }
        return $data;
    }
}