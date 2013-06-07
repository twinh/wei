<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Widget\Db;

use Widget\Db;
use Widget\Db\Collection;

/**
 * A minimalist version of Doctrine DBAL QueryBuilder
 *
 * QueryBuilder class is responsible to dynamically create SQL queries.
 *
 * Important: Verify that every feature you use will work with your database vendor.
 * SQL Query Builder does not attempt to validate the generated SQL at all.
 *
 * The query builder does no validation whatsoever if certain features even work with the
 * underlying database vendor. Limit queries and joins are NOT applied to UPDATE and DELETE statements
 * even if some vendors such as MySQL support it.
 *
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @link        http://www.doctrine-project.com
 * @author      Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author      Benjamin Eberlei <kontakt@beberlei.de>
 * @author      Twin Huang <twinhuang@qq.com>
 */
class QueryBuilder
{
    /* The query types. */
    const SELECT = 0;
    const DELETE = 1;
    const UPDATE = 2;

    /** The builder states. */
    const STATE_DIRTY = 0;
    const STATE_CLEAN = 1;

    /**
     * The database widget
     *
     * @var Db
     */
    protected $db = null;

    /**
     * @var array The array of SQL parts collected.
     */
    protected $sqlParts = array(
        'select'  => array(),
        'from'    => array(),
        'join'    => array(),
        'set'     => array(),
        'where'   => null,
        'groupBy' => array(),
        'having'  => null,
        'orderBy' => array(),
        'limit'   => null,
        'offset'  => null,
    );

    /**
     * @var string The complete SQL string for this query.
     */
    protected $sql;

    /**
     * @var array The query parameters.
     */
    protected $params = array();

    /**
     * @var array The parameter type map of this query.
     */
    protected $paramTypes = array();

    /**
     * @var integer The type of query this is. Can be select, update or delete.
     */
    protected $type = self::SELECT;

    /**
     * @var integer The state of the query object. Can be dirty or clean.
     */
    protected $state = self::STATE_CLEAN;

    /**
     * Initializes a new QueryBuilder
     *
     * @param Db $db The database widget
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Get the type of the currently built query
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the associated database widget for this query builder
     *
     * @return \Widget\Db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Get the state of this query builder instance
     *
     * @return integer Either QueryBuilder::STATE_DIRTY or QueryBuilder::STATE_CLEAN
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Execute this query using the bound parameters and their types
     *
     * @return mixed
     */
    public function execute()
    {
        if ($this->type == self::SELECT) {
            return $this->db->fetchAll($this->getSql(), $this->params, $this->paramTypes);
        } else {
            return $this->db->executeUpdate($this->getSql(), $this->params, $this->paramTypes);
        }
    }

    public function find()
    {
        $data = $this
            //->limit(1)
            ->execute();

        $table = $this->sqlParts['from']['table'];

        if ($data) {
            return $this->db->create($table, $data[0]);
        } else  {
            return false;
        }
    }

    public function findAll()
    {
        $data = $this->execute();

        $table = $this->sqlParts['from']['table'];

        $records = array();
        foreach ($data as $row) {
            $records[] = $this->db->create($table, $row);
        }

        return new Collection($records);
    }

    public function count()
    {
        return $this->db->fetchColumn($this->getSqlForCount(), $this->params);
    }

    /**
     * Get the complete SQL string formed by the current specifications of this QueryBuilder
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('id, group_id')
     *     ->from('user');
     *
     * echo $qb->getSql(); // SELECT id, group_id FROM user u
     * ```
     *
     * @return string The sql query string
     */
    public function getSql()
    {
        if ($this->sql !== null && $this->state === self::STATE_CLEAN) {
            return $this->sql;
        }

        switch ($this->type) {
            case self::DELETE:
                $this->sql = $this->getSqlForDelete();
                break;

            case self::UPDATE:
                $this->sql = $this->getSqlForUpdate();
                break;

            case self::SELECT:
            default:
                $this->sql = $this->getSqlForSelect();
                break;
        }

        $this->state = self::STATE_CLEAN;

        return $this->sql;
    }

