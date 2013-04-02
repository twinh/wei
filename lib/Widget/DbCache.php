<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2011 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */
namespace Widget;

use PDO;
use PDOException;
use Widget\Exception;
use Widget\Cache\AbstractCache;

/**
 * The database cache widget
 *
 * @author  Twin Huang <twinh@yahoo.cn>
 * @todo    add serialize field
 */
class DbCache extends AbstractCache
{
    /**
     * The cache table name
     *
     * @var string
     */
    protected $table = 'cache';

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
    protected $dsn = 'sqlite:cache.sqlite';

    /**
     * The PDO object
     *
     * @var \PDO
     */
    protected $dbh;

    /**
     * The PDOStatement object
     *
     * @var \PDOStatement
     */
    protected $stmt;

    /**
     * The sql driver object
     *
     * @var \Widget\Cache\Db\DriverInterface
     */
    protected $driver;
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->connect();
    }

    /**
     * Connect the database
     *
     * @throws \Widget\Exception\UnsupportedException When driver not support
     */
    public function connect()
    {
        if (!$this->dbh) {
            $this->dbh = new PDO($this->dsn, $this->user, $this->password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
        }

        // Get driver and load sql queries
        $driver = $this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME);
        $class = 'Widget\Cache\Db\\' . ucfirst($driver);
        if (class_exists($class)) {
            $this->driver = new $class;
        } else {
            throw new Exception\UnsupportedException(sprintf('Unsupport driver "%s"', $driver));
        }

        // Execute prepare sql query
        if ($prepare = $this->driver->getSql('prepare')) {
            $this->query($prepare);
        }

        // Check if the table exists, if not, create the table
        try {
            if (!$this->query($this->driver->getSql('checkTable'))) {
                $this->query($this->driver->getSql('create'));
            }
        } catch (PDOException $e) {
            $this->query($this->driver->getSql('create'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $result = $this->query($this->driver->getSql('get'), array(
            ':id' => $key,
            ':expire' => time(),
        ));

        if ($result) {
            $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? unserialize($row['value']) : false;
        }

        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        $this->remove($key);

        $result = $this->query($this->driver->getSql('set'), array(
            ':id' => $key,
            ':value' => serialize($value),
            ':lastModified' => time(),
            ':expire' => $expire ? time() + $expire : 2147483647
        ));

        return $result;
    }
    
    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $result = $this->query($this->driver->getSql('remove'), array(
            ':id' => $key,
        ));

        return $result;
    }
    
    /**
     * {@inheritdoc}
     * @todo better way
     */
    public function exists($key)
    {
        $result = $this->query($this->driver->getSql('get'), array(
            ':id' => $key,
            ':expire' => time(),
        ));

        if ($result) {
            $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? true : false;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        try {
            $result = $this->query($this->driver->getSql('set'), array(
                ':id' => $key,
                ':value' => serialize($value),
                ':lastModified' => time(),
                ':expire' => $expire ? time() + $expire : 2147483647
            ));

            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        $this->query($this->driver->getSql('replace'), array(
            ':id' => $key,
            ':value' => serialize($value),
            ':lastModified' => time(),
            ':expire' => $expire ? time() + $expire : 2147483647
        ));

        return (bool) $this->stmt->rowCount();
    }

    /**
     * Note: This method is not an atomic operation
     * 
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        $value = $this->get($key);
        
        return $this->set($key, $value + $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->query($this->driver->getSql('clear'));
    }

    /**
     * Execute a sql query
     *
     * @param  string $sql  sql query from driver
     * @param  array  $args args for prepare
     * @return bool
     */
    public function query($sql, $args = array())
    {
        $sql = sprintf($sql, $this->table);

        $this->stmt = $this->dbh->prepare($sql);

        return $this->stmt->execute($args);
    }

    /**
     * Get the PDO object
     *
     * @return \PDO
     */
    public function getDbh()
    {
        return $this->dbh;
    }
    
    /**
     * Get current database cache driver
     *
     * @return \Widget\Cache\Db\DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
