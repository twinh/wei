<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A cache widget that stored data in databases
 *
 * @author  Twin Huang <twinhuang@qq.com>
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
        $db = $this->db;
        $driver = $db->getDriver();

        $tableExistsSql = sprintf($this->checkTableSqls[$driver], $this->table);
        if ($this->checkTable && !$this->db->fetchColumn($tableExistsSql)) {
            $createTableSql = sprintf($this->createTableSqls[$driver], $this->table);
            $db->executeUpdate($createTableSql);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doGet($key)
    {
        $result = $this->db->select($this->table, $key);
        return $result ? unserialize($result['value']) : false;
    }

    /**
     * {@inheritdoc}
     */
    protected function doSet($key, $value, $expire = 0)
    {
        $data = array(
            'value' => serialize($value),
            'lastModified' => date('Y-m-d H:i:s'),
            'expire' => date('Y-m-d H:i:s', $expire ? time() + $expire: 2147483647)
        );
        $identifier = array(
            'id' => $key
        );

        if ($this->doExists($key)) {
            // In mysql, the rowCount method return 0 when data is not modified,
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
    protected function doRemove($key)
    {
        return (bool)$this->db->delete($this->table, array('id' => $key));
    }

    /**
     * {@inheritdoc}
     */
    protected function doExists($key)
    {
        return (bool)$this->db->select($this->table, $key);
    }

    /**
     * {@inheritdoc}
     */
    protected function doAdd($key, $value, $expire = 0)
    {
        if ($this->doExists($key)) {
            return false;
        } else {
            return $this->doSet($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doReplace($key, $value, $expire = 0)
    {
        if (!$this->doExists($key)) {
            return false;
        } else {
            return $this->doSet($key, $value, $expire);
        }
    }

    /**
     * Note: This method is not an atomic operation
     *
     * {@inheritdoc}
     */
    protected function doIncr($key, $offset = 1)
    {
        $value = $this->doGet($key) + $offset;

        return $this->doSet($key, $value) ? $value : false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return (bool)$this->db->executeUpdate("DELETE FROM {$this->table}");
    }
}
