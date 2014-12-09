<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that stored data in databases
 *
 * @author  Twin Huang <twinhuang@qq.com>
 * @property Db $db A database service
 */
class DbCache extends BaseCache
{
    /**
     * The cache table name
     *
     * @var string
     */
    protected $table = 'cache';

    /**
     * Whether check if table is exists or not
     *
     * @var bool
     */
    protected $checkTable = true;

    /**
     * The SQL to check if table exists
     *
     * @var array
     */
    protected $checkTableSqls = array(
        'mysql'     => "SHOW TABLES LIKE '%s'",
        'sqlite'    => "SELECT name FROM sqlite_master WHERE type='table' AND name='%s'",
        'pgsql'     => "SELECT true FROM pg_tables WHERE tablename = '%s'"
    );

    /**
     * The SQL to create cache table
     *
     * @var array
     */
    protected $createTableSqls = array(
        'mysql'  => "CREATE TABLE %s (id VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, expire DATETIME NOT NULL, lastModified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB",
        'sqlite' => "CREATE TABLE %s (id VARCHAR(255) NOT NULL, value CLOB NOT NULL, expire DATETIME NOT NULL, lastModified DATETIME NOT NULL, PRIMARY KEY(id))",
        'pgsql'  => "CREATE TABLE %s (id VARCHAR(255) NOT NULL, value TEXT NOT NULL, expire TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, lastModified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))",
    );

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->prepareTable();
    }

    /**
     * Check if table exists, if not exists, create table
     */
    public function prepareTable()
    {
        $driver = $this->db->getDriver();
        $tableExistsSql = sprintf($this->checkTableSqls[$driver], $this->table);
        if ($this->checkTable && !$this->db->fetchColumn($tableExistsSql)) {
            $createTableSql = sprintf($this->createTableSqls[$driver], $this->table);
            $this->db->executeUpdate($createTableSql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $expire = null, $fn = null)
    {
        if ($this->exists($key)) {
            $result = $this->db->select($this->table, $this->namespace . $key);
            $result = unserialize($result['value']);
        } else {
            $result = false;
        }
        return $this->processGetResult($key, $result, $expire, $fn);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $data = array(
            'value' => serialize($value),
            'lastModified' => date('Y-m-d H:i:s'),
            'expire' => date('Y-m-d H:i:s', $expire ? time() + $expire : 2147483647)
        );
        $identifier = array(
            'id' => $this->namespace . $key
        );

        if ($this->exists($key)) {
            // In MySQL, the rowCount method return 0 when data is not modified,
            // so check errorCode to make sure it executed success
            $result = $this->db->update($this->table, $data, $identifier) || '0000' == $this->db->errorCode();
        } else {
            $result = $this->db->insert($this->table, $data + $identifier);
        }
        return (bool)$result;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return (bool)$this->db->delete($this->table, array('id' => $this->namespace . $key));
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $result = $this->db->select($this->table, $this->namespace . $key);

        if (!$result) {
            return false;
        }
        if ($result['expire'] < date('Y-m-d H:i:s')) {
            $this->remove($key);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        if ($this->exists($key)) {
            return false;
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        if (!$this->exists($key)) {
            return false;
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        $value = $this->get($key) + $offset;
        return $this->set($key, $value) ? $value : false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return (bool)$this->db->executeUpdate("DELETE FROM {$this->table}");
    }
}
