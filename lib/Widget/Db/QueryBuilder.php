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
class QueryBuilder implements \ArrayAccess
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
        'from'    => null,
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
     * A field to be the key of the fetched array, if not provided, return
     * default number index array
     *
     * @var string
     */
    protected $indexBy;

    /**
     * @var string The complete SQL string for this query.
     */
    protected $sql;

    /**
     * The main table in SQL query
     *
     * @var string
     */
    protected $table;

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
     * @var Record
     */
    protected $record;

    /**
     * @var Collection
     */
    protected $records;

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
     * Returns the table name in query
     */
    public function getTable()
    {
        return $this->table;
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

    /**
     * Executes the generated SQL and returns the found record object or false
     *
     * @return false|Record
     */
    public function find()
    {
        $data = $this->fetch();

        if ($data) {
            return $this->db->create($this->table, $data);
        } else  {
            return false;
        }
    }

    /**
     * Executes the generated SQL and returns the found record collection object or false
     *
     * @return Collection
     */
    public function findAll()
    {
        $data = $this->fetchAll();

        $records = array();
        foreach ($data as $key => $row) {
            $records[$key] = $this->db->create($this->table, $row, false);
        }

        return new Collection($records);
    }

    /**
     * Executes the generated query and returns the first array result
     *
     * @return array|false
     */
    public function fetch()
    {
        $this->limit(1);
        $data = $this->execute();
        return $data ? $data[0] : false;
    }

    /**
     * Executes the generated query and returns all array results
     *
     * @return array|false
     */
    public function fetchAll()
    {
        $data = $this->execute();

        if ($data && $this->indexBy && !array_key_exists($this->indexBy, $data[0])) {
            throw new \RuntimeException(sprintf('Index field "%s" not found in fetched data', $this->indexBy));
        }

        if ($this->indexBy) {
            $newData = array();
            foreach ($data as $row) {
                $newData[$row[$this->indexBy]] = $row;
            }
            return $newData;
        } else {
            return $data;
        }
    }

    /**
     * Execute a COUNT query to receive the rows number
     *
     * @return int
     */
    public function count()
    {
        return (int)$this->db->fetchColumn($this->getSqlForCount(), $this->params);
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function offset($offset)
    {
        return $this->add('offset', $offset);
    }

    /**
     * Sets the maximum number of results to retrieve (the "limit")
     *
     * @param integer $limit The maximum number of results to retrieve
     * @return $this
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
     * 'groupBy', 'having', 'orderBy', 'limit' and 'offset'.
     *
     * @param string $sqlPartName
     * @param string $sqlPart
     * @param boolean $append
     * @param string $type
     * @return $this
     */
    public function add($sqlPartName, $sqlPart, $append = false, $type = null)
    {
        if (!$sqlPart) {
            return $this;
        }

        $isArray = is_array($sqlPart);
        $isMultiple = is_array($this->sqlParts[$sqlPartName]);

        if ($isMultiple && !$isArray) {
            $sqlPart = array($sqlPart);
        }

        $this->state = self::STATE_DIRTY;

        if ($append) {
            if ($sqlPartName == 'where' || $sqlPartName == 'having') {
                if ($this->sqlParts[$sqlPartName]) {
                    $this->sqlParts[$sqlPartName] = '(' . $this->sqlParts[$sqlPartName] .  ') ' . $type . ' (' . $sqlPart  . ')';
                } else {
                    $this->sqlParts[$sqlPartName] = $sqlPart;
                }
            } elseif ($sqlPartName == 'orderBy' || $sqlPartName == 'groupBy' || $sqlPartName == 'select' || $sqlPartName == 'set') {
                foreach ($sqlPart as $part) {
                    $this->sqlParts[$sqlPartName][] = $part;
                }
            } elseif ($isMultiple) {
                $this->sqlParts[$sqlPartName][] = $sqlPart;
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
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->select('u.id', 'p.id')
     *     ->from('users u')
     *     ->leftJoin('phones p', 'u.id = p.user_id');
     * ```
     *
     * @param mixed $select The selection expressions.
     * @return $this
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
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->select('u.id')
     *     ->addSelect('p.id')
     *     ->from('users u')
     *     ->leftJoin('phones p', 'u.id = p.user_id');
     * ```
     *
     * @param mixed $select The selection expression.
     * @return $this
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
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->delete('users u')
     *     ->where('u.id = :user_id');
     *     ->setParameter(':user_id', 1);
     * ```
     *
     * @param string $table The table whose rows are subject to the deletion.
     * @return $this
     */
    public function delete($table = null)
    {
        $this->type = self::DELETE;

        if ($table) {
            return $this->from($table);
        } else {
            return $this;
        }
    }

    /**
     * Turns the query being built into a bulk update query that ranges over
     * a certain table
     *
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->update('users u')
     *     ->set('u.password', md5('password'))
     *     ->where('u.id = ?');
     * ```
     *
     * @param string $table The table whose rows are subject to the update
     * @return $this
     */
    public function update($table = null)
    {
        $this->type = self::UPDATE;

        if ($table) {
            return $this->from($table);
        } else {
            return $this;
        }
    }

    /**
     * Sets table for FROM query
     *
     * ```php
     * // SELECT * FROM users
     * $qb = $db->createQueryBuilder()
     *     ->select('*')
     *     ->from('users');
     *
     * // SELECT * FROM users u
     * $qb = $qb->createQueryBuilder()
     *     ->select('*')
     *     ->from('users u');
     * ```
     *
     * @param string $from   The table
     * @return $this
     */
    public function from($from)
    {
        $pos = strpos($from, ' ');
        if (false !== $pos) {
            $this->table = substr($from, 0, $pos);
        } else {
            $this->table = $from;
        }

        return $this->add('from', $from);
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
     * @return $this
     */
    public function join($table, $on = null)
    {
        return $this->add('join', array(
            'type'      => 'inner',
            'table'     => $table,
            'condition' => $on
        ), true);
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
     * @return $this
     */
    public function innerJoin($table, $on = null)
    {
        return $this->add('join', array(
            'type'      => 'inner',
            'table'     => $table,
            'condition' => $on
        ), true);
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
     * @return $this
     */
    public function leftJoin($table, $on = null)
    {
        return $this->add('join', array(
            'type'      => 'left',
            'table'     => $table,
            'condition' => $on
        ), true);
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
     * @return $this
     */
    public function rightJoin($table, $on = null)
    {
        return $this->add('join', array(
            'type'      => 'right',
            'table'     => $table,
            'condition' => $on
        ), true);
    }

    /**
     * Sets a new value for a field in a bulk update query.
     *
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->update('users u')
     *     ->set('u.password = ?')
     *     ->where('u.id = ?');
     * ```
     *
     * @param string $set The SET clause
     * @return $this
     */
    public function set($set)
    {
        return $this->add('set', $set, true);
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
     *          'id' => array('1', '2', '3')
     *     ));
     * ```
     *
     * @param string $conditions The WHERE conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return $this
     */
    public function where($conditions, $params = array(), $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('where', $conditions);
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * conjunction with any previously specified restrictions
     *
     * ```php
     * $qb = $db->createQueryBuilder()
     *     ->select('*')
     *     ->from('users u')
     *     ->where('u.username LIKE ?')
     *     ->andWhere('u.is_active = 1');
     * ```
     *
     * @param string $conditions The WHERE conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return $this
     */
    public function andWhere($conditions, $params = array(), $types = array())
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
     *     ->from('users u')
     *     ->where('u.id = 1')
     *     ->orWhere('u.id = 2');
     * ```
     *
     * @param string $conditions The WHERE conditions
     * @param array $params The condition parameters
     * @param array $types The parameter types
     * @return $this
     */
    public function orWhere($conditions, $params = array(), $types = array())
    {
        $conditions = $this->processCondition($conditions, $params, $types);

        return $this->add('where', $conditions, true, 'OR');
    }

    /**
     * Specifies a grouping over the results of the query.
     * Replaces any previously specified groupings, if any.
     *
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->select('u.name')
     *     ->from('users u')
     *     ->groupBy('u.id');
     * ```
     *
     * @param mixed $groupBy The grouping expression.
     * @return $this
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
     * ```
     * $qb = $db->createQueryBuilder()
     *     ->select('u.name')
     *     ->from('users u')
     *     ->groupBy('u.lastLogin');
     *     ->addGroupBy('u.createdAt')
     * ```
     *
     * @param mixed $groupBy The grouping expression.
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function andHaving($conditions, $params = array(), $types = array())
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
     * @return $this
     */
    public function orHaving($conditions, $params = array(), $types = array())
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
     * @return $this
     */
    public function orderBy($sort, $order = 'ASC')
    {
        return $this->add('orderBy', $sort . ' ' . ($order ?: 'ASC'), false);
    }

    /**
     * Adds an ordering to the query results.
     *
     * @param string $sort The ordering expression.
     * @param string $order The ordering direction.
     * @return $this
     */
    public function addOrderBy($sort, $order = 'ASC')
    {
        return $this->add('orderBy', $sort . ' ' . ($order ?: 'ASC'), true);
    }

    /**
     * Specifies a field to be the key of the fetched array
     *
     * @param string $field
     * @return $this
     */
    public function indexBy($field)
    {
        $this->indexBy = $field;
        return $this;
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
    public function getAll()
    {
        return $this->sqlParts;
    }

    /**
     * Reset all SQL parts
     *
     * @param array $queryPartNames
     * @return $this
     */
    public function resetAll($queryPartNames = null)
    {
        if (is_null($queryPartNames)) {
            $queryPartNames = array_keys($this->sqlParts);
        }

        foreach ($queryPartNames as $queryPartName) {
            $this->reset($queryPartName);
        }

        return $this;
    }

    /**
     * Reset single SQL part
     *
     * @param string $queryPartName
     * @return $this
     */
    public function reset($queryPartName)
    {
        $this->sqlParts[$queryPartName] = is_array($this->sqlParts[$queryPartName])
            ? array() : null;

        $this->state = self::STATE_DIRTY;

        return $this;
    }

    /**
     * Converts this instance into an SELECT string in SQL
     *
     * @param bool $count
     * @return string
     */
    protected function getSqlForSelect($count = false)
    {
        $parts = $this->sqlParts;

        if (!$parts['select']) {
            $parts['select'] = array('*');
        }

        $query = 'SELECT ' . implode(', ', $parts['select']) . ' FROM ' . $parts['from'];

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
        $query = 'UPDATE ' . $this->sqlParts['from']
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
        return 'DELETE FROM ' . $this->sqlParts['from'] . ($this->sqlParts['where'] !== null ? ' WHERE ' . ((string) $this->sqlParts['where']) : '');
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
     * Receives the record field value
     *
     * @param string $name
     * @throws \InvalidArgumentException When field not found
     * @return string
     */
    public function __get($name)
    {
        $this->loadRecord($name);
        $this->record->__get($name);
    }

    /**
     * Set field value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->loadRecord($name);
        $this->record->__set($name, $value);
    }

    /**
     * Check if the offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $this->loadRecord($offset);
        return isset($this->record[$offset]);
    }

    /**
     * Get the offset value
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->loadRecord($offset);
        return $this->record[$offset];
    }

    /**
     * Set the offset value
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->loadRecord($offset);
        $this->record[$offset] = $value;
    }

    /**
     * Unset the offset
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->loadRecord($offset);
        unset($this->record[$offset]);
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
            $where = array();
            $params = array();
            foreach ($conditions as $field => $condition) {
                if (is_array($condition)) {
                    $where[] = $field . ' IN (' . implode(', ', array_pad(array(), count($condition), '?')) . ')';
                    $params = array_merge($params, $condition);
                } else {
                    $where[] = $field . " = ?";
                    $params[] = $condition;
                }
            }
            $conditions = implode(' AND ', $where);
        }

        if ($params !== false) {
            if (is_array($params)) {
                $this->params = array_merge($this->params, $params);
                $this->paramTypes = array_merge($this->paramTypes, $types);
            } else {
                $this->params[] = $params;
                if ($types) {
                    $this->paramTypes[] = $types;
                }
            }
        }

        return $conditions;
    }

    /**
     * Load record by array offset
     *
     * @param int|string $offset
     * @return false|Collection|Record
     */
    protected function loadRecord($offset)
    {
        if (!$this->record) {
            if (is_int($offset)) {
                $this->record = $this->findAll();
            } else {
                $this->record = $this->find();
            }
        }
        return $this->record;
    }
}