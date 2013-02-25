<?php
/**
 * Widget Framework
 *
 * Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * ReplaceFirst
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        replaceOne, replaceLast ?
 */
class ReplaceFirst extends AbstractWidget
{
    /**
     * Replace the first string
     *
     * @param  string $string  the string to be searched and replaced
     * @param  string $search  the string to search
     * @param  string $replace the string to replace
     * @return mixed
     */
    public function __invoke($string, $search, $replace)
    {
        $pos = strpos($string, $search);
        if ($pos === false) {
            return $string;
        }

        return substr_replace($string, $replace, $pos, strlen($search));
    }
}
