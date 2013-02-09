<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

/**
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Date extends DateTime
{
    protected $format = 'Y-m-d';

    protected $message = '%name% is not a valid date, the format should be "%format%", eg: %example%';
}
