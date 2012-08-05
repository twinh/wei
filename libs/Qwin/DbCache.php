<?php
/**
 * DbCache
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2012-7-24
 */
class Qwin_DbCache extends Qwin_Widget implements Qwin_Storable
{
    public $options = array(
        'dsn' => 'sqlite:cache.sqlite',
        'user' => null,
        'password' => null,
        'dbh' => null,
        'table' => 'cache',
    );

    /**
     * PDO object
     *
     * @var PDO
     */
    protected $_dbh;

    /**
     * PDOStatement object
     *
     * @var PDOStatement
     */
    protected $_stmt;
    
    /**
     * Sql driver object
     * 
     * @var Qwin_DbCache_Driver
     */
    protected $_driver;

    /**
     * Sql queries
     * 
     * @var array
     */
    protected $_sqls = array();

    /**
     * Connect database  
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options += $this->options;

        // connect
        if ($options['dbh'] && $options['dbh'] instanceof PDO) {
            $this->_dbh = $options['dbh'];
        } else {
            try {
                $this->_dbh = new PDO($options['dsn'], $options['user'], $options['password']);
                $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
            } catch (PDOException $e) {
                $this->exception($e->getMessage());
            }
        }
        
        // get driver and load sql queries
        $driver = $this->_dbh->getAttribute(PDO::ATTR_DRIVER_NAME);
        $class = 'Qwin_DbCache_' . ucfirst($driver);
        if (class_exists($class)) {
            $this->_driver = new $class;
            $this->_sqls = $this->_driver->getSqls();
        } else {
            $this->exception('Unsupport driver "' . $driver . '"');
        }
        
        // execute prepare sql query
        if ($this->_sqls['prepare']) {
            $this->query($this->_sqls['prepare']);
        }
        
        // check if table exists, if not, create table
        try {
            if (!$this->query($this->_sqls['checkTable'])) {
                $this->query($this->_sqls['create']);
            }
        } catch (Qwin_Exception $e) {
            $this->query($this->_sqls['create']);
        }
    }

    /**
     * Get or set cache
     *
     * @param string $key the name of cache
     * @param mixed $value
     * @param int $expire expire time for set cache
     * @return mixed
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
     * Get cache
     *
     * @param string $key the name of cache
     * @return mixed|false
     */
    public function get($key, $options = null)
    {
        $result = $this->query($this->_sqls['get'], array(
            ':id' => $key,
            ':expire' => time(),
        ));

        if ($result) {
            $row = $this->_stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['value'] : false;
        }

        return false;
    }

    /**
     * Set cache
     *
     * @param string $key the name of cache
     * @param value $value the value of cache
     * @param int $expire expire time, 0 means never expired
     * @return bool
     */
    public function set($key, $value, $expire = 0, array $options = array())
    {
        $this->remove($key);

        $result = $this->query($this->_sqls['set'], array(
            ':id' => $key,
            ':value' => $value,
            ':lastModified' => time(),
            ':expire' => $expire ? time() + $expire : 2147483647
        ));

        return $result;
    }

    /**
     * Remove cache by key
     *
     * @param string $key the name of cache
     * @return bool
     */
    public function remove($key)
    {
        $result = $this->query($this->_sqls['remove'], array(
            ':id' => $key,
        ));

        return $result;
    }

    /**
     * Add cache, if cache is exists, return false
     *
     * @param string $key the name of cache
     * @param mixed $value the value of cache
     * @param int $expire expire time
     * @return bool
     */
    public function add($key, $value, $expire = 0, array $options = array())
    {
        try {
            $result = $this->query($this->_sqls['set'], array(
                ':id' => $key,
                ':value' => $value,
                ':lastModified' => time(),
                ':expire' => $expire ? time() + $expire : 2147483647
            ));

            return $result;
        } catch (Qwin_Exception $e) {
            return false;
        }
    }

    /**
     * Replace cache, if cache not exists, return false
     *
     * @param string $key the name of cache
     * @param mixed $value the value of cache
     * @param int $expire expire time
     * @return bool
     */
    public function replace($key, $value, $expire = 0, array $options = array())
    {
        $this->query($this->_sqls['replace'], array(
            ':id' => $key,
            ':value' => $value,
            ':lastModified' => time(),
            ':expire' => $expire ? time() + $expire : 2147483647
        ));
        return (bool)$this->_stmt->rowCount();
    }

    /**
     * Increase a numerical cache
     *
     * @param string $key the name of cache
     * @param int $offset the value to decrease
     * @return int|false
     */
    public function increment($key, $offset = 1)
    {
        // todo add lock or transaction
        if (!is_numeric($value = $this->get($key))) {
            return false;
        }

        try {
            $this->query($this->_sqls['increment'], array(
                ':id' => $key,
                ':offset' => $offset,
                ':lastModified' => time(),
            ));

            if (0 === $this->_stmt->rowCount()) {
                return false;
            } else {
                return $value + $offset;
            }
        } catch (Qwin_Exception $e) {
            return false;
        }
    }

    /**
     * Decrease a numerical cache
     *
     * @param string $key the name of cache
     * @param int $offset the value to decrease
     * @return int|false
     */
    public function decrement($key, $offset = 1)
    {
        return $this->increment($key, -$offset);
    }

    /**
     * Clear all cache
     *
     * @return boolean
     */
    public function clear()
    {
        return $this->query($this->_sqls['clear']);
    }

    /**
     * Execute a sql query 
     * 
     * @param string $sql sql query from driver
     * @param array $args args for prepare
     * @return bool
     */
    public function query($sql, $args = array())
    {
        try {
            $sql = sprintf($sql, $this->options['table']);
            
            $this->_stmt = $this->_dbh->prepare($sql);
            
            return $this->_stmt->execute($args);
        } catch (PDOException $e) {
            $this->exception($e->getMessage(), $e->getCode());
        }
    }
    
    /**
     * Get PDO object
     * 
     * @return PDO 
     */
    public function getDbh()
    {
        return $this->_dbh;
    }
    
    /** 
     * Get last PDOStatement object
     * 
     * @return PDOStatement
     */
    public function getStmt()
    {
        return $this->_stmt;
    }
    
    /**
     * Get current database cache driver
     * 
     * @return Qwin_DbCache_Driver
     */
    public function getDriver()
    {
        return $this->_driver;
    }
}