<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * The mysql db cache driver
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Mysql extends AbstractDriver
{
    /**
     * The sql queries
     *
     * @var array
     */
    protected $sqls = array(
        'prepare' => 'SET NAMES UTF8',
        'checkTable' => 'SELECT 1 from %s',
        'create' => 'CREATE TABLE %s (id varchar(255) PRIMARY KEY, value longtext, lastModified int, expire int) ENGINE=InnoDB DEFAULT CHARSET=utf8',
        'get' => 'SELECT value FROM %s WHERE id = :id AND expire > :expire LIMIT 1',
        'set' => 'INSERT INTO %s (id, value, lastModified, expire) VALUES (:id, :value, :lastModified, :expire)',
        'remove' => 'DELETE FROM %s WHERE id = :id',
        'replace' => 'UPDATE %s SET value = :value, lastModified = :lastModified, expire = :expire WHERE id = :id',
        'increment' => 'UPDATE %s SET value = value + :offset, lastModified = :lastModified WHERE id = :id',
        'clear' => 'DELETE FROM %s',
        'drop' => 'DROP TABLE %s',
    );
}
