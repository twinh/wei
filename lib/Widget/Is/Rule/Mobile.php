<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

/**
 * Mobile
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Mobile extends AbstractRule
{
    public function __invoke($data)
    {
        return (bool) preg_match('/^1[358][\d]{9}$/', $data);
    }
}