    /**
     * Sets a query parameter for the query being constructed
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('*')
     *     ->from('user', 'u')
     *     ->where('u.id = :userId')
     *     ->setParameter(':userId', 1);
     * ```
     *
     * @param string|integer $key The parameter position or name
     * @param mixed $value The parameter value
     * @param string|null $type PDO::PARAM_*
     * @return QueryBuilder This QueryBuilder instance
     */
    public function setParameter($key, $value, $type = null)
    {
        if ($type !== null) {
            $this->paramTypes[$key] = $type;
        }

        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Sets a collection of query parameters for the query being constructed
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('*')
     *     ->from('user', 'u')
     *     ->where('u.id = :userId1 OR u.id = :userId2')
     *     ->setParameters(array(
     *         ':userId1' => 1,
     *         ':userId2' => 2
     *     ));
     * ```
     *
     * @param array $params The query parameters to set
     * @param array $types  The query parameters types to set
     * @return QueryBuilder This QueryBuilder instance
     */
    public function setParameters(array $params, array $types = array())
    {
        $this->paramTypes = $types;
        $this->params = $params;

        return $this;
    }

    /**
     * Gets all defined query parameters for the query being constructed.
     *
     * @return array The currently defined query parameters.
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Gets a (previously set) query parameter of the query being constructed
     *
     * @param mixed $key The key (index or name) of the bound parameter
     * @return mixed The value of the bound parameter
     */
    public function getParameter($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * Sets the position of the first result to retrieve (the "offset")
     *
     * @param integer $offset The first result to return
     * @return QueryBuilder This QueryBuilder instance
     */
    public function offset($offset)
    {
        return $this->add('offset', $offset);
    }

    /**
     * Sets the maximum number of results to retrieve (the "limit")
     *
     * @param integer $limit The maximum number of results to retrieve
     * @return QueryBuilder This QueryBuilder instance
     */
    public function limit($limit)
    {
        return $this->add('limit', $limit);
    }

    /**
     * Sets the page number
     *
     * The "OFFSET" value is equals "($page - 1) * $this->rows"
     * The "LIMIT" value is equals "$this->rows"
     *
     * @param int $page The page number
     * @return $this
     */
    public function page($page)
    {
        $limit = $this->get('limit');

        if (!$limit) {
            $limit = 10;
            $this->add('limit', $limit);
        }

        return $this->add('offset', ($page - 1) * $limit);
    }

    /**
     * Either appends to or replaces a single, generic query part.
     *
     * The available parts are: 'select', 'from', 'set', 'where',
     * 'groupBy', 'having' and 'orderBy'.
     *
     * @param string $sqlPartName
     * @param string $sqlPart
     * @param boolean $append
     * @param string $type
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function add($sqlPartName, $sqlPart, $append = false, $type = null)
    {
        $isArray = is_array($sqlPart);
        $isMultiple = is_array($this->sqlParts[$sqlPartName]);

        if ($isMultiple && !$isArray) {
            $sqlPart = array($sqlPart);
        }

        $this->state = self::STATE_DIRTY;

        if ($append) {
            if ($sqlPartName == "where" || $sqlPartName == "having") {
                if ($this->sqlParts[$sqlPartName]) {
                    if ($type) {
                        $this->sqlParts[$sqlPartName] = '(' . $this->sqlParts[$sqlPartName] .  ') ' . $type . ' (' . $sqlPart  . ')';
                    } else {
                        $this->sqlParts[$sqlPartName] = '(' . $this->sqlParts[$sqlPartName] .  ')' . $sqlPart;
                    }
                } else {
                    $this->sqlParts[$sqlPartName] = $sqlPart;
                }
            } else if ($sqlPartName == "orderBy" || $sqlPartName == "groupBy" || $sqlPartName == "select" || $sqlPartName == "set") {
                foreach ($sqlPart as $part) {
                    $this->sqlParts[$sqlPartName][] = $part;
                }
            } else if ($isArray && is_array($sqlPart[key($sqlPart)])) {
                $key = key($sqlPart);
                $this->sqlParts[$sqlPartName][$key][] = $sqlPart[$key];
            } else if ($isMultiple) {
                $this->sqlParts[$sqlPartName][] = $sqlPart;
            } else {
                $this->sqlParts[$sqlPartName] = $sqlPart;
            }

            return $this;
        }

        $this->sqlParts[$sqlPartName] = $sqlPart;

        return $this;
    }

    /**
     * Specifies an item that is to be returned in the query result.
     * Replaces any previously specified selections, if any.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.id', 'p.id')
     *         ->from('users', 'u')
     *         ->leftJoin('u', 'phonenumbers', 'p', 'u.id = p.user_id');
     * </code>
     *
     * @param mixed $select The selection expressions.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function select($select = null)
    {
        $this->type = self::SELECT;

        if (empty($select)) {
            return $this;
        }

        $selects = is_array($select) ? $select : func_get_args();

        return $this->add('select', $selects, false);
    }

    /**
     * Adds an item that is to be returned in the query result.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.id')
     *         ->addSelect('p.id')
     *         ->from('users', 'u')
     *         ->leftJoin('u', 'phonenumbers', 'u.id = p.user_id');
     * </code>
     *
     * @param mixed $select The selection expression.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function addSelect($select = null)
    {
        $this->type = self::SELECT;

        if (empty($select)) {
            return $this;
        }

        $selects = is_array($select) ? $select : func_get_args();

        return $this->add('select', $selects, true);
    }

    /**
     * Turns the query being built into a bulk delete query that ranges over
     * a certain table.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->delete('users', 'u')
     *         ->where('u.id = :user_id');
     *         ->setParameter(':user_id', 1);
     * </code>
     *
     * @param string $table The table whose rows are subject to the deletion.
     * @param string $alias The table alias used in the constructed query.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function delete($table = null, $alias = null)
    {
        $this->type = self::DELETE;

        if ($table) {
            return $this->from($table, $alias);
        } else {
            return $this;
        }
    }

    /**
     * Turns the query being built into a bulk update query that ranges over
     * a certain table
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->update('users', 'u')
     *         ->set('u.password', md5('password'))
     *         ->where('u.id = ?');
     * </code>
     *
     * @param string $table The table whose rows are subject to the update.
     * @param string $alias The table alias used in the constructed query.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function update($table = null, $alias = null)
    {
        $this->type = self::UPDATE;

        if ($table) {
            return $this->from($table, $alias);
        } else {
            return $this;
        }
    }

    /**
     * Create and add a query root corresponding to the table identified by the
     * given alias, forming a cartesian product with any existing query roots.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.id')
     *         ->from('users', 'u')
     * </code>
     *
     * @param string $from   The table
     * @param string $alias  The alias of the table
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function from($from, $alias = null)
    {
        return $this->add('from', array(
            'table' => $from,
            'alias' => $alias
        ));
    }

    /**
     * Adds a inner join to the query
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->join('post', 'post.user_id = user.id');
     * ```
     *
     * @param string $table The table name to join
     * @param string $on The condition for the join
     * @param array $params The parameter values
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function join($table, $on = null, $params = array(), $types = array())
    {
        return $this->addJoin('inner', $table, $on, $params, $types);
    }

    /**
     * Adds a inner join to the query
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->innerJoin('post', 'post.user_id = user.id');
     * ```
     *
     * @param string $table The table name to join
     * @param string $on The condition for the join
     * @param array $params The parameter values
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function innerJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->addJoin('inner', $table, $on, $params, $types);
    }

    /**
     * Adds a left join to the query
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->leftJoin('post', 'post.user_id = user.id');
     * ```
     *
     * @param string $table The table name to join
     * @param string $on The condition for the join
     * @param array $params The parameter values
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function leftJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->addJoin('left', $table, $on, $params, $types);
    }

    /**
     * Adds a right join to the query
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->rightJoin('post', 'post.user_id = user.id');
     * ```
     *
     * @param string $table The table name to join
     * @param string $on The condition for the join
     * @param array $params The parameter values
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function rightJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->addJoin('right', $table, $on, $params, $types);
    }

    /**
     * Sets a new value for a column in a bulk update query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->update('users', 'u')
     *         ->set('u.password', md5('password'))
     *         ->where('u.id = ?');
     * </code>
     *
     * @param string $key The column to set.
     * @param string $value The value, expression, placeholder, etc.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function set($key, $value)
    {
        return $this->add('set', $key .' = ' . $value, true);
    }

    /**
     * Specifies one or more restrictions to the query result.
     * Replaces any previously specified restrictions, if any.
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->where('id = ?', '1');
     *
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->where(array(
     *         'id' => '1',
     *         'username' => 'twin'
     *      ));
     *
     * $qb = $db->createQueryBuilder()
     *     ->from('user')
     *     ->where(array(
     *          'id' => array('1', '2', '3'
     *     ));
     * ```
     *
     * @param $conditions
     * @param null $params
     * @param array $type
     * @internal param mixed $predicates The restriction predicates.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function where($conditions, $params = null, $type = array())
    {
        $conditions = $this->processCondition($conditions, $params, $type);

        return $this->add('where', $conditions);
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * conjunction with any previously specified restrictions
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('*')
     *     ->from('users', 'u')
     *     ->where('u.username LIKE ?')
     *     ->andWhere('u.is_active = 1');
     * ```
     *
     * @param string $conditions The WHERE conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function andWhere($conditions, $params = null, $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('where', $conditions, true, 'AND');
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * disjunction with any previously specified restrictions.
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('u.name')
     *     ->from('users', 'u')
     *     ->where('u.id = 1')
     *     ->orWhere('u.id = 2');
     *
     * ```
     *
     * @param string $conditions The WHERE conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function orWhere($conditions, $params = null, $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('where', $conditions, true, 'OR');
    }

    /**
     * Specifies a grouping over the results of the query.
     * Replaces any previously specified groupings, if any.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->groupBy('u.id');
     * </code>
     *
     * @param mixed $groupBy The grouping expression.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function groupBy($groupBy)
    {
        if (empty($groupBy)) {
            return $this;
        }

        $groupBy = is_array($groupBy) ? $groupBy : func_get_args();

        return $this->add('groupBy', $groupBy, false);
    }


    /**
     * Adds a grouping expression to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->groupBy('u.lastLogin');
     *         ->addGroupBy('u.createdAt')
     * </code>
     *
     * @param mixed $groupBy The grouping expression.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function addGroupBy($groupBy)
    {
        if (empty($groupBy)) {
            return $this;
        }

        $groupBy = is_array($groupBy) ? $groupBy : func_get_args();

        return $this->add('groupBy', $groupBy, true);
    }

    /**
     * Specifies a restriction over the groups of the query.
     * Replaces any previous having restrictions, if any.
     *
     * @param string $conditions The having conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function having($conditions, $params = array(), $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('having', $conditions);
    }

    /**
     * Adds a restriction over the groups of the query, forming a logical
     * conjunction with any existing having restrictions.
     *
     * @param string $conditions The HAVING conditions to append
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function andHaving($conditions, $params = null, $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('having', $conditions, true, 'AND');
    }

    /**
     * Adds a restriction over the groups of the query, forming a logical
     * disjunction with any existing having restrictions.
     *
     * @param string $conditions The HAVING conditions to add
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return QueryBuilder
     */
    public function orHaving($conditions, $params = null, $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('having', $conditions, true, 'OR');
    }

    /**
     * Specifies an ordering for the query results.
     * Replaces any previously specified orderings, if any.
     *
     * @param string $sort The ordering expression.
     * @param string $order The ordering direction.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function orderBy($sort, $order = null)
    {
        return $this->add('orderBy', $sort . ' ' . (! $order ? 'ASC' : $order), false);
    }

    /**
     * Adds an ordering to the query results.
     *
     * @param string $sort The ordering expression.
     * @param string $order The ordering direction.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function addOrderBy($sort, $order = null)
    {
        return $this->add('orderBy', $sort . ' ' . (! $order ? 'ASC' : $order), true);
    }

    /**
     * Returns a SQL query part by its name
     *
     * @param string $name The name of SQL part
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->sqlParts[$name]) ? $this->sqlParts[$name] : false;
    }

    /**
     * Get all query parts.
     *
     * @return array $sqlParts
     */
    public function getQueryParts()
    {
        return $this->sqlParts;
    }

    /**
     * Reset SQL parts
     *
     * @param array $queryPartNames
     * @return QueryBuilder
     */
    public function resetQueryParts($queryPartNames = null)
    {
        if (is_null($queryPartNames)) {
            $queryPartNames = array_keys($this->sqlParts);
        }

        foreach ($queryPartNames as $queryPartName) {
            $this->resetQueryPart($queryPartName);
        }

        return $this;
    }

    /**
     * Reset single SQL part
     *
     * @param string $queryPartName
     * @return QueryBuilder
     */
    public function resetQueryPart($queryPartName)
    {
        $this->sqlParts[$queryPartName] = is_array($this->sqlParts[$queryPartName])
            ? array() : null;

        $this->state = self::STATE_DIRTY;

        return $this;
    }

    /**
     * Converts this instance into an SELECT string in SQL
     *
     * @throws \RuntimeException When table alias not registered
     * @return string
     */
    protected function getSqlForSelect($count = false)
    {
        $parts = $this->sqlParts;

        if (!$parts['select']) {
            $parts['select'] = array('*');
        }

        $query = 'SELECT ' . implode(', ', $parts['select']) . ' FROM ';

        // FROM
        $query .= $parts['from']['table'] . ($parts['from']['alias'] ? ' ' . $parts['from']['alias'] : '');

        // JOIN
        foreach ($parts['join'] as $join) {
            $query .= ' ' . strtoupper($join['type'])
                . ' JOIN ' . $join['table']
                . ' ON ' . $join['condition'];
        }

        $query .= ($parts['where'] !== null ? ' WHERE ' . ((string) $parts['where']) : '')
            . ($parts['groupBy'] ? ' GROUP BY ' . implode(', ', $parts['groupBy']) : '')
            . ($parts['having'] !== null ? ' HAVING ' . ((string) $parts['having']) : '')
            . ($parts['orderBy'] ? ' ORDER BY ' . implode(', ', $parts['orderBy']) : '');

        if (false === $count) {
            // TODO mssql & oracle
            if ($parts['limit'] !== null) {
                $query .= ' LIMIT ' . $parts['limit'];
            }

            if ($parts['offset'] !== null) {
                $query .= ' OFFSET ' . $parts['offset'];
            }
        }

        return $query;
    }

    /**
     * Converts this instance into an SELECT COUNT string in SQL
     */
    protected function getSqlForCount()
    {
        return "SELECT COUNT(*) FROM (" . $this->getSqlForSelect(true) . ") widget_count";
    }

    /**
     * Converts this instance into an UPDATE string in SQL.
     *
     * @return string
     */
    protected function getSqlForUpdate()
    {
        $table = $this->sqlParts['from']['table'] . ($this->sqlParts['from']['alias'] ? ' ' . $this->sqlParts['from']['alias'] : '');
        $query = 'UPDATE ' . $table
            . ' SET ' . implode(", ", $this->sqlParts['set'])
            . ($this->sqlParts['where'] !== null ? ' WHERE ' . ((string) $this->sqlParts['where']) : '');

        return $query;
    }

    /**
     * Converts this instance into a DELETE string in SQL.
     *
     * @return string
     */
    protected function getSqlForDelete()
    {
        $table = $this->sqlParts['from']['table'] . ($this->sqlParts['from']['alias'] ? ' ' . $this->sqlParts['from']['alias'] : '');
        $query = 'DELETE FROM ' . $table . ($this->sqlParts['where'] !== null ? ' WHERE ' . ((string) $this->sqlParts['where']) : '');

        return $query;
    }

    /**
     * Gets a string representation of this QueryBuilder which corresponds to
     * the final SQL query being constructed.
     *
     * @return string The string representation of this QueryBuilder.
     */
    public function __toString()
    {
        return $this->getSql();
    }

    /**
     * Generate condition string for WHERE or Having statement
     *
     * @param $conditions
     * @param $params
     * @param $types
     * @return string
     */
    protected function processCondition($conditions, $params, $types)
    {
        if (is_array($conditions)) {
            $where = '';
            $params = array();
            foreach ($conditions as $column => $condition) {
                if (is_array($condition)) {
                    $where[] = $column . ' IN (' . implode(', ', array_pad(array(), count($condition), '?')) . ')';
                    $params = array_merge($params, $condition);
                } else {
                    $where[] = $column . " = ?";
                    $params[] = $condition;
                }
            }
            $conditions = implode(' AND ', $where);
        }

        if ($params) {
            if (is_array($params)) {
                $this->params = array_merge($this->params, $params);
                $this->paramTypes = array_merge($this->paramTypes, $types);
            } else {
                $this->params[] = $params;
                if ($this->paramTypes) {
                    $this->paramTypes[] = $types;
                }
            }
        }

        return $conditions;
    }

    /**
     * Adds a join to the query
     *
     * @param $type
     * @param $table
     * @param string $on
     * @param array $params
     * @param array $types
     * @return $this
     */
    protected function addJoin($type, $table, $on = null, $params = array(), $types = array())
    {
        $this->add('join', array(
            'type'      => $type,
            'table'     => $table,
            'condition' => $on
        ), true);

        return $this;
    }
}