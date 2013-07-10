<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that handles key-value map data
 *
 * @author twinhuang
 */
class Map extends AbstractWidget
{
    /**
     * The map PHP file
     *
     * @var string
     */
    protected $file;

    /**
     * The data return by map file
     *
     * @var array
     */
    protected $data;

    /**
     * The inverse map data
     *
     * @var array
     */
    protected $inverseData = array();

    /**
     * Returns map data by specified name
     *
     * @param string $name The name of map
     * @param null|string $key The item name in map
     * @return array|string The map array or the item value of map
     */
    public function __invoke($name, $key = null)
    {
        if (1 == func_num_args()) {
            return $this->get($name);
        } else {
            $data = $this->get($name);
            return $data[$key];
        }
    }

    /**
     * Set map file
     *
     * @param string $file
     * @throws \InvalidArgumentException when map file not found
     * @return Map
     */
    public function setFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('Map file "%s" not found', $file));
        }

        $this->file = $file;
        $this->data = require $file;

        return $this;
    }

    /**
     * Returns map data by given name
     *
     * @param string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        throw new \InvalidArgumentException(sprintf('Map name "%s" not defined', $name));
    }

    /**
     * Get inverse map data
     *
     * @param $name
     * @param string|null $key
     * @return array|string
     */
    public function getInverse($name, $key = null)
    {
        if (!isset($this->inverseData[$name])) {
            $this->inverseData[$name] = array_flip($this->get($name));
        }

        if ($key) {
            return isset($this->inverseData[$name][$key]) ? $this->inverseData[$name][$key] : false;
        } else {
            return $this->inverseData[$name];
        }
    }

    /**
     * Convert map data to JSON
     *
     * @param string $name The name of map
     * @return string
     */
    public function toJson($name)
    {
        return json_encode($this->get($name));
    }

    /**
     * Convert map data to HTML select options
     *
     * @param string $name The name of map
     * @return string
     */
    public function toOptions($name)
    {
        $html = '';
        foreach ($this->get($name) as $value => $text) {
            if (is_array($text)) {
                $html .= '<optgroup label="' . $value . '">';
                foreach ($text as $v => $t) {
                    $html .= '<option value="' . $v . '">' . $t . '</option>';
                }
                $html .= '</optgroup>';
            } else {
                $html .= '<option value="' . $value . '">' . $text . '</option>';
            }
        }
        return $html;
    }

    /**
     * Convert map data to single level PHP array
     *
     * @param string $name The name of map
     * @return array
     */
    public function toSimpleArray($name)
    {
        return $this->loopSimpleArray($this->get($name));
    }

    /**
     * @param array $data
     * @param array $result
     * @return array
     */
    protected function loopSimpleArray(array $data, &$result = array())
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->loopSimpleArray($value, $result);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}