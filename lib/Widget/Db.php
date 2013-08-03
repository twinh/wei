<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

use PDO;
use Symfony\Component\Config\Definition\Exception\Exception;
use Widget\Db\Collection;

/**
 * A database widget that compatible with Doctrine DBAL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Db extends AbstractWidget
{
    /**
     * The name of PDO driver, could be mysql, sqlite or pgsql
     *
     * @var string
     */
    protected $driver = 'mysql';

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
     * The hostname on which the database server resides, for mysql and pgsql
     *
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * The port on which the database server is running, for mysql and pgsql
     *
     * @var string
     */
    protected $port;

    /**
     * The name of the database, for mysql and pgsql
     *
     * @var string
     */
    protected $dbname;

    /**
     * The MySQL Unix socket (shouldn't be used with host or port), for mysql only
     *
     * @var string
     */
    protected $unixSocket;

    /**
     * The character set, for mysql only
     *
     * @var string
     */
    protected $charset;

    /**
     * The path for sqlite database
     *
     * @var string
     */
    protected $path;

    /**
     * Whether the sqlite database is in memory
     *
     * @var bool
     */
    protected $memory = false;

    /**
     * The dsn parameter for PDO constructor
     *
     * @var string
     */
    protected $dsn;

    /**
     * A key=>value array of driver-specific connection attributes
     *
     * @var array
     * @link http://www.php.net/manual/zh/pdo.setattribute.php
     */
    protected $attrs = array();

    /**
     * The PDO object
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * The callback triggers before connect to database
     *
     * @var callback
     */
    protected $beforeConnect;

    /**
     * The callback triggers when fails to connect to database
     *
     * @var callback
     */
    protected $connectFails;

    /**
     * The callback triggers after connect to database
     *
     * @var callback
     */
    protected $afterConnect;

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
     * The prefix of table name
     *
     * @var string
     */
    protected $tablePrefix;

    /**
     * Whether use the callbacks from default db widget
     *
     * @var bool
     */
    protected $global = false;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Uses callbacks from default db widget if global option is true
        if (true == $this->global && $this !== $this->db) {
            $callbacks = array(
                'beforeConnect', 'connectFails', 'afterConnect',
                'beforeQuery', 'afterQuery'
            );
            foreach ($callbacks as $callback) {
                $this->$callback = $this->db->getOption($callback);
            }
        }
    }

    /**
     * Create a new instance of a SQL query builder with specified table name
     *
     * @param string $table The name of database table
     * @return Db\QueryBuilder
     */
    public function __invoke($table)
    {
        return $this->createQueryBuilder($table);
    }

    /**
     * Connect to the database server
     *
     * @throws \PDOException When fails to connect to database server
     * @return boolean
     */
    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        if (!$this->pdo) {
            $this->beforeConnect && call_user_func($this->beforeConnect, $this);

            $attrs = $this->attrs + array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_STRINGIFY_FETCHES => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            // Build PDO DSN
            $dsn = $this->driver . ':';
            $this->dsn = &$dsn;
            switch ($this->driver) {
                case 'mysql':
                    $this->host && $dsn .= 'host=' . $this->host . ';';
                    $this->port && $dsn .= 'port=' . $this->port . ';';
                    $this->dbname && $dsn .= 'dbname=' . $this->dbname . ';';
                    $this->unixSocket && $dsn .= 'unix_socket=' . $this->unixSocket . ';';
                    $this->charset && $dsn .= 'charset=' . $this->charset . ';';
                    break;

                case 'sqlite':
                    if ($this->path) {
                        $dsn .= $this->path;
                    } elseif ($this->memory) {
                        $dsn .= ':memory:';
                    }
                    break;

                case 'pgsql':
                    $this->host && $dsn .= 'host=' . $this->host . ';';
                    $this->port && $dsn .= 'port=' . $this->port . ';';
                    $this->dbname && $dsn .= 'dbname=' . $this->dbname . ';';
                    break;

                default:
                    throw new \RuntimeException(sprintf('Unsupported database driver: %s', $this->driver));
            }

            try {
                $this->pdo = new PDO($dsn, $this->user, $this->password, $attrs);
            } catch (\PDOException $e) {
                $this->connectFails && call_user_func($this->connectFails, $this, $e);
                throw $e;
            }

            $this->afterConnect && call_user_func($this->afterConnect, $this, $this->pdo);
        }

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
     * @param array $data An associative array containing column-value pairs
     * @return int The number of affected rows
     */
    public function insert($table, array $data)
    {
        $table = $this->getTable($table);
        $field = implode(', ', array_keys($data));
        $placeholder = implode(', ', array_pad(array(), count($data), '?'));

        $query = "INSERT INTO $table ($field) VALUES ($placeholder)";

        return $this->executeUpdate($query, array_values($data));
    }

    /**
     * Executes a UPDATE query
     *
     * @param string $table The name of table
     * @param array $data An associative array containing column-value pairs
     * @param array $conditions The conditions to search records
     * @return int The number of affected rows
     */
    public function update($table, array $data, array $conditions)
    {
        $table = $this->getTable($table);
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $where = implode(' = ? AND ', array_keys($conditions)) . ' = ?';

        $query = "UPDATE $table SET $set WHERE $where";
        $params = array_merge(array_values($data), array_values($conditions));

        return $this->executeUpdate($query, $params);
    }

    /**
     * Executes a DELETE query
     *
     * @param string $table The name of table
     * @param array $conditions The conditions to search records
     * @return int The number of affected rows
     */
    public function delete($table, array $conditions)
    {
        $table = $this->getTable($table);

        $query = "DELETE FROM $table WHERE " . implode(' = ? AND ', array_keys($conditions)) . ' = ?';

        return $this->executeUpdate($query, array_values($conditions));
    }

    /**
     * Executes a SELECT query and return the first result
     *
     * @param string $table The name of table
     * @param string|array $conditions The primary key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @internal param array|string $value The criteria to search record
     * @internal param string $column The table column to search
     * @return array An associative array containing column-value pairs
     */
    public function select($table, $conditions, $select = '*')
    {
        $data = $this->selectAll($table, $conditions, $select, 1);
        return $data ? $data[0] : false;
    }

    /**
     * Executes a SELECT query and return all results
     *
     * @param string $table The name of table
     * @param bool $conditions The primary key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @param int $limit The record number to retrieve
     * @return array
     */
    public function selectAll($table, $conditions = false, $select = '*', $limit = null)
    {
        $params = array();
        $query = "SELECT $select FROM " . $this->getTable($table) . ' ';

        if (is_array($conditions)) {
            if (!empty($conditions)) {
                $query .= "WHERE " . implode(' = ? AND ', array_keys($conditions)) . ' = ?';
                $params = array_values($conditions);
            }
        } elseif ($conditions !== false) {
            $query .= "WHERE id = :id";
            $params = array('id' => $conditions);
        }

        if ($limit) {
            $query .= " LIMIT $limit";
        }

        return $this->query($query, $params)->fetchAll();
    }

    /**
     * Returns the rows number of table search by specified parameters
     *
     * @param string $table
     * @param array $conditions
     * @return int
     */
    public function count($table, $conditions = false)
    {
        $data = $this->selectAll($table, $conditions, 'COUNT(1)');
        return $data ? current($data[0]) : 0;
    }

    /**
     * Executes a query and returns the first array result
     *
     * @param string $sql The SQL query
     * @param array|string $params The query parameters
     * @param array $types The parameter types to bind
     * @return array|false Return an array or false when no result found
     */
    public function fetch($sql, $params = false, $types = array())
    {
        return $this->query($sql, $params, $types)->fetch();
    }

    /**
     * Executes a query and returns all array results
     *
     * @param string $sql The SQL query
     * @param array $params The query parameters
     * @param array $types The parameter types to bind
     * @return array|false Return an array or false when no result found
     */
    public function fetchAll($sql, $params = array(), $types = array())
    {
        return $this->query($sql, $params, $types)->fetchAll();
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
     * @param array $types The parameter types to bind
     * @return int The number of affected rows
     */
    public function executeUpdate($sql, $params = array(), $types = array())
    {
        $this->connect();

        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, array($sql, $params, $types, $this));
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
     * @param array $types The parameter types to bind
     * @throws \RuntimeException When a PDOException raise
     * @return \PDOStatement
     */
    public function query($sql, $params = array(), $types = array())
    {
        $this->connect();

        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, array($sql, $params, $types, $this));
        }

        try {
            if ($params) {
                $stmt = $this->pdo->prepare($sql);
                if ($types) {
                    $this->bindParameter($stmt, $params, $types);
                    $stmt->execute();
                } else {
                    $stmt->execute((array)$params);
                }
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
     * @param string $name The name of PostgreSQL sequence
     * @return string
     */
    public function lastInsertId($sequence = null)
    {
        return $this->pdo->lastInsertId($sequence);
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
     * @param string $table The name of database table
     * @return Db\QueryBuilder
     */
    public function createQueryBuilder($table = null)
    {
        $qb = new Db\QueryBuilder($this);
        return $qb->from($table);
    }

    /**
     * Create a new record instance
     *
     * @param string $table The name of database table
     * @param array $data The data for table record
     * @param bool $isNew Whether it's a new record and have not save to database
     * @return Db\Record
     */
    public function create($table, $data = array(), $isNew = true)
    {
        $class = $this->getRecordClass($table);
        return new $class(array(
            'widget'    => $this->widget,
            'db'        => $this,
            'table'     => $this->getTable($table),
            'isNew'     => $isNew,
            'data'      => $data,
        ));
    }

    /**
     * Find a record from specified table and conditions
     *
     * ```php
     * // Find by primary key
     * $db->find('user', 1);
     *
     * // Find by specified column
     * $db->find('user', array('username' => 'twin'));
     *
     * // Find in list
     * $db->find('user', array('id' => array(1, 2, 3)));
     * ```
     *
     * @param string $table The name of table
     * @param string|array $id The primary key value
     * @return Db\Record|false
     */
    public function find($table, $id)
    {
        $data = $this->select($table, $id);

        return $data ? $this->create($table, $data, false) : false;
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
            return $this->create($table, $record, false);
        } else {
            return $this->create($table, array('id' => $id) + $data);
        }
    }

    /**
     * Find records from specified table and conditions
     *
     * ```php
     * // Find by primary key
     * $db->findAll('user', 1);
     *
     * // Find by specified column
     * $db->findAll('user', array('username' => 'twin'));
     *
     * // Find in list
     * $db->findAll('user', array('id' => array(1, 2, 3)));
     * ```
     *
     * @param string $table The name of database table
     * @param array|false $where The primary key value or an associative array containing column-value pairs
     * @return Db\Collection
     */
    public function findAll($table, $where = false)
    {
        $data = $this->selectAll($table, $where);

        $records = array();
        foreach ($data as $row) {
            $records[] = $this->create($table, $row, false);
        }

        return new $this->collectionClass($records);
    }

    /**
     * Find a record from specified table and conditions
     *
     * @param string $name
     * @param array $args
     * @return Db\Record|false
     */
    public function __call($name, $args)
    {
        return $this->find($name, isset($args[0]) ? $args[0] : false);
    }

    /**
     * Returns the record class name of table
     *
     * @param string $table The name of table
     * @return string The record class name for table
     */
    public function getRecordClass($table)
    {
        if (isset($this->recordClasses[$table])) {
            return $this->recordClasses[$table];
        }

        if ($this->recordNamespace) {
            $class = $this->recordNamespace . '\\' . implode('', array_map('ucfirst', explode('_', $table)));
            if (class_exists($class)) {
                return $class;
            }
        }

        return $this->recordClass;
    }

    /**
     * Returns the full table name with prefix
     *
     * @param string $table
     * @return string
     */
    public function getTable($table)
    {
        return $this->tablePrefix . $table;
    }

    /**
     * Returns the database username
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns the database password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Bind parameters to statement object
     *
     * @param \PDOStatement $stmt
     * @param array $params
     * @param array $types
     */
    protected function bindParameter(\PDOStatement $stmt, $params, $types)
    {
        if (!is_array($params)) {
            $params = array($params);
        }
        if (!is_array($types)) {
            $types = array($types);
        }

        $isIndex = is_int(key($params));
        $index = 1;

        foreach ($params as $name => $param) {
            // Number index parameters
            if ($isIndex) {
                if (isset($types[$index - 1])) {
                    $stmt->bindValue($index, $param, $types[$index - 1]);
                } else {
                    $stmt->bindValue($index, $param);
                }
                $index++;
            // Named parameters
            } else {
                if (isset($types[$name])) {
                    $stmt->bindValue($name, $param, $types[$name]);
                } else {
                    $stmt->bindValue($name, $param);
                }
            }
        }
    }
}