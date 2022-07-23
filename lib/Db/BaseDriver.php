<?php

namespace Wei\Db;

use Wei\Base;

/**
 * @mixin \DbMixin
 * @internal may be rename
 */
abstract class BaseDriver extends Base
{
    // The query types.

    public const SELECT = 0;
    public const DELETE = 1;
    public const UPDATE = 2;

    /**
     * @var string
     */
    protected $wrapper = '';

    /**
     * @var callable|null
     */
    protected $identifierConverter;

    /**
     * The table name alias used in the query
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * @param int $type
     * @param array $sqlParts
     * @param callable|null $identifierConverter
     * @return string
     */
    abstract public function getSql($type, $sqlParts, $identifierConverter = null);

    /**
     * @param int $type
     * @param array $sqlParts
     * @param callable|null $identifierConverter
     * @param array $values
     * @return string
     */
    abstract public function getRawSql($type, $sqlParts, $identifierConverter, array $values);

    /**
     * Returns the columns config
     *
     * @param string $table
     * @param callable|null $phpKeyConverter
     * @return array
     */
    abstract public function getColumns(string $table, callable $phpKeyConverter = null): array;

    protected function wrap($column, string $defaultTable = null)
    {
        if (false === strpos($column, '.')) {
            if (!$defaultTable) {
                return $this->wrapValue($column);
            } else {
                $column = $defaultTable . '.' . $column;
            }
        }

        $items = explode('.', $column);

        // 倒数第二项是数据表名称，例如：db.table.column
        $tableIndex = count($items) - 2;

        foreach ($items as $i => &$item) {
            if ($i === $tableIndex) {
                $item = $this->wrapTable($item);
            } else {
                $item = $this->wrapValue($item);
            }
        }

        return implode('.', $items);
    }

    protected function wrapTable($table)
    {
        return $this->wrap($this->isAlias($table) ? $table : $this->db->getTable($table));
    }

    protected function addAlias($name)
    {
        $this->aliases[$name] = true;
        return $this;
    }

    protected function isAlias($name)
    {
        return isset($this->aliases[$name]);
    }

    protected function wrapValue(string $value): string
    {
        if ('*' === $value) {
            return $value;
        }

        if ($this->identifierConverter) {
            $value = call_user_func($this->identifierConverter, $value);
        }

        return $this->wrapper . $value . $this->wrapper;
    }

    protected function getRawValue($expression)
    {
        return $this->db->getRawValue($expression);
    }

    protected function isRaw($expression)
    {
        return $this->db->isRaw($expression);
    }
}
