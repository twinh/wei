<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * An util widget provides some useful method to manipulation array 
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Arr extends AbstractWidget
{
    /**
     * Sort two-dimensional array like SQL ORDER BY clause
     *
     * @param  array  $array the two-dimensional array
     * @param  string $key   the array to be sort
     * @param  int    $type  sort in ascending or descending order
     * @return array
     */
    public function sort(array $array, $key = 'order', $type = SORT_ASC)
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
    
    /**
     * Returns the value of specified key in $data
     * 
     * @param mixed $data The data, could be array, instance of \ArrayAccess, 
     *                    object or object with getter method 
     * @param string $key The key of data
     * @return mixed|null Returns null on not found
     */
    public function attr($data, $key)
    {
        if ((is_array($data) && array_key_exists($key, $data)) 
            || ($data instanceof \ArrayAccess && $data->offsetExists($key))
        ) {
            return $data[$key];
        } elseif (isset($data->$key)) {
            return $data->$key;
        } elseif (method_exists($data, 'get' . $key)) {
            return $data->{'get' . $key}();
        } else {
            return null;
        }
    }
    
    /**
     * 
     * @return \Widget\Arr
     */
    public function __invoke()
    {
        return $this;
    }
}