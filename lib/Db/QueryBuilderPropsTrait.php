<?php

namespace Wei\Db;

use Wei\BaseCache;
use Wei\Db;

/**
 * The properties of the the query builder
 *
 * @internal Expected to be used only by QueryBuilder and BaseModel
 */
trait QueryBuilderPropsTrait
{
    /**
     * The name of the table
     *
     * @var string|null
     */
    protected $table;

    /**
     * The column names of the table
     *
     * If leave it blank, it will automatic generate form the database table,
     * or fill it to speed up the record
     *
     * @var array
     */
    protected $columns = [];

    /**
     * A callback, use to convert the PHP array key name to a table or column name,
     * usually to convert from camel case to snake case
     *
     * @var callable|null
     */
    protected $dbKeyConverter;

    /**
     * A callback, used to convert the name of the table or column to the format required by PHP,
     * usually to convert from snake case to camel case
     *
     * @var callable|null
     */
    protected $phpKeyConverter;

    /**
     * The parts of query
     *
     * - indexBy  A field to be the key of the fetched array, if not provided, return default number index array
     *
     * @var array
     */
    protected $queryParts = [
        'select' => [],
        'distinct' => null,
        'from' => null,
        'join' => [],
        'set' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'orderBy' => [],
        'limit' => null,
        'offset' => null,
        'page' => null,
        'lock' => '',
        'aggregate' => null,
        'indexBy' => null,
    ];

    /**
     * The query parameters
     *
     * @var array
     */
    protected $queryParams = [
        'set' => [],
        'where' => [],
        'having' => [],
    ];

    /**
     * The parameter type map of this query
     *
     * @var array
     */
    protected $queryParamTypes = [];

    /**
     * The type of query this is. Can be select, update or delete
     *
     * @var int
     */
    protected $queryType = BaseDriver::SELECT;

    /**
     * Indicates whether the query statement is changed, so the SQL must be regenerated.
     *
     * @var bool
     */
    protected $queryChanged = false;

    /**
     * @var string the complete SQL string for this query
     */
    protected $sql;

    /**
     * The cache service that stores metadata
     *
     * @var BaseCache|null
     */
    protected $metadataCache;

    /**
     * Indicates whether the columns config has been loaded
     *
     * @var bool
     */
    protected $loadedColumns;

    /**
     * The db service
     *
     * @var Db|null
     */
    protected $db;

    /**
     * @var BaseDriver[]
     */
    protected static $dbDrivers = [];
}
