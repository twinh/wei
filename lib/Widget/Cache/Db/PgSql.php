<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * PgSql
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class PgSql extends AbstractDriver
{
    /**
     * The sql queries
     *
     * @var array
     */
    protected $sqls = array(
        'prepare' => null,
        'checkTable' => 'SELECT 1 FROM %s',
        'create' => 'CREATE TABLE %s (id varchar(255) PRIMARY KEY, value text, "lastModified" int4, expire int4)',
        'get' => 'SELECT value FROM %s WHERE id = :id AND expire > :expire',
        'set' => 'INSERT INTO %s (id, value, "lastModified", expire) VALUES (:id, :value, :lastModified, :expire)',
        'remove' => 'DELETE FROM %s WHERE id = :id',
        'replace' => 'UPDATE %s SET value = :value, "lastModified" = :lastModified, expire = :expire WHERE id = :id',
        'clear' => 'DELETE FROM %s',
        'drop' => 'DROP TABLE %s',
    );
}
