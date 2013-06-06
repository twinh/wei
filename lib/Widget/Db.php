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
    protected $recordClass = 'Widget\Db\Record';

    /**
     * The collection class to store database records
     *
     * @var string
     */
    protected $collectionClass = 'Widget\Db\Collection';

    /**
     * An associative array that the key is table name and the value is class name
     *
     * @var array
     */
    protected $recordClasses = array();

    /**
     * The record namespace without ending "\"
     *
     * @var string
     */
    protected $recordNamespace;

    /**
     * Create a new instance of a SQL query builder with specified table and alias
     *
     * @param string $table The name of database table
     * @param string $alias The name of table alias
     * @return Db\QueryBuilder
     */
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
     * @param string $sql The SQL query
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
     * @throws \RuntimeException When a PDOException raise
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
     * Create a new instance of a SQL query builder
     *
     * @return Db\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new Db\QueryBuilder($this);
    }

    /**
     * Create a new instance of a SQL query builder with specified table and alias
     *
     * @param string $table The name of database table
     * @param string $alias The name of table alias
     * @return Db\QueryBuilder
     */
    public function from($table, $alias = null)
    {
        return $this
            ->createQueryBuilder()
            ->from($table, $alias);
    }

    /**
     * Create a new record instance
     *
     * @param string $table The name of database table
     * @param array $data The data for table record
     * @param bool $isNew Whether it's a new record and have not save to database
     * @return Db\Record
     */
    public function create($table, $data = array(), $isNew = false)
    {
        $class = $this->getRecordClass($table);
        return new $class(array(
            'widget'    => $this->widget,
            'db'        => $this,
            'table'     => $table,
            'isNew'     => $isNew,
            'data'      => $data,
        ));
    }

    /**
     * Find a record
     *
     * @param string $table The name of table
     * @param string|array $id The primary key value
     * @return Db\Record|false
     */
    public function find($table, $id)
    {
        $data = $this->select($table, $id);

        return $data ? $this->create($table, $data) : false;
    }

    /**
     * Find a record, if not found, create a new one from specified data
     *
     * @param string $table The name of table
     * @param string $id The primary key value
     * @param array $data The data to create a new record when record not found
     * @return Db\Record
     */
    public function findOrCreate($table, $id, $data = array())
    {
        $record = $this->select($table, $id);

        if ($record) {
            return $this->create($table, $record);
        } else {
            return $this->create($table, $data + array('id' => $id), true);
        }
    }

    /**
     * Fetch records by specified conditions
     *
     * @param string $table The name of database table
     * @param array|false $where The primary key value or an associative array containing column-value pairs
     * @return Collection
     */
    public function findAll($table, $where = false)
    {
        $data = $this->selectAll($table, $where);

        $records = array();
        foreach ($data as $row) {
            $records[] = $this->create($table, $row);
        }

        return new $this->collectionClass($records);
    }

    /**
     * Returns a new table instance, alias of `create`
     *
     * @param string $name
     * @return Db\Record
     */
    public function __get($name)
    {
        return $this->create($name, array(), true);
    }

    public function __call($name, $args)
    {
        return $this->find($name, $args[0]);
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
            $class = $this->recordNamespace . '\\' . implode('', array_map('ucfirst', explode('_', $name)));
            if (class_exists($class)) {
                return $class;
            }
        }

        return $this->recordClass;
    }
}