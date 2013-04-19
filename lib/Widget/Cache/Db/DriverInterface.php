<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Cache\Db;

/**
 * The base SQL query interface for database cache
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
interface DriverInterface
{
    /**
     * Returns the sql query by specified type
     * 
     * @param string $type The type of sql query
     */
    public function getSql($type);
}
