<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use PDO;
use Wei\Db\Raw;

/**
 * A database service inspired by Doctrine DBAL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Db extends Base
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
    protected $attrs = [];

    /**
     * The PDO object
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * The callback triggers before connect to database
     *
     * @var callable
     */
    protected $beforeConnect;

    /**
     * The callback triggers when fails to connect to database
     *
     * @var callable
     */
    protected $connectFails;

    /**
     * The callback triggers after connect to database
     *
     * @var callable
     */
    protected $afterConnect;

    /**
     * The callback triggers before execute query
     *
     * @var callable
     */
    protected $beforeQuery;

    /**
     * The callback triggers after execute query
     *
     * @var callable
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
    protected $tables = [];

    /**
     * The base record class when instance a new record object
     *
     * @var string
     */
    protected $recordClass = 'Wei\Record';

    /**
     * An associative array that the key is table name and the value is class name
     *
     * @var array
     */
    protected $recordClasses = [];

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
     * Whether use the options from the default db service
     *
     * @var bool
     */
    protected $global = false;

    /**
     * All executed SQL queries
     *
     * @var array
     */
    protected $queries = [];

    /**
     * The field names of table
     *
     * @var array
     */
    protected $tableFields = [];

    /**
     * The salve db configuration name
     *
     * @var string
     */
    protected $slaveDb;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['global']) && true === $options['global']) {
            $options += (array) $options['wei']->getConfig('db');
        }
        parent::__construct($options);
    }

    /**
     * Create a new instance of a SQL query builder with specified table name
     *
     * @param string $table The name of database table
     * @return Record
     */
    public function __invoke($table = null)
    {
        return $this->init($table);
    }

    /**
     * Connect to the database server
     *
     * @return bool
     * @throws \PDOException When fails to connect to database server
     */
    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        if (!$this->pdo) {
            $this->beforeConnect && call_user_func($this->beforeConnect, $this);

            $dsn = $this->getDsn();
            $attrs = $this->attrs + [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_STRINGIFY_FETCHES => true,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ];

            try {
                $this->pdo = new \PDO($dsn, $this->user, $this->password, $attrs);
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
     * Close the current connection and create a new one
     */
    public function reconnect()
    {
        $this->close();
        $this->connect();
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
        $placeholder = $this->buildSqlObject($data, ', ', false);
        $query = "INSERT INTO $table ($field) VALUES ($placeholder)";
        return $this->executeUpdate($query, array_values($data));
    }

    /**
     * Insert batch data into table
     *
     * @param string $table The name of table
     * @param array $data A two-dimensional array
     * @param string $extra A extra string concat after sql, like "ON DUPLICATE KEY UPDATE ..."
     * @return int The number of inserted rows
     */
    public function batchInsert($table, array $data, $extra = null)
    {
        $table = $this->getTable($table);
        $field = implode(', ', array_keys($data[0]));
        $placeholders = [];
        $values = [];

        foreach ($data as $row) {
            $placeholders[] = '(' . $this->buildSqlObject($row, ', ', false) . ')';
            $values = array_merge($values, array_values($row));
        }
        $placeholder = 'VALUES ' . implode(', ', $placeholders);

        $query = "INSERT INTO $table ($field) $placeholder";
        $extra && $query .= ' ' . $extra;

        return $this->executeUpdate($query, $values);
    }

    /**
     * @param mixed $table
     * @param mixed|null $extra
     * @deprecated use batchInsert instead
     */
    public function insertBatch($table, array $data, $extra = null)
    {
        return $this->batchInsert($table, $data, $extra);
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
        $set = $this->buildSqlObject($data);
        $where = $this->buildSqlObject($conditions, ' AND ');

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
        $where = $this->buildSqlObject($conditions, ' AND ');

        $query = "DELETE FROM $table" . ($where ? ' WHERE ' . $where : '');
        return $this->executeUpdate($query, array_values($conditions));
    }

    /**
     * Executes a SELECT query and return the first result
     *
     * ```php
     * // Find by the "id" key
     * $db->select('user', 1);
     *
     * // Find by specified column
     * $db->select('user', array('username' => 'twin'));
     *
     * // Find in list
     * $db->select('user', array('id' => array(1, 2, 3)));
     * ```
     *
     * @param string $table The name of table
     * @param mixed $conditions The "id" key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @return array|false An associative array containing column-value pairs
     */
    public function select($table, $conditions, $select = '*')
    {
        $data = $this->selectAll($table, $conditions, $select, 1);
        return $data ? $data[0] : false;
    }

    /**
     * Executes a SELECT query and return all results
     *
     * ```php
     * // Find by the "id" key
     * $db->selectAll('user', 1);
     *
     * // Find by specified column
     * $db->selectAll('user', array('username' => 'twin'));
     *
     * // Find in list
     * $db->selectAll('user', array('id' => array(1, 2, 3)));
     * ```
     *
     * @param string $table The name of table
     * @param mixed $conditions The "id" key value or an associative array containing column-value pairs
     * @param string $select The table columns to select
     * @param int $limit The row number to retrieve
     * @return array
     */
    public function selectAll($table, $conditions = false, $select = '*', $limit = null)
    {
        $params = [];
        $query = "SELECT $select FROM " . $this->getTable($table) . ' ';

        if (is_array($conditions)) {
            if (!empty($conditions)) {
                $query .= 'WHERE ' . implode(' = ? AND ', array_keys($conditions)) . ' = ?';
                $params = array_values($conditions);
            }
        } elseif (false !== $conditions) {
            $query .= 'WHERE id = :id';
            $params = ['id' => $conditions];
        }

        if ($limit) {
            $query .= " LIMIT $limit";
        }

        return $this->query($query, $params)->fetchAll();
    }

    /**
     * Executes a query and returns the first array result
     *
     * @param string $sql The SQL query
     * @param mixed $params The query parameters
     * @param array $types The parameter types to bind
     * @return array|false Return an array or false when no result found
     */
    public function fetch($sql, $params = [], $types = [])
    {
        return $this->query($sql, $params, $types)->fetch();
    }

    /**
     * Executes a query and returns all array results
     *
     * @param string $sql The SQL query
     * @param mixed $params The query parameters
     * @param array $types The parameter types to bind
     * @return array|false Return an array or false when no result found
     */
    public function fetchAll($sql, $params = [], $types = [])
    {
        return $this->query($sql, $params, $types)->fetchAll();
    }

    /**
     * Executes a query and returns a column value of the first row
     *
     * @param string $sql The SQL query
     * @param mixed $params The query parameters
     * @param int $column The index of column
     * @return string
     */
    public function fetchColumn($sql, $params = [], $column = 0)
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
    public function executeUpdate($sql, $params = [], $types = [])
    {
        return $this->query($sql, $params, $types, true);
    }

    /**
     * Executes an SQL statement, returning a PDOStatement object or the number of affected rows
     *
     * @param string $sql The SQL query
     * @param array $params The SQL parameters
     * @param array $types The parameter types to bind
     * @param bool $returnRows Whether returns a PDOStatement object or the number of affected rows
     * @return \PDOStatement|int
     * @throws \PDOException
     */
    public function query($sql, $params = [], $types = [], $returnRows = false)
    {
        // A select query, using slave db if configured
        if (!$returnRows && $this->slaveDb) {
            /** @var $slaveDb \Wei\Db */
            $slaveDb = $this->wei->get($this->slaveDb);
            return $slaveDb->query($sql, $params, $types, $returnRows);
        }

        $this->connect();
        $this->queries[] = $sql;
        if ($this->beforeQuery) {
            call_user_func_array($this->beforeQuery, [$sql, $params, $types, $this]);
        }

        try {
            if (!$returnRows) {
                if ($params) {
                    $stmt = $this->pdo->prepare($sql);
                    if ($types) {
                        $this->bindParameter($stmt, $params, $types);
                        $stmt->execute();
                    } else {
                        $stmt->execute((array) $params);
                    }
                } else {
                    $stmt = $this->pdo->query($sql);
                }
                $result = $stmt;
            } else {
                if ($params) {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($params);
                    $result = $stmt->rowCount();
                } else {
                    $result = $this->pdo->exec($sql);
                }
            }
        } catch (\PDOException $e) {
            // Builder exception message
            $msg = sprintf("An exception occurred while executing \"%s\" : \n\n %s", $sql, $e->getMessage());
            $params && $msg .= ', with parameters ' . json_encode($params);

            // Reset exception message
            $message = new \ReflectionProperty($e, 'message');
            $message->setAccessible(true);
            $message->setValue($e, $msg);
            throw $e;
        }

        if ($this->afterQuery) {
            call_user_func_array($this->afterQuery, [$sql, $params, $types, $this]);
        }

        return $result;
    }

    /**
     * Returns the rows number of table search by specified parameters
     *
     * @param string $table
     * @param mixed $conditions
     * @return int
     */
    public function count($table, $conditions = false)
    {
        return (int) $this->executeAggregate('COUNT', $table, '1', $conditions);
    }

    /**
     * Returns the sum of specified table field and conditions
     *
     * @param string $table
     * @param string $field
     * @param mixed $conditions
     * @return float
     */
    public function sum($table, $field, $conditions = false)
    {
        return $this->executeAggregate('SUM', $table, $field, $conditions);
    }

    /**
     * Returns the max value of specified table field and conditions
     *
     * @param string $table
     * @param string $field
     * @param mixed $conditions
     * @return float
     */
    public function max($table, $field, $conditions = false)
    {
        return $this->executeAggregate('MAX', $table, $field, $conditions);
    }

    /**
     * Returns the min value of specified table field and conditions
     *
     * @param string $table
     * @param string $field
     * @param mixed $conditions
     * @return float
     */
    public function min($table, $field, $conditions = false)
    {
        return $this->executeAggregate('MIN', $table, $field, $conditions);
    }

    /**
     * Returns the avg value of specified table field and conditions
     *
     * @param string $table
     * @param string $field
     * @param mixed $conditions
     * @return float
     */
    public function avg($table, $field, $conditions = false)
    {
        return $this->executeAggregate('AVG', $table, $field, $conditions);
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string $sequence The name of PostgreSQL sequence
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
     * Init a record instance
     *
     * @param string $table The name of database table
     * @param array $data The data for table record
     * @param bool $isNew Whether it's a new record and have not save to database
     * @return Record
     */
    public function init($table, $data = [], $isNew = true)
    {
        $class = $this->getRecordClass($table);
        return new $class([
            'wei' => $this->wei,
            'db' => $this,
            'table' => $table,
            'isNew' => $isNew,
            'data' => $data,
        ]);
    }

    /**
     * Find a record from specified table and conditions
     *
     * @param string $table The name of table
     * @param string|array $id The primary key value or an associative array containing column-value pairs
     * @return Record|false
     */
    public function find($table, $id)
    {
        $data = $this->select($table, $id);
        return $data ? $this->init($table, $data, false) : false;
    }

    /**
     * Find a record from specified table and conditions
     * and throws 404 exception when record not found
     *
     * @param string $table
     * @param string|array $id The primary key value or an associative array containing column-value pairs
     * @return Record
     * @throws \Exception
     */
    public function findOne($table, $id)
    {
        return $this->init($table)->findOne($id);
    }

    /**
     * Find a record, if not found, create a new one from specified data
     *
     * @param string $table The name of table
     * @param string $id The primary key value or an associative array containing column-value pairs
     * @param array $data The data to create a new record when record not found
     * @return Record
     */
    public function findOrInit($table, $id, $data = [])
    {
        return $this->init($table)->findOrInit($id, $data);
    }

    /**
     * Find records from specified table and conditions
     *
     * @param string $table The name of table
     * @param mixed $where The primary key value or an associative array containing column-value pairs
     * @return Record
     */
    public function findAll($table, $where = false)
    {
        return $this->init($table)->findAll($where);
    }

    /**
     * Set the record class for specified table
     *
     * @param string $table
     * @param string $class
     * @return $this
     */
    public function addRecordClass($table, $class)
    {
        $this->recordClasses[$table] = $class;
        return $this;
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
        if (false !== strpos($table, '.')) {
            [$dbname, $table] = explode('.', $table, 2);
            return $dbname . '.' . $this->tablePrefix . $table;
        }
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
     * Returns the hostname on which the database server resides
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns the port on which the database server is running
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Returns the name of the database
     *
     * @return string
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * Returns the name of PDO driver
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Returns the PDO DSN
     *
     * @return string
     * @throws \RuntimeException When database driver is unsupported
     */
    public function getDsn()
    {
        if ($this->dsn) {
            return $this->dsn;
        }

        $dsn = $this->driver . ':';
        switch ($this->driver) {
            case 'mysql':
                $this->host && $dsn .= 'host=' . $this->host . ';';
                $this->port && $dsn .= 'port=' . $this->port . ';';
                $this->dbname && $dsn .= 'dbname=' . $this->dbname . ';';
                $this->unixSocket && $dsn .= 'unix_socket=' . $this->unixSocket . ';';
                $this->charset && $dsn .= 'charset=' . $this->charset;
                break;

            case 'sqlite':
                $dsn .= $this->path;
                break;

            case 'pgsql':
                $this->host && $dsn .= 'host=' . $this->host . ';';
                $this->port && $dsn .= 'port=' . $this->port . ';';
                $this->dbname && $dsn .= 'dbname=' . $this->dbname;
                break;

            default:
                throw new \RuntimeException(sprintf('Unsupported database driver: %s', $this->driver));
        }
        return $dsn;
    }

    /**
     * Returns the original PDO object
     *
     * @return \PDO
     */
    public function getPdo()
    {
        $this->connect();
        return $this->pdo;
    }

    /**
     * Returns the prefix string of table name
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * Returns the last executed SQL query
     *
     * @return string
     */
    public function getLastQuery()
    {
        return end($this->queries);
    }

    /**
     * Returns all executed SQL queries
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     *  Returns the name of fields of specified table
     *
     * @param string $table
     * @param bool $withPrefix
     * @return array
     * @throws \PDOException
     */
    public function getTableFields($table, $withPrefix = false)
    {
        $fullTable = $withPrefix ? $table : $this->getTable($table);
        if (isset($this->tableFields[$fullTable])) {
            return $this->tableFields[$fullTable];
        } else {
            $fields = [];
            switch ($this->driver) {
                case 'mysql':
                    $tableInfo = $this->fetchAll("SHOW COLUMNS FROM $fullTable");
                    $fields = $this->filter($tableInfo, 'Field');
                    break;

                case 'sqlite':
                    $tableInfo = $this->fetchAll("PRAGMA table_info($fullTable)");
                    $fields = $this->filter($tableInfo, 'name');
                    break;

                case 'pgsql':
                    $tableInfo = $this->fetchAll(
                        'SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE table_catalog = ? AND table_name = ?
                        ORDER BY dtd_identifier ASC',
                        [$this->dbname, $fullTable]
                    );
                    $fields = $this->filter($tableInfo, 'column_name');
            }
            if (empty($fields)) {
                // For SQLite and PostgreSQL
                throw new \PDOException(sprintf('Table or view "%s" not found', $fullTable));
            }
            return $this->tableFields[$table] = $fields;
        }
    }

    /**
     * Switch to specified database
     *
     * @param string $database
     * @return $this
     */
    public function useDb($database)
    {
        switch ($this->driver) {
            case 'mysql':
                $this->query("USE `$database`");
                $this->dbname = $database;
                break;

            default:
                throw new \RuntimeException(sprintf(
                    'Unsupported switching database for current driver: %s',
                    $this->driver
                ));
        }
        return $this;
    }

    /**
     * Set the prefix string of table name
     *
     * @param string $tablePrefix
     * @return $this
     * @svc
     */
    protected function setTablePrefix(string $tablePrefix): self
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * Execute a function in a transaction
     *
     * @param callable $fn
     * @throws \Exception
     * @svc
     */
    protected function transactional(callable $fn)
    {
        $pdo = $this->getPdo();
        $pdo->beginTransaction();
        try {
            call_user_func($fn);
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Return the raw value
     *
     * @param Raw $raw
     * @return mixed
     */
    public function getRawValue(Raw $raw)
    {
        return $raw->getValue();
    }

    /**
     * Check if a variable is a raw object
     *
     * @param mixed $raw
     * @return bool
     */
    public function isRaw($raw): bool
    {
        return $raw instanceof Raw;
    }

    /**
     * Create a raw value instance
     *
     * @param mixed $value
     * @return Raw
     * @svc
     */
    protected function raw($value): Raw
    {
        return new Raw($value);
    }

    protected function filter($data, $name)
    {
        $fields = [];
        foreach ($data as $row) {
            $fields[] = $row[$name];
        }
        return $fields;
    }

    protected function buildSqlObject(array &$data, $glue = ', ', $withColumn = true)
    {
        $query = [];
        foreach ($data as $key => $value) {
            $column = ($withColumn ? ($key . ' = ') : '');

            if (false === $value) {
                // Avoid MySQL default sql mode "STRICT_TRANS_TABLES"
                // complains "Incorrect integer value: '' for column ..."
                // Note that if the column is string type, it will insert/update a "0" string
                $data[$key] = 0;
                $query[] = $column . '?';
            } elseif ($this->isRaw($value)) {
                $query[] = $column . $this->getRawValue($value);
                unset($data[$key]);
            } else {
                $query[] = $column . '?';
            }
        }

        return implode($glue, $query);
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
        !is_array($params) && $params = [$params];
        !is_array($types) && $types = [$types];

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
                ++$index;
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

    /**
     * Execute a query with aggregate function
     *
     * @param string $fn
     * @param string $table
     * @param string $field
     * @param mixed $conditions
     * @return float
     */
    protected function executeAggregate($fn, $table, $field, $conditions)
    {
        $data = $this->selectAll($table, $conditions, $fn . '(' . $field . ')');
        return $data ? (float) current($data[0]) : 0.0;
    }
}
