<?php

namespace Wei\Db;

use Closure;
use Wei\BaseCache;
use Wei\Db;

/**
 * The main functions of the query builder
 *
 * @author Twin Huang <twinhuang@qq.com>
 * @mixin \NearCacheMixin
 * @property \Wei\Cache $cache A cache service proxy 不引入 \CacheMixin 以免 phpstan 识别为 mixin 的 cache 方法
 * @internal Expected to be used only by QueryBuilder and ModelTrait
 */
trait QueryBuilderTrait
{
    /**
     * Set the record table name
     *
     * @param string $table
     * @return $this
     */
    public function setTable(string $table): self
    {
        return $this->from($table);
    }

    /**
     * Execute this query using the bound parameters and their types
     *
     * @return mixed
     */
    public function execute()
    {
        if (BaseDriver::SELECT == $this->queryType) {
            if ($this->hasCacheConfig()) {
                return $this->fetchFromCache();
            } else {
                return $this->executeFetchAll($this->getSql(), $this->getBindParams(), $this->queryParamTypes);
            }
        } else {
            return $this->getDb()->executeUpdate($this->getSql(), $this->getBindParams(), $this->queryParamTypes);
        }
    }

    /**
     * Executes the generated query and returns a column value of the first row
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return string|null
     */
    public function fetchColumn($column = null, $operator = null, $value = null): ?string
    {
        $data = $this->fetch(...func_get_args());
        return $data ? current($data) : null;
    }

    /**
     * Executes a sub query to receive the rows number
     *
     * @return int
     * @todo 改为自动识别
     */
    public function countBySubQuery(): int
    {
        return (int) $this->where(...func_get_args())->fetchColumn();
        //return (int) $this->db->fetchColumn($this->getSqlForCount(), $this->getBindParams());
    }

    /**
     * @param string $function
     * @param string|string[] $columns
     * @return string|null
     */
    public function aggregate($function, $columns = ['*']): ?string
    {
        $this->addQueryPart('aggregate', compact('function', 'columns'));
        return $this->fetchColumn(null);
    }

    /**
     * @param bool $distinct
     * @return $this
     */
    public function distinct(bool $distinct = true): self
    {
        return $this->addQueryPart('distinct', $distinct);
    }

    /**
     * @param scalar $expression
     * @return object
     */
    public function raw($expression): object
    {
        return $this->getDb()->raw($expression);
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * disjunction with any previously specified restrictions.
     *
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null): self
    {
        if (is_array($column)) {
            foreach ($column as $arg) {
                $this->orWhere(...$arg);
            }
            return $this;
        }

        if (2 === func_num_args()) {
            $value = $operator;
            $operator = '=';
        }

        return $this->addWhere($column, $operator, $value, 'OR');
    }

