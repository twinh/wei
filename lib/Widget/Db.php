<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use PDO;
use Widget\Db\Collection;

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

    /**
     * The database table definitions
     *
     * @var array
     */
    protected $tables = array();

    /**
     * The base record class when instance a new record object
     *
     * @var string
     */
    protected $recordClass = '\Widget\Record';

    /**
     * An associative array that the key is table name and the value is class name
     *
     * @var array
     */
    protected $recordClasses = array();

    /**
     * The record namespace
     *
     * @var string
     */
    protected $recordNamespace;

    public function __invoke($table, $alias = null)
    {
        return $this->from($table, $alias);
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
     * Check if has connected to the database server
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * Close the database connection
     */
    public function close()
    {
        $this->pdo = null;
        $this->isConnected = false;
    }

    /**
     * Executes an INSERT query to insert specified data into table
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
     * Executes a DELETE query
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
     * Executes a SELECT query and return the first result
     *
     * @param string $table The name of table
     * @param string|array $where The primary key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @internal param array|string $value The criteria to search record
     * @internal param string $column The table column to search
     * @return array An associative array containing column-value pairs
     */
    public function select($table, $where, $select = '*')
    {
        $data = $this->selectAll($table, $where, $select, 1);
        return $data ? $data[0] : false;
    }

    /**
     * Executes a SELECT query and return all results
     *
     * @param string $table The name of table
     * @param bool $where The primary key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @param int $limit The record number to retrieve
     * @return array
     */
    public function selectAll($table, $where = false, $select = '*', $limit = null)
    {
        $params = array();
        $query = "SELECT $select FROM $table ";

        if (is_array($where)) {
            // Associative array
            if (1) {
                $query .= "WHERE " . implode(' = ? AND ', array_keys($where)) . ' = ?';
                $params = array_values($where);
                // Indexed array
            } else {

            }
        } elseif ($where !== false) {
            $query .= "WHERE id = :field";
            $params = array('field' => $where);
        }

        if ($limit) {
            $query .= " LIMIT $limit";
        }

        return $this->query($query, $params)->fetchAll();
    }

    /**
     * Executes a query and returns the first result
     *
     * @param string $sql The SQL query
     * @param array|string $params The query parameters
     * @return array|false Return an array or false when no result found
     */
    public function fetch($sql, $params = array())
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Executes a query and returns all results
     *
     * @param string $sql The SQL query
     * @param array $params The query parameters
     * @return array|false Return an array or false when no result found
     */
    public function fetchAll($sql, $params = array())
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Executes a query and returns a column value of the first row
     *
     * @param $sql The SQL query
     * @param array $params The query parameters
     * @param int $column The index of column
     * @return string
     */
    public function fetchColumn($sql, $params = array(), $column = 0)
    {
        return $this->query($sql, $params)->fetchColumn($column);
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows
     *
     * @param string $sql The SQL query
     * @param array $params The query parameters
     * @return int The number of affected rows
     */
    public function executeUpdate($sql, $params = array())
    {
        $this->connect();

        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, array($sql, $params, $this));
        }

        if ($params) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->rowCount();
        } else {
            $result = $this->pdo->exec($sql);
        }

        if ($this->afterQuery) {
            call_user_func_array($this->afterQuery, array($this));
        }

        return $result;
    }

    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object
     *
     * @param string $sql The SQL query
     * @param array $params The SQL parameters
     * @return \PDOStatement
     */
    public function query($sql, $params = array())
    {
        $this->connect();

        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, array($sql, $params, $this));
        }

        try {
            if ($params) {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute((array)$params);
            } else {
                $stmt = $this->pdo->query($sql);
            }
        } catch (\PDOException $e) {
            $msg = 'An exception occurred while executing "' . $sql . '"';
            $msg .= ":\n\n". $e->getMessage();
            throw new \RuntimeException($msg, 0, $e);
        }

        if ($this->afterQuery) {
            call_user_func_array($this->afterQuery, array($this));
        }

        return $stmt;
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
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
     * Create a new instance of a SQL query builder.
     *
     * @return Db\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new Db\QueryBuilder($this);
    }

    public function from($table, $alias = null)
    {
        return $this
            ->createQueryBuilder()
            ->from($table, $alias);
    }

    /**
     * Create a new record instance
     *
     * @param $table
     * @param $data
     * @return Record
     */
    public function create($table, $data = array())
    {
        $class = $this->getRecordClass($table);
        return new $class(array(
            'widget'    => $this->widget,
            'db'        => $this,
            'table'     => $this->camelCaseToUnderscore($table),
            'data'      => $data
        ));
    }

    /**
     *
     *
     * @param $table
     * @param $id
     * @return Record
     */
    public function find($table, $id)
    {
        $data = $this->select($table, $id);

        return $data ? $this->create($table, $data) : false;
    }

    /**
     * Fetch data by specified
     *
     * @param string $table
     * @param array $where
     * @return Collection
     */
    public function findAll($table, $where = null)
    {
        $data = $this->selectAll($table, $where);

        $records = array();
        foreach ($data as $row) {
            $records[] = $this->create($table, $row);
        }

        return new Collection($records);
    }

    /**
     * Returns a new table instance, alias of `create`
     *
     * @param string $name
     * @return Record
     */
    public function __get($name)
    {
        return $this->create($name);
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

    public function getTableRelation($table)
    {
        return isset($this->tables[$table]['relations']) ? $this->tables[$table]['relations'] : array();
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

    /**
     * Returns the record class name of table
     *
     * @param string $name The name of table
     * @return string The record class name for table
     */
    public function getRecordClass($name)
    {
        if (isset($this->recordClasses[$name])) {
            return $this->recordClasses[$name];
        }

        if ($this->recordNamespace) {
            $class = $this->recordNamespace . '\\' . ucfirst($this->camelCaseToUnderscore($name));
            if (class_exists($class)) {
                return $class;
            }
        }

        return $this->recordClass;
    }
}