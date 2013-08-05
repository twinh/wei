<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */
namespace Widget;

use Widget\Stdlib\AbstractCache;

/**
 * A database cache widget
 *
 * @author  Twin Huang <twinhuang@qq.com>
 */
class DbCache extends AbstractCache
{
    /**
     * The cache table name
     *
     * @var string
     */
    protected $table = 'cache';

    protected $checkTableSqls = array(
        'mysql'     => "SHOW TABLES LIKE '%s'",
        'sqlite'    => "SELECT name FROM sqlite_master WHERE type='table' AND name='%s'",
        'pgsql'     => "SELECT true FROM pg_tables WHERE tablename = '%s'"
    );

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
        if (!$this->tableExists()) {
            $sql = sprintf($this->createTableSqls[$this->db->getDriver()], $this->table);
            $this->db->executeUpdate($sql);
        }
    }

    protected function tableExists()
    {
        $sql = sprintf($this->checkTableSqls[$this->db->getDriver()], $this->table);
        return (bool)$this->db->fetchColumn($sql);
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
    public function set($key, $value, $expire = 0)
    {
        $data = array(
            'value' => serialize($value),
            'lastModified' => time(),
            'expire' => $expire ? time() + $expire : 2147483647
        );
        $identifier = array(
            'id' => $key
        );

        if ($this->exists($key)) {
            // In mysql, the rowCount method return 0 when data is not modified,
            // so check errorCode to make sure it executed success
            $result = $this->db->update($this->table, $data, $identifier) || !$this->db->errorCode();
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
        return (bool)$this->db->delete($this->table, array('id' => $key));
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return (bool)$this->db->select($this->table, $key);
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
    public function inc($key, $offset = 1)
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
