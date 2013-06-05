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
        'orderBy' => array()
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
     * The index of the first result to retrieve
     *
     * @var integer
     */
    protected $offset;

    /**
     * The maximum number of results to retrieve
     *
     * @var integer
     */
    protected $limit;

    /**
     * Initializes a new <tt>QueryBuilder</tt>.
     *
     * @param \Widget\Db $db The database widget
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Get the type of the currently built query.
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the associated database widget for this query builder.
     *
     * @return \Widget\Db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Get the state of this query builder instance.
     *
     * @return integer Either QueryBuilder::STATE_DIRTY or QueryBuilder::STATE_CLEAN.
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Execute this query using the bound parameters and their types.
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

        $sql = '';

        switch ($this->type) {
            case self::DELETE:
                $sql = $this->getSqlForDelete();
                break;

            case self::UPDATE:
                $sql = $this->getSqlForUpdate();
                break;

            case self::SELECT:
            default:
                $sql = $this->getSqlForSelect();
                break;
        }

        $this->state = self::STATE_CLEAN;
        $this->sql = $sql;

        return $sql;
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
        $this->state = self::STATE_DIRTY;
        $this->offset = $offset;
        return $this;
    }

    /**
     * Gets the position of the first result the query object was set to retrieve (the "offset").
     * Returns NULL if {@link setoffset} was not applied to this QueryBuilder.
     *
     * @return integer The position of the first result.
     */
    public function getOffset()
    {
        return $this->offset;
    }


    /**
     * Sets the maximum number of results to retrieve (the "limit")
     *
     * @param integer $limit The maximum number of results to retrieve
     * @return QueryBuilder This QueryBuilder instance
     */
    public function limit($limit)
    {
        $this->state = self::STATE_DIRTY;
        $this->limit = $limit;
        return $this;
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
        $this->state = self::STATE_DIRTY;

        if (!$this->limit) {
            $this->limit = 10;
        }
        $this->offset = ($page - 1) * $this->limit;

        return $this;
    }

    /**
     * Gets the maximum number of results the query object was set to retrieve (the "limit")
     * Returns NULL if {@link setlimit} was not applied to this query builder
     *
     * @return integer Maximum number of results
     */
    public function getLimit()
    {
        return $this->limit;
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
     * @param null $type
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
     * Merge a query builder
     *
     * Is it necessary ?
     *
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public function merge(QueryBuilder $query)
    {
        $sqlParts = $query->getQueryParts();
        foreach ($sqlParts as $name => $sqlPart) {
            if ($sqlPart) {
                $this->add($name, $sqlPart, true);
            }
        }
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
     * Creates and adds a join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->join('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join The table name to join
     * @param string $alias The alias of the join table
     * @param string $condition The condition for the join
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function join($table, $on = null, $params = array(), $types = array())
    {
        return $this->innerJoin($table, $on, $params, $types);
    }

    /**
     * Creates and adds a join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->innerJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join The table name to join
     * @param string $alias The alias of the join table
     * @param string $condition The condition for the join
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function innerJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->add('join', array(
            'type'      => 'inner',
            'table'     => $table,
            'condition' => $on
        ), true);
    }

    /**
     * Creates and adds a left join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->leftJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join The table name to join
     * @param string $alias The alias of the join table
     * @param string $condition The condition for the join
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function leftJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->add('join', array(
            'type'      => 'left',
            'table'     => $table,
            'condition' => $on
        ), true);
    }

    /**
     * Creates and adds a right join to the query.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->rightJoin('u', 'phonenumbers', 'p', 'p.is_primary = 1');
     * </code>
     *
     * @param string $fromAlias The alias that points to a from clause
     * @param string $join The table name to join
     * @param string $alias The alias of the join table
     * @param string $condition The condition for the join
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function rightJoin($table, $on = null, $params = array(), $types = array())
    {
        return $this->add('join', array(
            'type'      => 'right',
            'table'     => $table,
            'condition' => $on
        ), true);
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
     * conjunction with any previously specified restrictions.
     *
     * <code>
     *     $qb = $conn->createQueryBuilder()
     *         ->select('u')
     *         ->from('users', 'u')
     *         ->where('u.username LIKE ?')
     *         ->andWhere('u.is_active = 1');
     * </code>
     *
     * @param mixed $where The query restrictions.
     * @return QueryBuilder This QueryBuilder instance.
     * @see where()
     */
    public function andWhere($conditions, $params = null, $type = array())
    {
        $conditions = $this->processCondition($conditions, $params, $type);

        return $this->add('where', $conditions, true, 'AND');
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * disjunction with any previously specified restrictions.
     *
     * <code>
     *     $qb = $em->createQueryBuilder()
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->where('u.id = 1')
     *         ->orWhere('u.id = 2');
     * </code>
     *
     * @param mixed $where The WHERE statement
     * @return QueryBuilder $qb
     * @see where()
     */
    public function orWhere($conditions, $params = null, $type = array())
    {
        $conditions = $this->processCondition($conditions, $params, $type);

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
     * @param mixed $having The restriction over the groups.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function having($conditions, $params = array(), $type = null)
    {
        $conditions = $this->processCondition($conditions, $params, $type);

        return $this->add('having', $conditions);
    }

    /**
     * Adds a restriction over the groups of the query, forming a logical
     * conjunction with any existing having restrictions.
     *
     * @param mixed $having The restriction to append.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function andHaving($conditions, $params = null, $type = array())
    {
        $conditions = $this->processCondition($conditions, $params, $type);

        return $this->add('having', $conditions, true, 'AND');
    }

    /**
     * Adds a restriction over the groups of the query, forming a logical
     * disjunction with any existing having restrictions.
     *
     * @param mixed $having The restriction to add.
     * @return QueryBuilder This QueryBuilder instance.
     */
    public function orHaving($conditions, $params = null, $type = array())
    {
        $conditions = $this->processCondition($conditions, $params, $type);

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
     * Get a query part by its name.
     *
     * @param string $queryPartName
     * @return mixed $queryPart
     */
    public function getQueryPart($queryPartName)
    {
        return $this->sqlParts[$queryPartName];
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
    protected function getSqlForSelect()
    {
        if (!$this->sqlParts['select']) {
            $this->sqlParts['select'] = array('*');
        }

        $query = 'SELECT ' . implode(', ', $this->sqlParts['select']) . ' FROM ';

        // FROM
        $query .= $this->sqlParts['from']['table'] . ($this->sqlParts['from']['alias'] ? ' ' . $this->sqlParts['from']['alias'] : '');

        // JOIN
        foreach ($this->sqlParts['join'] as $join) {
            $query .= ' ' . strtoupper($join['type'])
                . ' JOIN ' . $join['table']
                . ' ON ' . $join['condition'];
        }

        $query .= ($this->sqlParts['where'] !== null ? ' WHERE ' . ((string) $this->sqlParts['where']) : '')
            . ($this->sqlParts['groupBy'] ? ' GROUP BY ' . implode(', ', $this->sqlParts['groupBy']) : '')
            . ($this->sqlParts['having'] !== null ? ' HAVING ' . ((string) $this->sqlParts['having']) : '')
            . ($this->sqlParts['orderBy'] ? ' ORDER BY ' . implode(', ', $this->sqlParts['orderBy']) : '');

        // TODO mssql & oracle
        if ($this->limit !== null) {
            $query .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $query .= ' OFFSET ' . $this->offset;
        }

        return $query;
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

    protected function processCondition($conditions, $params, $type)
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
                $this->setParameters($params, $type);
            } else {
                $this->setParameter(0, $params, $type);
            }
        }

        return $conditions;
    }
}