<?php

namespace Wei;

/**
 * A MySQL schema builder
 *
 * @property Db $db
 */
class Schema extends Base
{
    public const TYPE_BIG_INT = 'bigInt';

    public const TYPE_BOOL = 'bool';

    public const TYPE_CHAR = 'char';

    public const TYPE_DATE = 'date';

    public const TYPE_DATETIME = 'datetime';

    public const TYPE_DECIMAL = 'decimal';

    public const TYPE_DOUBLE = 'double';

    public const TYPE_INT = 'int';

    public const TYPE_LONG_TEXT = 'longText';

    public const TYPE_MEDIUM_INT = 'mediumInt';

    public const TYPE_MEDIUM_TEXT = 'mediumText';

    public const TYPE_TINY_INT = 'tinyInt';

    public const TYPE_SMALL_INT = 'smallInt';

    public const TYPE_STRING = 'string';

    public const TYPE_TEXT = 'text';

    public const TYPE_TIMESTAMP = 'timestamp';

    public const TYPE_JSON = 'json';

    public const TYPE_BINARY = 'binary';

    public const TYPE_VAR_BINARY = 'varBinary';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $engine = 'InnoDB';

    /**
     * @var string
     */
    protected $charset = '';

    /**
     * @var string
     */
    protected $collate = '';

    /**
     * @var string
     */
    protected $tableComment = '';

    /**
     * @var bool
     */
    protected $autoDefault = true;

    /**
     * @var bool
     */
    protected $defaultNullable = false;

    /**
     * The column type for user id column, like created_by and updated_by
     *
     * @var string
     */
    protected $userIdType = 'uInt';

    /**
     * @var string
     */
    protected $autoIncrement = '';

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $indexes = [];

    /**
     * @var string
     */
    protected $lastColumn;

    /**
     * @var bool
     */
    protected $isChange = false;

    /**
     * @var array
     */
    protected $columnDefaults = [
        'nullable' => false,
        'comment' => '',
        'unsigned' => false,
        'change' => false,
    ];

    /**
     * The default values for types, false means no default value
     *
     * @var array
     */
    protected $typeDefaults = [
        self::TYPE_BIG_INT => '0',
        self::TYPE_BOOL => '0',
        self::TYPE_CHAR => '',
        self::TYPE_DATE => null,
        self::TYPE_DATETIME => null,
        self::TYPE_DECIMAL => '0',
        self::TYPE_DOUBLE => '0',
        self::TYPE_INT => '0',
        self::TYPE_LONG_TEXT => false,
        self::TYPE_MEDIUM_INT => '0',
        self::TYPE_MEDIUM_TEXT => false,
        self::TYPE_TINY_INT => '0',
        self::TYPE_SMALL_INT => '0',
        self::TYPE_STRING => '',
        self::TYPE_TEXT => false,
        self::TYPE_TIMESTAMP => null,
        self::TYPE_JSON => false,
        self::TYPE_BINARY => '',
        self::TYPE_VAR_BINARY => '',
    ];

    /**
     * @var array
     */
    protected $typeMaps = [
        'mysql' => [
            self::TYPE_BIG_INT => 'bigint',
            self::TYPE_BOOL => 'tinyint(1)',
            self::TYPE_CHAR => 'char',
            self::TYPE_DATE => 'date',
            self::TYPE_DATETIME => 'datetime',
            self::TYPE_DECIMAL => 'decimal',
            self::TYPE_DOUBLE => 'double',
            self::TYPE_INT => 'int',
            self::TYPE_LONG_TEXT => 'longtext',
            self::TYPE_MEDIUM_INT => 'mediumint',
            self::TYPE_MEDIUM_TEXT => 'mediumtext',
            self::TYPE_TINY_INT => 'tinyint',
            self::TYPE_SMALL_INT => 'smallint',
            self::TYPE_STRING => 'varchar',
            self::TYPE_TEXT => 'text',
            self::TYPE_TIMESTAMP => 'timestamp',
            self::TYPE_JSON => 'json',
            self::TYPE_BINARY => 'binary',
            self::TYPE_VAR_BINARY => 'varbinary',
        ],
    ];

    /**
     * The SQL to check if table exists
     *
     * @var array
     */
    protected $checkTableSqls = [
        'mysql' => 'SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1;',
    ];

