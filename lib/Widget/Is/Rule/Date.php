<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

use Widget\WidgetProvider;
use DateTime;


/**
 * The date validate rule
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Date extends WidgetProvider
{
    protected $format = 'Y-m-d';
    
    public function __invoke($data)
    {
        $date = DateTime::createFromFormat($this->format, $data);
        
        if (false === $date) {
            return false;
        }

        return $data === $date->format($this->format);
    }
}