    /**
     * @param scalar $expression
     * @param mixed $params
     * @return $this
     */
    public function orWhereRaw($expression, $params = null): self
    {
        return $this->orWhere($this->raw($expression), null, $params);
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     */
    public function orWhereNotBetween(string $column, array $params): self
    {
        return $this->addWhere($column, 'NOT BETWEEN', $params, 'OR');
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     */
    public function orWhereIn(string $column, array $params): self
    {
        return $this->addWhere($column, 'IN', $params, 'OR');
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     */
    public function orWhereNotIn(string $column, array $params): self
    {
        return $this->addWhere($column, 'NOT IN', $params, 'OR');
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereNull(string $column): self
    {
        return $this->addWhere($column, 'NULL', null, 'OR');
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereNotNull(string $column): self
    {
        return $this->addWhere($column, 'NOT NULL', null, 'OR');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function orWhereDate(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'DATE');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function orWhereMonth(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'MONTH');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function orWhereDay(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'DAY');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function orWhereYear(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'YEAR');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function orWhereTime(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'TIME');
    }

    /**
     * @param string $column
     * @param mixed $opOrColumn2
     * @param mixed|null $column2
     * @return $this
     */
    public function orWhereColumn(string $column, $opOrColumn2, $column2 = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'OR', 'COLUMN');
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return $this
     */
    public function orWhereContains(string $column, $value): self
    {
        return $this->whereContains($column, $value, 'OR');
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return $this
     */
    public function orWhereNotContains(string $column, $value): self
    {
        return $this->whereNotContains($column, $value, 'OR');
    }

    /**
     * Adds a restriction over the groups of the query, forming a logical
     * disjunction with any existing having restrictions.
     *
     * @param mixed $column
     * @param mixed $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orHaving($column, $operator, $value = null): self
    {
        if (2 === func_num_args()) {
            $value = $operator;
            $operator = '=';
        }
        return $this->having($column, $operator, $value, 'OR');
    }

    /**
     * Returns a SQL query part by its name
     *
     * @param string $name The name of SQL part
     * @return mixed
     */
    public function getQueryPart(string $name)
    {
        return $this->queryParts[$name] ?? null;
    }

    /**
     * Get all SQL parts
     *
     * @return array
     */
    public function getQueryParts(): array
    {
        return $this->queryParts;
    }

    /**
     * Reset all SQL parts
     *
     * @param string|null $name
     * @return $this
     */
    public function resetQueryParts($name = null): self
    {
        if (null === $name) {
            $name = array_keys($this->queryParts);
        }
        foreach ($name as $queryPartName) {
            $this->resetQueryPart($queryPartName);
        }
        return $this;
    }

    /**
     * Sets a query parameter for the query being constructed
     *
     * @param int|string $key The parameter position or name
     * @param mixed $value The parameter value
     * @param string $type
     * @return $this
     */
    public function setQueryParam($key, $value, string $type = 'where'): self
    {
        $this->queryParams[$type][$key] = $value;
        return $this;
    }

    /**
     * Sets a collection of query parameters for the query being constructed
     *
     * @param array $params The query parameters to set
     * @param string $type
     * @return $this
     */
    public function setQueryParams(array $params, string $type = 'where'): self
    {
        $this->queryParams[$type] = $params;
        return $this;
    }

    /**
     * Gets a (previously set) query parameter of the query being constructed
     *
     * @param mixed $key The key (index or name) of the bound parameter
     * @param string $type
     * @return mixed The value of the bound parameter
     */
    public function getQueryParam($key, string $type = 'where')
    {
        return $this->queryParams[$type][$key] ?? null;
    }

    /**
     * Gets all defined query parameters for the query being constructed.
     *
     * @param string|null $type
     * @return array the currently defined query parameters
     */
    public function getQueryParams(?string $type = 'where'): ?array
    {
        if ($type) {
            return $this->queryParams[$type] ?? null;
        } else {
            return $this->queryParams;
        }
    }

    /**
     * @param array|string $param
     * @param string $type
     * @return $this
     */
    public function addQueryParam($param, string $type = 'where'): self
    {
        $this->queryParams[$type] = array_merge($this->queryParams[$type], (array) $param);
        return $this;
    }

    /**
     * @param array $params
     * @param string $type
     */
    public function addQueryParams(array $params, string $type = 'where'): self
    {
        $this->queryParams[$type] = array_merge($this->queryParams[$type], $params);
        return $this;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function resetQueryParam(?string $type = 'where'): self
    {
        if ($type) {
            $this->queryParams[$type] = [];
        } else {
            foreach ($this->queryParams as $type => $params) {
                $this->queryParams[$type] = [];
            }
        }
        return $this;
    }

    /**
     * Returns flatten array for parameter binding.
     *
     * @return array
     */
    public function getBindParams(): array
    {
        $result = [];
        foreach ($this->queryParams as $params) {
            foreach ($params as $key => $param) {
                if (is_int($key)) {
                    $result[] = $param;
                } else {
                    $result[$key] = $param;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $types
     * @return $this
     */
    public function setQueryParamTypes(array $types): self
    {
        $this->queryParamTypes = $types;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParamTypes(): array
    {
        return $this->queryParamTypes;
    }

    /**
     * Get the complete SQL string formed by the current specifications of this QueryBuilder
     *
     * @return string The sql query string
     */
    public function getSql(): string
    {
        if (null !== $this->sql && !$this->queryChanged) {
            return $this->sql;
        }

        if (!$this->queryParts['from']) {
            $this->queryParts['from'] = $this->getTable();
        }

        $this->sql = $this->getDbDriver()->getSql($this->queryType, $this->queryParts, $this->dbKeyConverter);

        $this->queryChanged = false;

        return $this->sql;
    }

    public function getRawSql(): string
    {
        if (!$this->queryParts['from']) {
            $this->queryParts['from'] = $this->getTable();
        }

        return $this->getDbDriver()->getRawSql(
            $this->queryType,
            $this->queryParts,
            $this->dbKeyConverter,
            $this->getBindParams()
        );
    }

    /**
     * Reset all SQL parts and parameters
     *
     * @return $this
     */
    public function resetQuery(): self
    {
        $this->resetQueryParam(null);
        $this->queryParamTypes = [];

        return $this->resetQueryParts();
    }

    /**
     * @return callable
     */
    public function getDbKeyConverter(): callable
    {
        return $this->dbKeyConverter;
    }

    /**
     * Return the record table name
     *
     * @return string|null
     * @svc
     */
    protected function getTable(): ?string
    {
        return $this->table ?? null;
    }

    /**
     * Returns the name of columns of current table
     *
     * @return array
     * @svc
     */
    protected function getColumns(): array
    {
        if (!$this->loadedColumns) {
            $columns = $this->getMetadataCache()->remember(
                'tableColumns:' . $this->getDb()->getDbname() . ':' . $this->getTable(),
                60,
                function () {
                    return $this->getDbDriver()->getColumns($this->getTable(), $this->phpKeyConverter);
                }
            );
            $this->columns = array_replace_recursive($columns, $this->columns);
            $this->loadedColumns = true;
        }
        return $this->columns;
    }

    /**
     * Check if column name exists
     *
     * @param string|int|null $name
     * @return bool
     * @svc
     */
    protected function hasColumn($name): bool
    {
        return isset($this->getColumns()[$name]);
    }

    /**
     * Return the value of the specified key in the columns config, if the key name does not exist, omit it
     *
     * @param string $name
     * @return array
     */
    protected function getColumnValues(string $name): array
    {
        $values = [];
        foreach ($this->getColumns() as $key => $column) {
            if (isset($column[$name])) {
                $values[$key] = $column[$name];
            }
        }
        return $values;
    }

    /**
     * Return the names of column
     *
     * @return array
     */
    protected function getColumnNames(): array
    {
        return array_keys($this->getColumns());
    }

    /**
     * Return the cache service that stores metadata
     *
     * @return BaseCache
     */
    protected function getMetadataCache(): BaseCache
    {
        return $this->metadataCache ?? $this->wei->nearCache;
    }

    /**
     * Set the cache service to store metadata
     *
     * @param BaseCache $metadataCache
     * @return $this
     */
    public function setMetadataCache(BaseCache $metadataCache): self
    {
        $this->metadataCache = $metadataCache;
        return $this;
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     */
    public function orWhereBetween(string $column, array $params): self
    {
        return $this->addWhere($column, 'BETWEEN', $params, 'OR');
    }

    /**
     * Executes the generated query and returns the first array result
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return array|null
     * @svc
     */
    protected function fetch($column = null, $operator = null, $value = null): ?array
    {
        $this->where(...func_get_args());
        $this->limit(1);
        $data = $this->execute();
        return $data ? $data[0] : null;
    }

    /**
     * Executes the generated query and returns all array results
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return array
     * @svc
     */
    protected function fetchAll($column = null, $operator = null, $value = null): array
    {
        $this->where(...func_get_args());
        $data = $this->execute();
        if ($this->queryParts['indexBy']) {
            $data = $this->executeIndexBy($data, $this->queryParts['indexBy']);
        }
        return $data;
    }

    /**
     * Executes the generated SQL and returns the found record object or null if not found
     *
     * @return array|null
     * @svc
     */
    protected function first(): ?array
    {
        return $this->fetch();
    }

    /**
     * @return array
     * @svc
     */
    protected function all(): array
    {
        return $this->fetchAll();
    }

    /**
     * @param string $column
     * @param string|null $index
     * @return array
     * @svc
     */
    protected function pluck(string $column, string $index = null): array
    {
        $columns = [$column];
        $index && $columns[] = $index;
        $data = $this->select($columns)->fetchAll();
        return array_column($data, $column, $index);
    }

    /**
     * @param int $count
     * @param callable $callback
     * @return bool
     * @svc
     */
    protected function chunk(int $count, callable $callback): bool
    {
        $this->limit($count);
        $page = 1;

        do {
            $qb = clone $this;
            $data = $qb->page($page)->all();

            // Do not execute callback when no new records are founded
            if (0 === count($data)) {
                break;
            }

            if (false === $callback($data, $page)) {
                return false;
            }

            ++$page;
        } while (count($data) === $count);

        return true;
    }

    /**
     * Executes a COUNT query to receive the rows number
     *
     * @param string $column
     * @return int
     * @svc
     */
    protected function cnt($column = '*'): int
    {
        return (int) $this->aggregate('COUNT', $column);
    }

    /**
     * Executes a MAX query to receive the max value of column
     *
     * @param string $column
     * @return string|null
     * @svc
     */
    protected function max(string $column): ?string
    {
        return $this->aggregate('MAX', $column);
    }

    /**
     * Execute a update query with specified data
     *
     * @param array|string $set
     * @param mixed $value
     * @return int
     * @svc
     */
    protected function update($set = [], $value = null): int
    {
        if (2 === func_num_args()) {
            $set = [$set => $value];
        }

        $params = [];
        foreach ($set as $field => $param) {
            $this->addQueryPart('set', $field, true);
            $params[] = $param;
        }
        $this->addQueryParams($params, 'set');

        $this->queryType = BaseDriver::UPDATE;
        return $this->execute();
    }

    /**
     * Execute a delete query with specified conditions
     *
     * @param mixed|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return int
     * @svc
     */
    protected function delete($column = null, $operator = null, $value = null): int
    {
        $this->where(...func_get_args());
        $this->queryType = BaseDriver::DELETE;
        return $this->execute();
    }

    /**
     * Sets the position of the first result to retrieve (the "offset")
     *
     * @param int|float|string $offset The first result to return
     * @return $this
     * @svc
     */
    protected function offset($offset): self
    {
        $offset = (int) $offset;
        $offset < 0 && $offset = 0;
        return $this->addQueryPart('offset', $offset);
    }

    /**
     * Sets the maximum number of results to retrieve (the "limit")
     *
     * @param int|float|string $limit The maximum number of results to retrieve
     * @return $this
     * @svc
     */
    protected function limit($limit): self
    {
        $limit = max(1, (int) $limit);
        $this->addQueryPart('limit', $limit);

        // 计算出新的offset
        if ($page = $this->getQueryPart('page')) {
            $this->page($page);
        }

        return $this;
    }

    /**
     * Sets the page number, the "OFFSET" value is equals "($page - 1) * LIMIT"
     *
     * @param int $page The page number
     * @return $this
     * @svc
     */
    protected function page($page): self
    {
        $page = max(1, (int) $page);
        $this->addQueryPart('page', $page);

        $limit = $this->getQueryPart('limit');
        if (!$limit) {
            $limit = 10;
            $this->addQueryPart('limit', $limit);
        }
        return $this->offset(($page - 1) * $limit);
    }

    /**
     * Specifies an item that is to be returned in the query result.
     * Replaces any previously specified selections, if any.
     *
     * @param array|string $columns the selection expressions
     * @return $this
     * @svc
     */
    protected function select($columns = ['*']): self
    {
        $this->queryType = BaseDriver::SELECT;

        $columns = is_array($columns) ? $columns : func_get_args();

        return $this->addQueryPart('select', (array) $columns, true);
    }

    /**
     * @param array|string $columns
     * @return $this
     * @svc
     */
    protected function selectDistinct($columns): self
    {
        $this->distinct(true);
        return $this->select(func_get_args());
    }

    /**
     * @param string $expression
     * @return $this
     * @svc
     */
    protected function selectRaw($expression): self
    {
        $this->queryType = BaseDriver::SELECT;

        return $this->addQueryPart('select', $this->raw($expression));
    }

    /**
     * Specifies columns that are not to be returned in the query result.
     * Replaces any previously specified selections, if any.
     *
     * @param array|string $columns
     * @return $this
     * @svc
     */
    protected function selectExcept($columns): self
    {
        $columns = array_diff($this->getColumnNames(), is_array($columns) ? $columns : [$columns]);

        return $this->select($columns);
    }

    /**
     * Specifies an item of the main table that is to be returned in the query result.
     * Default to all columns of the main table
     *
     * @param string $column
     * @return $this
     * @svc
     */
    protected function selectMain(string $column = '*'): self
    {
        return $this->select($this->getTable() . '.' . $column);
    }

    /**
     * Sets table for FROM query
     *
     * @param string $table
     * @param string|null $alias
     * @return $this
     * @svc
     */
    protected function from(string $table, $alias = null): self
    {
        $this->table = $table;
        return $this->addQueryPart('from', $table . ($alias ? ' ' . $alias : ''));
    }

    /**
     * @param string $table
     * @param mixed|null $alias
     * @return $this
     * @svc
     */
    protected function table(string $table, $alias = null): self
    {
        return $this->from($table, $alias);
    }

    /**
     * Adds a inner join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @param string $type
     * @return $this
     * @svc
     */
    protected function join(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null,
        string $type = 'INNER'
    ): self {
        return $this->addQueryPart('join', compact('table', 'first', 'operator', 'second', 'type'), true);
    }

    /**
     * Adds a inner join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @svc
     */
    protected function innerJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
        return $this->join(...func_get_args());
    }

    /**
     * Adds a left join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @svc
     */
    protected function leftJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    /**
     * Adds a right join to the query
     *
     * @param string $table The table name to join
     * @param string|null $first
     * @param string $operator
     * @param string|null $second
     * @return $this
     * @svc
     */
    protected function rightJoin(
        string $table,
        string $first = null,
        string $operator = '=',
        string $second = null
    ): self {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    /**
     * Specifies one or more restrictions to the query result.
     * Replaces any previously specified restrictions, if any.
     *
     * ```php
     * $user = wei()->db('user')->where('id = 1');
     * $user = wei()->db('user')->where('id = ?', 1);
     * $users = wei()->db('user')->where(array('id' => '1', 'username' => 'twin'));
     * $users = wei()->where(array('id' => array('1', '2', '3')));
     * ```
     *
     * @param array|Closure|string|null $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function where($column = null, $operator = null, $value = null): self
    {
        if (null === $column) {
            return $this;
        }

        if (is_array($column)) {
            foreach ($column as $key => $args) {
                if (is_string($key)) {
                    $this->where($key, '=', $args);
                } else {
                    $this->where(...$args);
                }
            }
            return $this;
        }

        if (2 === func_num_args()) {
            $value = $operator;
            $operator = '=';
        }

        return $this->addWhere($column, $operator, $value, 'AND');
    }

    /**
     * @param scalar $expression
     * @param mixed $params
     * @return $this
     * @svc
     */
    protected function whereRaw($expression, $params = null): self
    {
        return $this->where($this->raw($expression), null, $params);
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @svc
     */
    protected function whereBetween(string $column, array $params): self
    {
        return $this->addWhere($column, 'BETWEEN', $params);
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @svc
     */
    protected function whereNotBetween(string $column, array $params): self
    {
        return $this->addWhere($column, 'NOT BETWEEN', $params);
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @svc
     */
    protected function whereIn(string $column, array $params): self
    {
        return $this->addWhere($column, 'IN', $params);
    }

    /**
     * @param string $column
     * @param array $params
     * @return $this
     * @svc
     */
    protected function whereNotIn(string $column, array $params): self
    {
        return $this->addWhere($column, 'NOT IN', $params);
    }

    /**
     * @param string $column
     * @return $this
     * @svc
     */
    protected function whereNull(string $column): self
    {
        return $this->addWhere($column, 'NULL');
    }

    /**
     * @param string $column
     * @return $this
     * @svc
     */
    protected function whereNotNull(string $column): self
    {
        return $this->addWhere($column, 'NOT NULL');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function whereDate(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'DATE');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function whereMonth(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'MONTH');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function whereDay(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'DAY');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function whereYear(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'YEAR');
    }

    /**
     * @param string $column
     * @param mixed $opOrValue
     * @param mixed|null $value
     * @return $this
     * @svc
     */
    protected function whereTime(string $column, $opOrValue, $value = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'TIME');
    }

    /**
     * @param string $column
     * @param mixed $opOrColumn2
     * @param mixed|null $column2
     * @return $this
     * @svc
     */
    protected function whereColumn(string $column, $opOrColumn2, $column2 = null): self
    {
        return $this->addWhereArgs(func_get_args(), 'AND', 'COLUMN');
    }

    /**
     * 搜索字段是否包含某个值
     *
     * @param string $column
     * @param mixed $value
     * @param string $condition
     * @return $this
     * @svc
     */
    protected function whereContains(string $column, $value, string $condition = 'AND'): self
    {
        return $this->addWhere($column, 'LIKE', '%' . $value . '%', $condition);
    }

    /**
     * @param string $column
     * @param mixed $value
     * @param string $condition
     * @return $this
     * @svc
     */
    protected function whereNotContains(string $column, $value, string $condition = 'OR'): self
    {
        return $this->addWhere($column, 'NOT LIKE', '%' . $value . '%', $condition);
    }

    /**
     * Search whether a column has a value other than the default value
     *
     * @param string $column
     * @param bool $has
     * @return $this
     * @svc
     */
    protected function whereHas(string $column, bool $has = true): self
    {
        $config = $this->getColumns()[$column];
        $nullable = $config['nullable'] ?? false;

        if (array_key_exists('default', $config)) {
            if ($nullable) {
                if ($has) {
                    return $this->where(function (self $qb) use ($column, $config) {
                        $qb->where($column, '!=', $config['default'])->orWhereNull($column);
                    });
                } else {
                    return $this->where($column, '=', $config['default']);
                }
            }
            return $this->where($column, $has ? '!=' : '=', $config['default']);
        }

        if ($nullable) {
            return $has ? $this->whereNotNull($column) : $this->whereNull($column);
        }

        return $this->where($column, $has ? '!=' : '=', '');
    }

    /**
     * @param mixed $if
     * @param mixed ...$args
     * @return $this
     */
    protected function whereIf($if, ...$args): self
    {
        $if && $this->where(...$args);
        return $this;
    }

    /**
     * Search whether a column dont have a value other than the default value
     *
     * @param string $column
     * @return $this
     * @svc
     */
    protected function whereNotHas(string $column): self
    {
        return $this->whereHas($column, false);
    }

    /**
     * Specifies a grouping over the results of the query.
     * Replaces any previously specified groupings, if any.
     *
     * @param mixed $column the grouping column
     * @return $this
     * @svc
     */
    protected function groupBy($column): self
    {
        $column = is_array($column) ? $column : func_get_args();
        return $this->addQueryPart('groupBy', $column, true);
    }

    /**
     * Specifies a restriction over the groups of the query.
     * Replaces any previous having restrictions, if any.
     *
     * @param mixed $column
     * @param mixed $operator
     * @param mixed|null $value
     * @param mixed $condition
     * @return $this
     * @svc
     */
    protected function having($column, $operator, $value = null, $condition = 'AND'): self
    {
        if (2 === func_num_args()) {
            $value = $operator;
            $operator = '=';
        }

        if (null === $value) {
            $operator = 'NOT NULL' === $operator ? $operator : 'NULL';
        } else {
            $this->addQueryParam($value, 'having');
        }

        $this->addQueryPart('having', compact('column', 'operator', 'value', 'condition'), true);
        return $this;
    }

    /**
     * @param scalar $expression
     * @param mixed $params
     * @return $this
     * @svc
     */
    public function havingRaw($expression, $params = []): self
    {
        return $this->having($this->raw($expression), null, $params);
    }

    /**
     * Specifies an ordering for the query results.
     * Replaces any previously specified orderings, if any.
     *
     * @param string|Raw $column the ordering expression
     * @param string $order the ordering direction
     * @return $this
     * @svc
     */
    protected function orderBy($column, $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if (!in_array($order, ['ASC', 'DESC'], true)) {
            throw new \InvalidArgumentException('Parameter for "order" must be "ASC" or "DESC".');
        }

        return $this->addQueryPart('orderBy', [compact('column', 'order')], true);
    }

    /**
     * @param scalar $expression
     * @return $this
     * @svc
     */
    protected function orderByRaw($expression): self
    {
        return $this->orderBy($this->raw($expression));
    }

    /**
     * Adds a DESC ordering to the query
     *
     * @param string $field The name of field
     * @return $this
     * @svc
     */
    protected function desc(string $field): self
    {
        return $this->orderBy($field, 'DESC');
    }

    /**
     * Add an ASC ordering to the query
     *
     * @param string $field The name of field
     * @return $this
     * @svc
     */
    protected function asc(string $field): self
    {
        return $this->orderBy($field, 'ASC');
    }

    /**
     * Specifies a field to be the key of the fetched array
     *
     * @param string $column
     * @return $this
     * @svc
     */
    protected function indexBy(string $column): self
    {
        $this->addQueryPart('indexBy', $column);
        return $this;
    }

    /**
     * @return $this
     * @svc
     */
    protected function forUpdate(): self
    {
        return $this->lock(true);
    }

    /**
     * @return $this
     * @svc
     */
    protected function forShare(): self
    {
        return $this->lock(false);
    }

    /**
     * @param string|bool $lock
     * @return $this
     * @svc
     */
    protected function lock($lock): self
    {
        $this->addQueryPart('lock', $lock);
        return $this;
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @svc
     */
    protected function when($value, callable $callback, callable $default = null): self
    {
        if ($value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @svc
     */
    protected function unless($value, callable $callback, callable $default = null): self
    {
        if (!$value) {
            $callback($this, $value);
        } elseif ($default) {
            $default($this, $value);
        }
        return $this;
    }

    /**
     * @param callable|null $converter
     * @return $this
     * @svc
     */
    protected function setDbKeyConverter(callable $converter = null): self
    {
        $this->dbKeyConverter = $converter;
        return $this;
    }

    /**
     * @param callable|null $converter
     * @return $this
     * @svc
     */
    protected function setPhpKeyConverter(callable $converter = null): self
    {
        $this->phpKeyConverter = $converter;
        return $this;
    }

    /**
     * Return the db service
     *
     * @return Db
     */
    public function getDb(): Db
    {
        return $this->db ?? parent::__get('db');
    }

    /**
     * @return void
     * @internal only use for test
     */
    public static function resetDbDrivers(): void
    {
        static::$dbDrivers = [];
    }

    /**
     * @param array $data
     * @param string $column
     * @return array
     */
    protected function executeIndexBy($data, $column): array
    {
        if (!$data) {
            return $data;
        }

        $newData = [];
        foreach ($data as $row) {
            $newData[$row[$column]] = $row;
        }
        return $newData;
    }

    /**
     * Reset single SQL part
     *
     * @param string $name
     * @return $this
     */
    protected function resetQueryPart(string $name): self
    {
        $this->queryParts[$name] = is_array($this->queryParts[$name]) ? [] : null;
        $this->queryChanged = true;
        return $this;
    }

    /**
     * Either appends to or replaces a single, generic query part.
     *
     * @param string $name
     * @param mixed $value
     * @param bool $append
     * @return $this
     */
    protected function addQueryPart(string $name, $value, bool $append = false): self
    {
        $this->queryChanged = true;

        $isMultiple = is_array($this->queryParts[$name]);
        if ($isMultiple && !is_array($value)) {
            $value = [$value];
        }

        if ($append) {
            if (in_array($name, ['orderBy', 'groupBy', 'select', 'set'], true)) {
                // merge
                $this->queryParts[$name] = array_merge($this->queryParts[$name], $value);
            } elseif ($isMultiple) {
                // append
                $this->queryParts[$name][] = $value;
            }
        } else {
            // set
            $this->queryParts[$name] = $value;
        }

        return $this;
    }

    /**
     * @param string|Closure $column
     * @param string $operator
     * @param mixed|null $value
     * @param string $condition
     * @param string|null $type
     * @return $this
     */
    protected function addWhere(
        $column,
        ?string $operator,
        $value = null,
        string $condition = 'AND',
        string $type = null
    ): self {
        if ($column instanceof Closure) {
            /** @phpstan-ignore-next-line Allow new static */
            $query = new static([
                'wei' => $this->wei,
                'db' => $this->getDb(),
                'table' => $this->getTable(),
            ]);
            $column($query);
            $column = $query;
            $this->addQueryParams($query->getQueryParams());
        }

        if (null === $value) {
            $operator = 'NOT NULL' === $operator ? $operator : 'NULL';
        } elseif (is_array($value) && !in_array($operator, ['BETWEEN', 'NOT BETWEEN'], true)) {
            $operator = 'NOT IN' === $operator ? $operator : 'IN';
        }

        $this->addQueryPart('where', compact('column', 'operator', 'value', 'condition', 'type'), true);
        if (null !== $value) {
            $this->addQueryParam($value);
        }

        return $this;
    }

    /**
     * @param array $args
     * @param string $condition
     * @param string|null $type
     * @return $this
     */
    protected function addWhereArgs(array $args, string $condition = 'AND', string $type = null): self
    {
        if (2 === count($args)) {
            $operator = '=';
            [$column, $value] = $args;
        } else {
            [$column, $operator, $value] = $args;
        }
        return $this->addWhere($column, $operator, $value, $condition, $type);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function convertToDbKey(string $key): string
    {
        return isset($this->dbKeyConverter) ? call_user_func($this->dbKeyConverter, $key) : $key;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function convertToPhpKey(string $key): string
    {
        return isset($this->phpKeyConverter) ? call_user_func($this->phpKeyConverter, $key) : $key;
    }

    /**
     * Convert db array keys to php keys
     *
     * @param array $data
     * @return array
     */
    protected function convertKeysToPhpKeys(array $data): array
    {
        $newData = [];
        foreach ($data as $key => $value) {
            $newData[$this->convertToPhpKey($key)] = $value;
        }
        return $newData;
    }

    /**
     * Convert PHP array keys to db keys
     *
     * @param array $data
     * @return array
     */
    protected function convertKeysToDbKeys(array $data): array
    {
        $newData = [];
        foreach ($data as $key => $value) {
            $newData[$this->convertToDbKey($key)] = $value;
        }
        return $newData;
    }

    /**
     * @return BaseDriver
     */
    protected function getDbDriver(): BaseDriver
    {
        $driver = $this->getDb()->getDriver();
        if (!isset(static::$dbDrivers[$driver])) {
            $class = 'Wei\Db\\' . ucfirst($driver);
            static::$dbDrivers[$driver] = new $class();
        }
        return static::$dbDrivers[$driver];
    }

    /**
     * @return mixed
     */
    protected function fetchFromCache()
    {
        return $this->getCache()->remember($this->getCacheKey(), $this->getCacheTime(), function () {
            return $this->executeFetchAll($this->getSql(), $this->getBindParams(), $this->queryParamTypes);
        });
    }

    /**
     * @param string $sql
     * @param array $params
     * @param array $types
     * @return array
     * @internal
     */
    protected function executeFetchAll(string $sql, array $params = [], array $types = []): array
    {
        $data = $this->getDb()->fetchAll($sql, $params, $types);
        if (isset($data[0])) {
            foreach ($data as &$row) {
                $row = $this->convertKeysToPhpKeys($row);
            }
            return $data;
        } else {
            return $this->convertKeysToPhpKeys($data);
        }
    }
}
