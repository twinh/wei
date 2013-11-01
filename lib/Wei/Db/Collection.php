<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Db;

use Closure;

/**
 * A collection class contains the database records
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Collection extends \ArrayObject
{
    /**
     * Returns the collection and relative records data as array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        /** @var $record Record */
        foreach ($this as $key => $record) {
            $data[$key] = $record->toArray();
        }
        return $data;
    }

    /**
     *  Filters elements of the collection using a callback function
     *
     * @param Closure $fn
     * @return Collection
     */
    public function filter(Closure $fn)
    {
        return new static(array_filter($this->getArrayCopy(), $fn));
    }

    /**
     * Iteratively reduce the collection to a single value using a callback function
     *
     * @param Closure $fn
     * @return mixed
     */
    public function reduce(Closure $fn)
    {
        return array_reduce($this->getArrayCopy(), $fn);
    }
}
