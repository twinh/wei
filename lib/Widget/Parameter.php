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
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Parameter extends ArrayWidget
{
    /**
     * Returns a *stringify* or user defined($default) parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return string|null  The parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? (string)$this->data[$name] : $default;
    }
    
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
     * Returns a array parameter value
     * 
     * @param string $name The parameter name
     * @param array $default The default paraemter value returned if the 
     *                       parameter does not exist, should be array
     * @return array
     */
    public function getArray($name, array $default = array())
    {
        return (array) $this->getRaw($name, $default);
    }
    
    /**
     * Returns a raw parameter value
     *
     * @param  string $name    The parameter name
     * @param  mixed  $default The default parameter value if the parameter does not exist
     * @return string  The parameter value
     */
    public function getRaw($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     * Returns a parameter value in the specified array, if not in, returns the
     * first element instead
     *
     * @param string $name
     * @param array $array
     */
    public function getInArray($name, $array = array())
    {
        $value = $this($name);

        return in_array($value, $array) ? $value : $array[key($array)];
    }
}
