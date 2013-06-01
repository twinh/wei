<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Db;

/**
 * A base database collection class
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Collection extends \ArrayObject
{
    public function toArray()
    {
        foreach ($this as $record) {
            $data[] = $record->toArray();
        }
        return $data;
    }
}