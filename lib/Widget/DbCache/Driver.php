<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\DbCache;

/**
 * Driver
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
abstract class Driver
{
    /**
     * Sql queries
     *
     * @var array
     */
    protected $sqls = array();

    /**
     *  Get one sql query
     *
     * @param  string $type sql type, the key of the $this->sqls array
     * @return string
     */
    public function getSql($type)
    {
        return isset($this->sqls[$type]) ? $this->sqls[$type] : false;
    }

    /**
     *  Get all sql queries
     *
     * @return array
     */
    public function getSqls()
    {
        return $this->sqls;
    }
}
