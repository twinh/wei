<?php

namespace Wei\Db;

/**
 * @internal may be rename
 */
class Mysql extends BaseDriver
{
    protected $wrapper = '`';

    protected $sqlParts = [];

    /**
     * @var string[]
     * @internal
     */
    protected $typeMap = [
        'smallint' => 'smallInt',
        'mediumint' => 'mediumInt',
        'int' => 'int',
        'text' => 'text',
        'mediumtext' => 'mediumText',
        'longtext' => 'longText',
    ];

    public function getSql($type, $sqlParts, $identifierConverter = null)
    {
        $this->aliases = [];
        $this->sqlParts = $sqlParts;
        $this->identifierConverter = $identifierConverter;

        switch ($type) {
            case self::DELETE:
                return $this->getSqlForDelete();

            case self::UPDATE:
                return $this->getSqlForUpdate();

            case self::SELECT:
            default:
                return $this->getSqlForSelect();
        }
    }

    /**
     * Returns the interpolated query.
     *
     * @param int $type
     * @param array $sqlParts
     * @param callable $identifierConverter
     * @param array $values
     * @return string
     * @link https://stackoverflow.com/a/8403150
     */
    public function getRawSql($type, $sqlParts, $identifierConverter, array $values)
    {
        $query = $this->getSql($type, $sqlParts, $identifierConverter);
        $keys = [];

        // build a regular expression for each parameter
        foreach ($values as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = "'" . implode("','", $value) . "'";
            } elseif (null === $value) {
                $values[$key] = 'NULL';
            }
        }

