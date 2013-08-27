<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A counter widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Counter extends Base
{
    protected $deps = array(
        'cache' => ''
    );

    public function incr()
    {
        $this->cache;
    }

    public function decr()
    {

    }

    public function get()
    {

    }

    public function set()
    {

    }
}