<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to prevent CSRF attack
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Cache $cache A cache service
 */
class Csrf extends Base
{
    public function __invoke()
    {
        return $this;
    }
}