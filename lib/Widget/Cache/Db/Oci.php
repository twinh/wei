<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * Oci
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Oci extends AbstractDriver
{
    /**
     * The sql queries
     *
     * @var array
     */
    protected $sqls = array(
        'prepare' => null,
        'checkTable' => 'SELECT 1 FROM "%s"',
        'create' => 'CREATE TABLE "%s" ("id" NVARCHAR2(255) PRIMARY KEY ,"lastModified" NUMBER,"expire" NUMBER,"value" NVARCHAR2(2000))',
        'get' => 'SELECT "value" FROM "%s" WHERE "id" = :id AND "expire" > :expire AND rownum = 1',
        'set' => 'INSERT INTO "%s" ("id", "value", "lastModified", "expire") VALUES (:id, :value, :lastModified, :expire)',
        'remove' => 'DELETE FROM "%s" WHERE "id" = :id',
        'replace' => 'UPDATE "%s" SET "value" = :value, "lastModified" = :lastModified, "expire" = :expire WHERE "id" = :id',
        'clear' => 'DELETE FROM "%s"',
        'drop' => 'DROP TABLE "%s"',
    );
}
