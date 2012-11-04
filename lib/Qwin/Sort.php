<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Sort
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @todo        rename to orderBy ?
 * @tddo        add selectKeys or filterKeys ?
 */
class Sort extends WidgetProvider
{
    /**
     * Sort two-dimensional array like SQL ORDER BY clause
     *
     * @param  array  $array the two-dimensional array
     * @param  string $key   the array to be sort
     * @param  int    $type  sort in ascending or descending order
     * @return array
     */
    public function __invoke(array $array, $key = 'order', $type = SORT_ASC)
    {
        if (!$array) {
            return $array;
        }
        $array2 = array();
        foreach ($array as $k => $v) {
            $array2[$k] = $v[$key];
        }
        array_multisort($array2, $type, $array);

        return $array;
    }
}