        return preg_replace($keys, $values, $query, 1);
    }

    public function getColumns(string $table, ?callable $phpKeyConverter = null): array
    {
        $columns = [];

        $table = $this->db->getTable($table);
        $dbColumns = $this->db->fetchAll(<<<SQL
SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE,
COLUMN_TYPE, EXTRA, COLUMN_COMMENT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'
SQL
        );
        foreach ($dbColumns as $dbColumn) {
            $column = $this->parseColumn($dbColumn);
            $name = $phpKeyConverter ? $phpKeyConverter($dbColumn['COLUMN_NAME']) : $dbColumn['COLUMN_NAME'];
            $columns[$name] = $column;
        }

        return $columns;
    }

    /**
     * @param array $column
     * @return array
     */
    protected function getCustomDefault(array $column): array
    {
        $type = $column['DATA_TYPE'];
        $default = $column['COLUMN_DEFAULT'];

        if (null === $column['COLUMN_DEFAULT'] && 'YES' === $column['IS_NULLABLE']) {
            return [null, false];
        }

        // Bool
        if ('tinyint(1)' === $column['COLUMN_TYPE']) {
            return [(bool) $default, '0' !== $default];
        }

        if (in_array($type, ['tinyint', 'int', 'smallint', 'mediumint', 'bigint'], true)) {
            // When the column is the primary key, it cant have default value, $default is null
            return [(int) $default, '0' !== $default && null !== $default];
        }

        if (in_array($type, ['decimal'], true)) {
            // Remove ending 0
            return [(string) (float) $default, 0.0 !== (float) $default];
        }

        // MySQL 5.7 *text and json columns cant have default value,
        // so always use empty string and array as custom default value

        // TODO Mysql 8.0 support expression as default value

        if (in_array($column['COLUMN_TYPE'], ['tinytext', 'text', 'mediumtext', 'longtext'], true)) {
            return ['', true];
        }

        if (in_array($type, ['json'], true)) {
            return [[], true];
        }

        return [$default, '' !== $default];
    }

    /**
     * Converts this instance into an SELECT string in SQL
     *
     * @return string
     */
    protected function getSqlForSelect()
    {
        $parts = $this->sqlParts;

        if (!$parts['select']) {
            $parts['select'] = ['*'];
        }

        $query = 'SELECT ';

        if (isset($parts['distinct']) && $parts['distinct']) {
            $query .= 'DISTINCT ';
        }

        if ($parts['aggregate']) {
            $query .= $parts['aggregate']['function'] . '(' . $this->wrap($parts['aggregate']['columns']) . ')';
        } else {
            $selects = [];
            foreach ($parts['select'] as $as => $select) {
                if ($this->isRaw($select)) {
                    $selects[] = $this->getRawValue($select);
                } elseif (is_string($as)) {
                    $selects[] = $this->wrap($as) . ' AS ' . $this->wrap($select);
                } else {
                    $selects[] = $this->wrap($select);
                }
            }
            $query .= implode(', ', $selects);
        }

        $query .= ' FROM ' . $this->getFrom();

        // JOIN
        foreach ($parts['join'] as $join) {
            $query .= ' ' . strtoupper($join['type'])
                . ' JOIN ' . $this->parseTable($join['table'])
                . ' ON ' . $this->wrap($join['first']) . ' ' . $join['operator'] . ' ' . $this->wrap($join['second']);
        }

        if ($parts['where']) {
            $query .= ' WHERE ' . $this->buildWhere($parts);
        }

        if ($parts['groupBy']) {
            $query .= ' GROUP BY ';
            $groupBys = [];
            foreach ($parts['groupBy'] as $groupBy) {
                $groupBys[] = $this->wrap($groupBy);
            }
            $query .= implode(', ', $groupBys);
        }

        if ($parts['having']) {
            $query .= ' HAVING ' . $this->buildWhere($parts, 'having');
        }

        if (!$parts['aggregate']) {
            if ($parts['orderBy']) {
                $query .= ' ORDER BY ' . $this->buildOrderBy($parts);
            }

            $query .= (null !== $parts['limit'] ? ' LIMIT ' . $parts['limit'] : '')
                . (null !== $parts['offset'] ? ' OFFSET ' . $parts['offset'] : '');
        }

        $query .= $this->generateLockSql();

        return $query;
    }

    protected function buildWhere(array $parts, string $type = 'where')
    {
        $wheres = $parts[$type];
        $defaultTable = $parts['join'] ? $this->parseTableAndAlias($parts['from'])[0] : null;

        $query = '';
        foreach ($wheres as $i => $where) {
            if (0 !== $i) {
                $query .= ' ' . $where['condition'] . ' ';
            }

            if ($this->isRaw($where['column'])) {
                $query .= $this->getRawValue($where['column']);
                continue;
            }

            // NOTE: instanceof QueryBuilderTrait
            if (method_exists($where['column'], 'getQueryParts')) {
                $sqlParts = $where['column']->getQueryParts();
                $query .= '(' . $this->buildWhere($sqlParts) . ')';
                continue;
            }

            $column = $this->wrap($where['column'], $defaultTable);
            switch ($where['type'] ?? '') {
                case 'DATE':
                case 'MONTH':
                case 'DAY':
                case 'YEAR':
                case 'TIME':
                    $column = $where['type'] . '(' . $column . ')';
                    break;

                case 'COLUMN':
                    $query .= $column . ' ' . $where['operator'] . ' ' . $this->wrap($where['value']);
                    // TODO refactor
                    continue 2;
                default:
                    break;
            }

            switch ($where['operator']) {
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $query .= $this->processCondition($column . ' ' . $where['operator'] . ' ? AND ?');
                    break;

                case 'IN':
                case 'NOT IN':
                    $placeholder = implode(', ', array_pad([], count($where['value']), '?'));
                    $query .= $this->processCondition($column . ' ' . $where['operator'] . ' (' . $placeholder . ')');
                    break;

                case 'NULL':
                case 'NOT NULL':
                    $query .= $this->processCondition($column . ' IS ' . $where['operator']);
                    break;

                default:
                    $query .= $this->processCondition($column . ' ' . ($where['operator'] ?: '=') . ' ?');
            }
        }

        return $query;
    }

    protected function buildOrderBy(array $parts): string
    {
        $orderBys = [];
        foreach ($parts['orderBy'] as $orderBy) {
            if ($this->isRaw($orderBy['column'])) {
                $orderBys[] = $this->getRawValue($orderBy['column']);
            } else {
                $orderBys[] = $this->wrap($orderBy['column']) . ' ' . $orderBy['order'];
            }
        }
        return implode(', ', $orderBys);
    }

    /**
     * Converts this instance into an SELECT COUNT string in SQL
     */
    protected function getSqlForCount()
    {
        return 'SELECT COUNT(*) FROM (' . $this->getSqlForSelect() . ') wei_count';
    }

    /**
     * Converts this instance into an UPDATE string in SQL.
     *
     * @return string
     */
    protected function getSqlForUpdate()
    {
        $query = 'UPDATE ' . $this->getFrom() . ' SET ';

        $sets = [];
        foreach ($this->sqlParts['set'] as $set) {
            $sets[] = $this->wrap($set) . ' = ?';
        }
        $query .= implode(', ', $sets);

        if ($this->sqlParts['where']) {
            $query .= ' WHERE ' . $this->buildWhere($this->sqlParts);
        }

        if ($this->sqlParts['orderBy']) {
            $query .= ' ORDER BY ' . $this->buildOrderBy($this->sqlParts);
        }

        // Note that MySQL don't support OFFSET in UPDATE
        if ($this->sqlParts['limit']) {
            $query .= ' LIMIT ' . $this->sqlParts['limit'];
        }

        return $query;
    }

    /**
     * Converts this instance into a DELETE string in SQL.
     *
     * @return string
     */
    protected function getSqlForDelete()
    {
        $query = 'DELETE FROM ' . $this->getFrom();

        if ($this->sqlParts['where']) {
            $query .= ' WHERE ' . $this->buildWhere($this->sqlParts);
        }

        if ($this->sqlParts['orderBy']) {
            $query .= ' ORDER BY ' . $this->buildOrderBy($this->sqlParts);
        }

        // Note that MySQL don't support OFFSET in UPDATE
        if ($this->sqlParts['limit']) {
            $query .= ' LIMIT ' . $this->sqlParts['limit'];
        }

        return $query;
    }

    /**
     * Returns the from SQL part
     *
     * @return string
     */
    protected function getFrom()
    {
        return $this->parseTable($this->sqlParts['from']);
    }

    protected function parseTable($table)
    {
        [$table, $alias] = $this->parseTableAndAlias($table);
        return $this->wrapTable($table) . ($alias ? (' ' . $this->wrap($alias)) : '');
    }

    /**
     * Generate condition string for WHERE or Having statement
     *
     * @param mixed $conditions
     * @return string
     */
    protected function processCondition($conditions)
    {
        if (is_array($conditions)) {
            $where = [];
            $params = [];
            foreach ($conditions as $field => $condition) {
                if (is_array($condition)) {
                    $where[] = $field . ' IN (' . implode(', ', array_pad([], count($condition), '?')) . ')';
                    $params = array_merge($params, $condition);
                } else {
                    $where[] = $field . ' = ?';
                    $params[] = $condition;
                }
            }
            $conditions = implode(' AND ', $where);
        }

        return $conditions;
    }

    /**
     * @return string
     */
    protected function generateLockSql()
    {
        if ('' === $this->sqlParts['lock']) {
            return '';
        }

        if (is_string($this->sqlParts['lock'])) {
            return ' ' . $this->sqlParts['lock'];
        }

        if ($this->sqlParts['lock']) {
            return ' FOR UPDATE';
        } else {
            return ' LOCK IN SHARE MODE';
        }
    }

    /**
     * Parse column metadata from database column information
     *
     * @param array $dbColumn
     * @return array
     */
    protected function parseColumn(array $dbColumn): array
    {
        $column = $this->parseColumnType($dbColumn);

        // Auto increment column cant have default value, so it's allow to be null
        if ('YES' === $dbColumn['IS_NULLABLE'] || false !== strpos($dbColumn['EXTRA'], 'auto_increment')) {
            $column['nullable'] = true;
        }

        [$default, $isCustom] = $this->getCustomDefault($dbColumn);
        if ($isCustom) {
            $column['default'] = $default;
        }

        if ($dbColumn['COLUMN_COMMENT']) {
            $column['title'] = explode('。', $dbColumn['COLUMN_COMMENT'], 2)[0];
        }

        return $column;
    }

    /**
     * Parse column metadata from database column type
     *
     * @param array $column
     * @return array
     * @internal
     */
    protected function parseColumnType(array $column): array
    {
        switch ($column['DATA_TYPE']) {
            case 'tinyint':
                return 'tinyint(1)' === $column['COLUMN_TYPE'] ? [
                    'type' => 'bool',
                    'cast' => 'bool',
                ] : [
                    'type' => 'tinyInt',
                    'cast' => 'int',
                    'unsigned' => $this->isTypeUnsigned($column['COLUMN_TYPE']),
                ];

            case 'smallint':
            case 'mediumint':
            case 'int':
                return [
                    'type' => $this->typeMap[$column['DATA_TYPE']],
                    'cast' => 'int',
                    'unsigned' => $this->isTypeUnsigned($column['COLUMN_TYPE']),
                ];

            case 'bigint':
                return [
                    'type' => 'bigInt',
                    'cast' => 'intString',
                    'unsigned' => $this->isTypeUnsigned($column['COLUMN_TYPE']),
                ];

            case 'timestamp':
            case 'datetime':
                return [
                    'type' => $column['COLUMN_TYPE'],
                    'cast' => 'datetime',
                ];

            case 'date':
                return [
                    'type' => 'date',
                    'cast' => 'date',
                ];

            case 'decimal':
                return [
                    'type' => 'decimal',
                    'cast' => 'decimal',
                    'unsigned' => $this->isTypeUnsigned($column['COLUMN_TYPE']),
                    'length' => (int) $column['NUMERIC_PRECISION'],
                    'scale' => (int) $column['NUMERIC_SCALE'],
                ];

            case 'json':
                return [
                    'type' => 'json',
                    'cast' => 'json',
                    'length' => 1024 ** 3, // 1GB
                ];

            case 'char':
            case 'varchar':
                return [
                    'type' => 'string',
                    'cast' => 'string',
                    'length' => (int) $column['CHARACTER_MAXIMUM_LENGTH'],
                ];

            case 'text':
            case 'mediumtext':
            case 'longtext':
                return [
                    'type' => $this->typeMap[$column['DATA_TYPE']],
                    'cast' => 'string',
                ];

            default:
                return [
                    'type' => 'string',
                    'cast' => 'string',
                ];
        }
    }

    /**
     * @param string $columnType
     * @return bool
     * @internal
     */
    protected function isTypeUnsigned(string $columnType): bool
    {
        return 'unsigned' === substr($columnType, -8);
    }

    /**
     * @param string $columnType
     * @return string
     * @deprecated
     */
    protected function getCastType($columnType)
    {
        $parts = explode('(', $columnType);
        $type = $parts[0];
        $length = (int) ($parts[1] ?? 0);

        switch ($type) {
            case 'int':
            case 'smallint':
            case 'mediumint':
                return 'int';

            case 'tinyint':
                return 1 === $length ? 'bool' : 'int';

            case 'bigint':
                return 'intString';

            case 'timestamp':
            case 'datetime':
                return 'datetime';

            case 'date':
                return 'date';

            case 'decimal':
                return 'decimal';

            case 'json':
                return 'json';

            case 'varchar':
            case 'char':
            case 'mediumtext':
            case 'text':
            default:
                return 'string';
        }
    }

    private function parseTableAndAlias(?string $table): array
    {
        $pos = strpos($table, ' ');
        if (false !== $pos) {
            [$table, $alias] = explode(' ', $table);
            $this->addAlias($alias);
        } else {
            $alias = null;
        }
        return [$table, $alias];
    }
}