    /**
     * @var array
     */
    protected $stringTypes = [
        self::TYPE_CHAR,
        self::TYPE_STRING,
        self::TYPE_JSON,
    ];

    /**
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->reset();
        $this->table = $table;

        return $this;
    }

    /**
     * @param string $column
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addColumn($column, $type, array $options = [])
    {
        $this->lastColumn = $column;
        $this->columns[$column] = ['type' => $type] + $options;

        return $this;
    }

    /**
     * Add a drop column command
     *
     * @param string|array $column
     * @return $this
     */
    public function dropColumn($column)
    {
        $this->isChange = true;
        foreach ((array) $column as $item) {
            $this->columns[$item] = ['command' => 'drop'];
        }

        return $this;
    }

    /**
     * Set position for current column
     *
     * @param string $column
     * @return $this
     */
    public function after($column)
    {
        return $this->updateLastColumn('after', $column);
    }

    /**
     * @return $this
     */
    public function change()
    {
        $this->isChange = true;

        return $this->updateLastColumn('change', true);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function updateLastColumn($name, $value)
    {
        $this->columns[$this->lastColumn][$name] = $value;

        return $this;
    }

    /**
     * Return create/change table sql
     *
     * @return string
     */
    public function getSql()
    {
        if ($this->hasTable($this->table)) {
            $this->isChange = true;
        }

        $table = $this->db->getTable($this->table);
        $columnSql = $this->getCreateDefinition();

        if ($this->isChange) {
            $sql = "ALTER TABLE $table" . rtrim($columnSql);
        } else {
            $sql = "CREATE TABLE $table ($columnSql)";
            $sql .= $this->getTableOptionSql();
        }

        $sql .= ';';

        return $sql;
    }

    /**
     * @param bool $defaultNullable
     * @return $this
     */
    public function setDefaultNullable($defaultNullable)
    {
        $this->defaultNullable = (bool) $defaultNullable;
        return $this;
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getDefaultNullable()
    {
        return $this->defaultNullable;
    }

    /**
     * @return bool
     */
    public function isDefaultNullable()
    {
        return $this->defaultNullable;
    }

    /**
     * Execute a drop table sql
     *
     * @param string|array $table
     * @param bool $ifExists
     * @return $this
     */
    public function drop($table, $ifExists = false)
    {
        foreach ((array) $table as $name) {
            $sql = 'DROP TABLE ';
            if ($ifExists) {
                $sql .= 'IF EXISTS ';
            }
            $sql .= $this->db->getTable($name);

            $this->db->executeUpdate($sql);
        }

        return $this;
    }

    /**
     * Execute a drop table if exist sql
     *
     * @param string|array $table
     * @return $this
     */
    public function dropIfExists($table)
    {
        return $this->drop($table, true);
    }

    /**
     * Check if table exists
     *
     * @param string $table
     * @return bool
     */
    public function hasTable($table)
    {
        $parts = explode('.', $table);
        if (1 === count($parts)) {
            $db = $this->db->getDbname();
            $table = $parts[0];
        } else {
            list($db, $table) = $parts;
        }
        $table = $this->db->getTable($table);

        $tableExistsSql = $this->checkTableSqls[$this->db->getDriver()];

        return (bool) $this->db->fetchColumn($tableExistsSql, [$db, $table]);
    }

    /**
     * Check if database exists
     *
     * @param string $database
     * @return bool
     * @svc
     */
    protected function hasDatabase(string $database): bool
    {
        return (bool) $this->db->fetchColumn('SHOW DATABASES LIKE ?', [$database]);
    }

    /**
     * Create a database
     *
     * @param string $database
     * @return $this
     * @svc
     */
    protected function createDatabase(string $database): self
    {
        $this->db->executeUpdate('CREATE DATABASE ' . $database);
        return $this;
    }

    /**
     * Drop a database
     *
     * @param string $database
     * @return $this
     * @svc
     */
    protected function dropDatabase(string $database): self
    {
        $this->db->executeUpdate('DROP DATABASE ' . $database);
        return $this;
    }

    /**
     * Set user id type
     *
     * @param string $userIdType
     * @return $this
     * @svc
     */
    protected function setUserIdType(string $userIdType): self
    {
        if ('u' === substr($userIdType, 0, 1)) {
            $type = lcfirst(substr($userIdType, 1));
        } else {
            $type = $userIdType;
        }

        if (!array_key_exists($type, $this->typeDefaults)) {
            throw new \InvalidArgumentException(sprintf('Invalid user id type "%s"', $userIdType));
        }

        $this->userIdType = $userIdType;
        return $this;
    }

    /**
     * Get user id type
     *
     * @return string
     * @svc
     */
    protected function getUserIdType(): string
    {
        return $this->userIdType;
    }

    /**
     * @return $this
     */
    public function exec()
    {
        $this->db->executeUpdate($this->getSql());

        return $this;
    }

    /**
     * Add a big int column
     *
     * @param string $column
     * @return $this
     */
    public function bigInt($column)
    {
        return $this->addColumn($column, self::TYPE_BIG_INT);
    }

    /**
     * Add an unsigned big int column
     *
     * @param string $column
     * @return $this
     */
    public function uBigInt($column)
    {
        return $this->bigInt($column)->unsigned();
    }

    /**
     * Add a char column
     *
     * @param string $column
     * @return $this
     */
    public function bool($column)
    {
        return $this->addColumn($column, self::TYPE_BOOL);
    }

    /**
     * Add a char column
     *
     * @param string $column
     * @param int $length
     * @return $this
     */
    public function char($column, $length = 255)
    {
        return $this->addColumn($column, self::TYPE_CHAR, ['length' => $length]);
    }

    /**
     * Add a decimal column
     *
     * @param string $column
     * @param int $length
     * @param int $scale
     * @return $this
     */
    public function decimal($column, $length = 10, $scale = 2)
    {
        return $this->addColumn($column, self::TYPE_DECIMAL, ['length' => $length, 'scale' => $scale]);
    }

    /**
     * Add an unsigned decimal column
     *
     * @param string $column
     * @param int $length
     * @param int $scale
     * @return $this
     */
    public function uDecimal($column, $length = 10, $scale = 2)
    {
        return $this->decimal($column, $length, $scale)->unsigned();
    }

    /**
     * Add a double column
     *
     * @param string $column
     * @return $this
     */
    public function double($column)
    {
        return $this->addColumn($column, self::TYPE_DOUBLE);
    }

    /**
     * Add an unsigned double column
     *
     * @param string $column
     * @return $this
     */
    public function uDouble($column)
    {
        return $this->double($column)->unsigned();
    }

    /**
     * Add a string(varchar) column
     *
     * @param string $column
     * @param int $length
     * @return $this
     */
    public function string($column, $length = 255)
    {
        return $this->addColumn($column, self::TYPE_STRING, ['length' => $length]);
    }

    public function binary(string $column, int $length): self
    {
        return $this->addColumn($column, self::TYPE_BINARY, ['length' => $length]);
    }

    public function varBinary(string $column, int $length): self
    {
        return $this->addColumn($column, self::TYPE_VAR_BINARY, ['length' => $length]);
    }

    /**
     * Add a int column
     *
     * @param string $column
     * @param int|null $length
     * @return $this
     */
    public function int($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_INT, ['length' => $length]);
    }

    /**
     * Add an unsigned int column
     *
     * @param string $column
     * @param int|null $length
     * @return $this
     */
    public function uInt($column, $length = null)
    {
        return $this->int($column, $length)->unsigned();
    }

    /**
     * Add a long text column
     *
     * @param $column
     * @return $this
     */
    public function longText($column)
    {
        return $this->addColumn($column, self::TYPE_LONG_TEXT);
    }

    /**
     * Add a medium int column
     *
     * @param $column
     * @return $this
     */
    public function mediumInt($column)
    {
        return $this->addColumn($column, self::TYPE_MEDIUM_INT);
    }

    /**
     * Add an unsigned medium int column
     *
     * @param $column
     * @return $this
     */
    public function uMediumInt($column)
    {
        return $this->mediumInt($column)->unsigned();
    }

    /**
     * Add a medium text column
     *
     * @param $column
     * @return $this
     */
    public function mediumText($column)
    {
        return $this->addColumn($column, self::TYPE_MEDIUM_TEXT);
    }

    /**
     * Add a tiny int column
     *
     * @param $column
     * @param int|null $length
     * @return $this
     */
    public function tinyInt($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_TINY_INT, ['length' => $length]);
    }

