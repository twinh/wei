<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * Sqlsrv
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Sqlsrv extends Driver
{
    /**
     * The sql queries
     *
     * @var array
     */
    protected $sqls = array(
        'prepare' => null,
        'checkTable' => 'SELECT 1 from %s',
        'create' => 'CREATE TABLE %s (id nvarchar(255) PRIMARY KEY (id), value nvarchar(max), lastModified int, expire int)',
        'get' => 'SELECT TOP 1 value FROM %s WHERE id = :id AND expire > :expire',
        'set' => 'INSERT INTO %s (id, value, lastModified, expire) VALUES (:id, :value, :lastModified, :expire)',
        'remove' => 'DELETE FROM %s WHERE id = :id',
        'replace' => 'UPDATE %s SET value = :value, lastModified = :lastModified, expire = :expire WHERE id = :id',
        'increment' => 'UPDATE %s SET value = CAST(value AS int) + :offset, lastModified = :lastModified WHERE id = :id',
        'clear' => 'DELETE FROM %s',
        'drop' => 'DROP TABLE %s',
    );
}
