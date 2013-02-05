<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The basic class for request widget, like get, post etc
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Parameter extends ArrayWidget
{
    /**
     * Returns a integer value in the specified range
     *
     * @param string $name The parameter name
     * @param integer|null $min The min value for the parameter
     * @param integer|null $max The max value for the parameter
     * @return int
     */
    public function getInt($name, $min = null, $max = null)
    {
        $value = (int) $this($name, 0);

        if (!is_null($min) && $value < $min) {
            return $min;
        } elseif (!is_null($max) && $value > $max) {
            return $max;
        }

        return $value;
    }

    /**
     * Returns a parameter value in the specified array, if not in, returns the
     * first element instead
     *
     * @param string $name
     * @param array $array
     */
    public function inArray($name, $array = array())
    {
        $value = $this($name);

        return in_array($value, $array) ? $value : $array[key($array)];
    }
}