    /**
     * Add an unsigned tiny int column
     *
     * @param $column
     * @param int|null $length
     * @return $this
     */
    public function uTinyInt($column, $length = null)
    {
        return $this->tinyInt($column, $length)->unsigned();
    }

    /**
     * Add a small int column
     *
     * @param $column
     * @param int|null $length
     * @return $this
     */
    public function smallInt($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_SMALL_INT, ['length' => $length]);
    }

    /**
     * Add an unsigned small int column
     *
     * @param $column
     * @param int|null $length
     * @return $this
     */
    public function uSmallInt($column, $length = null)
    {
        return $this->smallInt($column, $length)->unsigned();
    }

    /**
     * Add a text column
     *
     * @param $column
     * @return $this
     */
    public function text($column)
    {
        return $this->addColumn($column, self::TYPE_TEXT);
    }

    /**
     * Add a date column
     *
     * @param string $column
     * @return $this
     */
    public function date($column)
    {
        return $this->addColumn($column, self::TYPE_DATE);
    }

    /**
     * Add a datetime column
     *
     * @param string $column
     * @return $this
     */
    public function datetime($column)
    {
        return $this->addColumn($column, self::TYPE_DATETIME);
    }

    /**
     * Add a timestamp column
     *
     * @param string $column
     * @return $this
     */
    public function timestamp($column)
    {
        return $this->addColumn($column, self::TYPE_TIMESTAMP);
    }

    /**
     * Add a timestamp column
     *
     * @param string $column
     * @return $this
     */
    public function json($column)
    {
        return $this->addColumn($column, self::TYPE_JSON);
    }

    /**
     * Set comment for current column
     *
     * @param string $comment
     * @return $this
     */
    public function comment($comment)
    {
        return $this->updateLastColumn('comment', $comment);
    }

    /**
     * Set default value for current column
     *
     * @param mixed $value
     * @return $this
     */
    public function defaults($value)
    {
        return $this->updateLastColumn('default', $value);
    }

    /**
     * Set nullable attribute for current column
     *
     * @param bool $nullable
     * @return $this
     */
    public function nullable($nullable = true)
    {
        return $this->updateLastColumn('nullable', $nullable);
    }

    /**
     * Set unsigned attribute for current column
     *
     * @param bool $unsigned
     * @return $this
     */
    public function unsigned($unsigned = true)
    {
        return $this->updateLastColumn('unsigned', $unsigned);
    }

    /**
     *  Add a unique index to specified column
     *
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function unique($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

    /**
     * Add a primary index to specified column
     *
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function primary($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

    /**
     * Add a index to specified column
     *
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function index($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

    /**
     * Add a drop index command
     *
     * @param string|array $index
     * @return $this
     */
    public function dropIndex($index)
    {
        $this->isChange = true;
        foreach ((array) $index as $item) {
            $this->indexes[$item] = ['command' => 'drop'];
        }

        return $this;
    }

    /**
     * Set current column is auto increment
     *
     * @return $this
     */
    public function autoIncrement()
    {
        $this->autoIncrement = $this->lastColumn;

        return $this;
    }

    /**
     * Add a int auto increment id to table
     *
     * @param string $column
     * @return $this
     */
    public function id($column = 'id')
    {
        $this->uInt($column)->autoIncrement();

        return $this->primary($column);
    }

    /**
     * Add a big int auto increment id to table
     *
     * @param string $column
     * @return $this
     */
    public function bigId($column = 'id')
    {
        $this->uBigInt($column)->autoIncrement();

        return $this->primary($column);
    }

    /**
     * Add created_at and updated_at columns to current table
     *
     * @return $this
     */
    public function timestamps()
    {
        return $this->timestamp('created_at')->timestamp('updated_at');
    }

    /**
     * Add created_by and updated_by columns to current table
     *
     * @return $this
     */
    public function userstamps()
    {
        return $this->{$this->userIdType}('created_by')->{$this->userIdType}('updated_by');
    }

    /**
     * Add deleted_at and deleted_by columns to current table
     *
     * @return $this
     */
    public function softDeletable()
    {
        return $this->timestamp('deleted_at')->{$this->userIdType}('deleted_by');
    }

    /**
     * Remove deleted_at and deleted_by columns in current table
     *
     * @return $this
     */
    public function dropSoftDeletable(): self
    {
        return $this->dropColumn(['deleted_at', 'deleted_by']);
    }

    /**
     * Add a user id column to table
     *
     * @param string $column
     * @return $this
     */
    public function userId(string $column = 'user_id'): self
    {
        return $this->{$this->userIdType}($column);
    }

    /**
     * Set the comment for current table
     *
     * @param string $comment
     * @return $this
     */
    public function tableComment($comment)
    {
        $this->tableComment = $comment;

        return $this;
    }

    /**
     * Add a rename column command
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function renameColumn($from, $to)
    {
        $this->columns[$from] = ['command' => 'rename', 'from' => $from, 'to' => $to];

        return $this;
    }

    /**
     * Execute a rename table sql
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function rename($from, $to)
    {
        $sql = sprintf('RENAME TABLE %s TO %s', $this->db->getTable($from), $this->db->getTable($to));
        $this->db->executeUpdate($sql);

        return $this;
    }

    protected function reset()
    {
        $this->table = '';
        $this->columns = [];
        $this->indexes = [];
        $this->lastColumn = null;
        $this->autoIncrement = '';
        $this->tableComment = '';
        $this->isChange = false;
    }

    /**
     * @return string
     */
    protected function getCreateDefinition()
    {
        $columnSqls = [];
        foreach ($this->columns as $column => $options) {
            $columnSqls[] = '  ' . $this->getColumnSql($column, $options);
        }

        foreach ($this->indexes as $index => $options) {
            $columnSqls[] = ' ' . $this->getIndexSql($index, $options);
        }

        $sql = "\n" . implode(",\n", $columnSqls) . "\n";

        return $sql;
    }

    /**
     * @param string $index
     * @param array $options
     * @return string
     */
    protected function getIndexSql($index, array $options)
    {
        $sql = ' ';

        if (isset($options['command'])) {
            $method = 'get' . ucfirst($options['command']) . 'IndexSql';

            return $this->{$method}($index, $options);
        }

        if ('index' !== $options['type']) {
            $sql .= strtoupper($options['type']) . ' ';
        }

        if ($this->isChange) {
            $sql .= 'ADD ';
        }

        $sql .= 'KEY ' . $index . ' (' . implode(', ', $options['columns']) . ')';

        return $sql;
    }

    /**
     * @return string
     */
    protected function getTableOptionSql()
    {
        $sql = '';
        if ($this->engine) {
            $sql .= ' ENGINE=' . $this->engine;
        }

        if ($this->charset) {
            $sql .= ' CHARSET=' . $this->charset;
        }

        if ($this->collate) {
            $sql .= ' COLLATE=' . $this->collate;
        }

        if ($this->tableComment) {
            $sql .= " COMMENT='" . $this->tableComment . "'";
        }

        return $sql;
    }

    /**
     * @param string $column
     * @param array $options
     * @return string
     */
    protected function getColumnSql($column, array $options)
    {
        $sql = '';

        if (isset($options['command'])) {
            $method = 'get' . ucfirst($options['command']) . 'ColumnSql';

            return $this->{$method}($column, $options);
        }

        if ($this->isChange) {
            if (isset($options['change'])) {
                $sql .= $this->getChangeColumnSql($column);
            } else {
                $sql .= 'ADD COLUMN ';
            }
        }

        return $sql . $this->buildColumnSql($column, $options);
    }

    /**
     * @param string $column
     * @param array $options
     * @return string
     */
    protected function buildColumnSql($column, array $options)
    {
        $sql = $column . ' ' . $this->getTypeSql($options) . ' ';
        $sql .= $this->getUnsignedSql($options);

        // Avoid automatic generate "NOT NULL DEFAULT NULL" error statement, convert it to "NULL DEFAULT NULL"
        if (
            !array_key_exists('default', $options)
            && $this->autoDefault
            && null === $this->typeDefaults[$options['type']]
            && !$this->defaultNullable
        ) {
            $options['nullable'] = true;
        }

        $sql .= $this->getNullSql(isset($options['nullable']) ? $options['nullable'] : $this->defaultNullable);

        // Auto increment do not have default value
        if ($this->autoIncrement == $column) {
            $sql .= ' AUTO_INCREMENT';
        } else {
            $defaultSql = $this->getDefaultSql($options);
            if ($defaultSql) {
                $sql .= ' ' . $defaultSql;
            }
        }

        if (isset($options['comment'])) {
            $sql .= " COMMENT '" . $options['comment'] . "'";
        }

        if (isset($options['after'])) {
            $sql .= ' AFTER ' . $options['after'];
        }

        return $sql;
    }

    /**
     * @param string $column
     * @return string
     */
    protected function getChangeColumnSql($column)
    {
        return 'CHANGE COLUMN ' . $column . ' ';
    }

    /**
     * @param string $column
     * @return string
     */
    protected function getDropColumnSql($column)
    {
        return 'DROP COLUMN ' . $column;
    }

    /**
     * @param string $column
     * @param array $options
     * @return string
     * @throws \Exception when column not found in table
     */
    protected function getRenameColumnSql($column, $options)
    {
        $table = $this->db->getTable($this->table);
        $dbColumns = $this->db->fetchAll("SHOW FULL COLUMNS FROM $table");
        $fromColumn = null;
        foreach ($dbColumns as $dbColumn) {
            if ($dbColumn['Field'] == $options['from']) {
                $fromColumn = $dbColumn;
                break;
            }
        }
        if (!$fromColumn) {
            throw new \Exception(sprintf('Column "%s" not found in table "%s"', $options['from'], $this->table));
        }

        $newOptions = [];
        $newOptions['type'] = $fromColumn['Type'];
        $newOptions['nullable'] = 'YES' === $fromColumn['Null'];
        $newOptions['comment'] = $fromColumn['Comment'];

        $keys = array_keys($this->typeDefaults, false, true);
        if (!in_array($newOptions['type'], $keys, true)) {
            $newOptions['default'] = $fromColumn['Default'];
        }

        $sql = $this->buildColumnSql($options['to'], $newOptions);
        if ('auto_increment' === $fromColumn['Extra']) {
            $sql .= ' AUTO_INCREMENT';
        }

        return $this->getChangeColumnSql($column) . $sql;
    }

    /**
     * @param string $index
     * @return string
     */
    protected function getDropIndexSql($index)
    {
        return 'DROP INDEX ' . $index;
    }

    /**
     * @param array $options
     * @return string
     */
    protected function getTypeSql(array $options)
    {
        $driver = $this->db->getDriver();
        $typeMap = $this->typeMaps[$driver];

        // Allow custom type (eg int(10) unsigned) from rename
        if (!isset($typeMap[$options['type']])) {
            return $options['type'];
        }

        $sql = $typeMap[$options['type']];

        if (isset($options['length'])) {
            if (isset($options['scale'])) {
                $sql .= '(' . $options['length'] . ', ' . $options['scale'] . ')';
            } else {
                $sql .= '(' . $options['length'] . ')';
            }
        }

        return $sql;
    }

    /**
     * @param array $options
     * @return string
     */
    protected function getUnsignedSql(array $options)
    {
        if (isset($options['unsigned'])) {
            return $options['unsigned'] ? 'unsigned ' : '';
        }
        return '';
    }

    /**
     * @param bool $null
     * @return string
     */
    protected function getNullSql($null)
    {
        return $null ? 'NULL' : 'NOT NULL';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function getDefaultSql(array $options)
    {
        $hasDefault = array_key_exists('default', $options);
        if (!$hasDefault && !$this->autoDefault) {
            return '';
        }

        if (!$hasDefault && $this->autoDefault) {
            $options['default'] = $this->typeDefaults[$options['type']];
        }

        $default = $options['default'];
        if (false === $default) {
            return '';
        }

        switch (true) {
            case '' === $default:
                $value = "''";
                break;

            case in_array($options['type'], $this->stringTypes, true):
                $value = var_export($default, true);
                break;

            case null === $default:
                $value = 'NULL';
                break;

            case is_bool($default):
                $value = (string) $default;
                break;

            default:
                $value = $default;
        }

        return 'DEFAULT ' . $value;
    }

    /**
     * @param string|array $columns
     * @param string $name
     * @param string $type
     * @return $this
     */
    protected function addIndex($columns, $name, $type)
    {
        $columns = (array) $columns;
        $name || $name = $this->generateIndexName($columns);

        $this->indexes[$name] = [
            'columns' => $columns,
            'type' => $type,
        ];

        return $this;
    }

    /**
     * @param array $columns
     * @return string
     */
    protected function generateIndexName(array $columns)
    {
        return implode('_', $columns);
    }
}
