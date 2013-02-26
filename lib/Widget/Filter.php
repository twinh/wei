<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Exception\RuntimeException;

/**
 * Filter
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Filter extends AbstractWidget
{
    protected $filters = array();

    public function __invoke($name, $callback)
    {
        if (is_callable($callback)) {
            return $this->add($name, $callback);
        } else {
            return $this->execute($name, $callback);
        }
    }

    /**
     * @param callable $callback
     */
    public function add($name, $callback)
    {
        $this->filters[$name][] = $callback;

        return $this;
    }

    public function execute($name, $data)
    {
        if (isset($this->filters[$name])) {
            foreach ($this->filters[$name] as $callback) {
                $data = call_user_func($callback, $data);
            }
        }

        return $data;
    }

    public function has($name, $callback)
    {
        if (!isset($this->filters[$name])) {
            throw new RuntimeException(sprintf('Undefined filter name "%s"', $name));
        }

        return (bool) array_search($callback, $this->filters[$name], true);
    }

    public function remove($name, $callback)
    {
        if (!isset($this->filters[$name])) {
            throw new RuntimeException(sprintf('Undefined filter name "%s"', $name));
        }

        $key = array_search($callback, $this->filters[$name], true);

        if (false === $key) {
            return false;
        }

        unset($this->filters[$name][$key]);

        return true;
    }
}
