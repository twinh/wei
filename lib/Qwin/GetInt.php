<?php
/**
 * Qwin Framework
 * 
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * GetInt
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class GetInt extends Widget
{
    public function __invoke($name, $min = null, $max = null)
    {
        $value = intval($this->get($name));

        if (!is_null($min) && $value < $min) {
            return $min;
        } elseif (!is_null($max) && $value > $max) {
            return $max;
        }

        return $value;
    }
}