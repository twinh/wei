<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator\Rule;

/**
 * IsAlpha
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Alpha extends AbstractRule
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^([a-z]+)$/i', $value);
    }
}
