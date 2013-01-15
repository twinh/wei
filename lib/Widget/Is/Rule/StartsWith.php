<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;


/**
 * Check if the data ends with the specified string
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class StartsWith extends AbstractRule
{
    public function __invoke($data, $findMe, $case = false)
    {
        if (!$findMe) {
            return false;
        }
        
        $fn = $case ? 'strpos' : 'stripos';
        
        return 0 === $fn($data, $findMe);
    }
}