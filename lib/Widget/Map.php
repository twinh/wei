<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * Description of Map
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

    protected $arrayObjects;

    protected $inverses = array();

    /**
     * Get map data by specified name
     *
     * @param string $name
     * @param string $key
     * @return mixed
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
     * @throws \InvalidArgumentException when file not found
     * @return Map
     */
    public function setFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('File %s not found', $file));
        }

        $this->file = $file;
        $this->data = require $file;

        return $this;
    }

    /**
     * Get map data by given name
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
        if (isset($this->arrayObjects[$name])) {
            return $this->arrayObjects[$name];
        }

        if (isset($this->data[$name])) {
            return $this->arrayObjects[$name] = new \ArrayObject($this->data[$name]);
        }

        throw new \InvalidArgumentException(sprintf('Map name "%s" not defined', $name));
    }

    public function getInverse($name, $key = null)
    {
        if (!isset($this->inverses[$name])) {
            $this->inverses[$name] = array_flip($this->get($name));
        }

        if ($key) {
            return isset($this->inverses[$name][$key]) ? $this->inverses[$name][$key] : null;
        } else {
            return $this->inverses[$name];
        }
    }

    /**
     * Convert map data to json
     *
     * @param string $name The name of map
     * @return string
     */
    public function toJson($name)
    {
        return json_encode($this->get($name));
    }

    /**
     * Convert map data to html select options
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
     * Convert map data to single level php array
     *
     * @param string $name
     */
    public function toSimpleArray($name)
    {
        return $this->loopSimpleArray($this->get($name));
    }

    protected function loopSimpleArray($data, &$result = array())
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