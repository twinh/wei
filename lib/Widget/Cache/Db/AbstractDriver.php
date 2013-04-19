<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * A simple implementation of \Widget\Cache\Db\DriverInterface
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * Sql queries
     *
     * @var array
     */
    protected $sqls = array();

    /**
     * {@inheritdoc}
     *
     * @param  string $type sql type, the key of the $this->sqls array
     * @return string|false
     */
    public function getSql($type)
    {
        return isset($this->sqls[$type]) ? $this->sqls[$type] : false;
    }
}
