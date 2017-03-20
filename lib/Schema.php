<?php

namespace Wei;

/**
 * A MySQL schema builder
 *
 * @property \Wei\Db $db
 */
class Schema extends Base
{
    const TYPE_BIG_INT = 'bigInt';

    const TYPE_BOOL = 'bool';

    const TYPE_CHAR = 'char';

    const TYPE_DATE = 'date';

    const TYPE_DATETIME = 'datetime';

    const TYPE_DECIMAL = 'decimal';

    const TYPE_DOUBLE = 'double';

    const TYPE_INT = 'int';

    const TYPE_LONG_TEXT = 'longText';

    const TYPE_MEDIUM_INT = 'mediumInt';

    const TYPE_MEDIUM_TEXT = 'mediumText';

    const TYPE_TINY_INT = 'tinyInt';

    const TYPE_SMALL_INT = 'smallInt';

    const TYPE_STRING = 'string';

    const TYPE_TEXT = 'text';

    const TYPE_TIMESTAMP = 'timestamp';

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
    protected $autoUnsigned = true;

    /**
     * @var string
     */
    protected $autoIncrement = '';

    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var array
     */
    protected $indexes = array();

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
    protected $columnDefaults = array(
        'nullable' => false,
        'comment' => '',
        'unsigned' => false,
        'change' => false,
    );

    /**
     * The default values for types, false means no default value
     *
     * @var array
     */
    protected $typeDefaults = array(
        self::TYPE_BIG_INT => '0',
        self::TYPE_BOOL => '0',
        self::TYPE_CHAR => '',
        self::TYPE_DATE => "'0000-00-00'",
        self::TYPE_DATETIME => "'0000-00-00 00:00:00'",
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
        self::TYPE_TIMESTAMP => "'0000-00-00 00:00:00'",
    );

    /**
     * @var array
     */
    protected $typeMaps = array(
        'mysql' => array(
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
        ),
    );

    /**
     * The SQL to check if table exists
     *
     * @var array
     */
    protected $checkTableSqls = array(
        'mysql' => "SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1;",
    );

    /**
     * @var array
     */
    protected $unsignedTypes = array(
        self::TYPE_BOOL,
        self::TYPE_TINY_INT,
        self::TYPE_SMALL_INT,
        self::TYPE_MEDIUM_INT,
        self::TYPE_INT,
        self::TYPE_BIG_INT,
        self::TYPE_DECIMAL,
        self::TYPE_DOUBLE,
    );

    /**
     * @var array
     */
    protected $stringTypes = array(
        self::TYPE_CHAR,
        self::TYPE_STRING,
    );

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

    protected function reset()
    {
        $this->table = '';
        $this->columns = array();
        $this->indexes = array();
        $this->lastColumn = null;
        $this->autoIncrement = '';
        $this->tableComment = '';
        $this->isChange = false;
    }

    /**
     * @param string $column
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addColumn($column, $type, array $options = array())
    {
        $this->lastColumn = $column;
        $this->columns[$column] = array('type' => $type) + $options;

        return $this;
    }

    /**
     * Add a drop column command
     *
     * @param string $column
     * @return $this
     */
    public function dropColumn($column)
    {
        $this->isChange = true;
        $this->columns[$column] = array('command' => 'drop');

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
        $table = $this->table;

        if ($this->hasTable($table)) {
            $this->isChange = true;
        }

        $columnSql = $this->getCreateDefinition();

        if ($this->isChange) {
            $sql = "ALTER TABLE $table" . $columnSql;
        } else {
            $sql = "CREATE TABLE $table ($columnSql)";
            $sql .= $this->getTableOptionSql();
        }

        return $sql;
    }

    /**
     * @return string
     */
    protected function getCreateDefinition()
    {
        $columnSqls = array();
        foreach ($this->columns as $column => $options) {
            $columnSqls[] .= '  ' . $this->getColumnSql($column, $options);
        }

        foreach ($this->indexes as $index => $options) {
            $columnSqls[] .= ' ' . $this->getIndexSql($index, $options);
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
        if ($options['type'] != 'index') {
            $sql .= strtoupper($options['type']) . ' ';
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

            return $this->$method($column, $options);
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
        $sql .= $this->getNullSql(isset($options['null']) ? $options['null'] : false);

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
        $dbColumns = $this->db->fetchAll("SHOW FULL COLUMNS FROM $this->table");
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

        $newOptions = array();
        $newOptions['type'] = $fromColumn['Type'];
        $newOptions['nullable'] = $fromColumn['Null'] === 'YES';
        $newOptions['comment'] = $fromColumn['Comment'];

        $keys = array_keys($this->typeDefaults, false, true);
        if (!in_array($newOptions['type'], $keys)) {
            $newOptions['default'] = $fromColumn['Default'];
        }

        $sql = $this->buildColumnSql($options['to'], $newOptions);
        if ($fromColumn['Extra'] === 'auto_increment') {
            $sql .= ' AUTO_INCREMENT';
        }

        return $this->getChangeColumnSql($column) . $sql;
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

        if ($this->autoUnsigned && in_array($options['type'], $this->unsignedTypes)) {
            return 'unsigned ';
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
        if ($default === false) {
            return '';
        }

        switch (true) {
            case $default === '':
                $value = "''";
                break;

            case in_array($options['type'], $this->stringTypes):
                $value = var_export($default, true);
                break;

            case $default === null:
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
     * Execute a drop table sql
     *
     * @param string $table
     * @param bool $ifExists
     * @return $this
     */
    public function drop($table, $ifExists = false)
    {
        $sql = 'DROP TABLE ';
        if ($ifExists) {
            $sql .= 'IF EXISTS ';
        }
        $sql .= $table;

        $this->db->executeUpdate($sql);

        return $this;
    }

    /**
     * Execute a drop table if exist sql
     *
     * @param string $table
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
        if (count($parts) == 1) {
            $db = $this->db->getDbname();
            $table = $parts[0];
        } else {
            list($db, $table) = $parts;
        }

        $tableExistsSql = $this->checkTableSqls[$this->db->getDriver()];

        return (bool) $this->db->fetchColumn($tableExistsSql, array($db, $table));
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
        return $this->addColumn($column, self::TYPE_CHAR, array('length' => $length));
    }

    /**
     * Add a decimal column
     *
     * @param string $column
     * @param int $length
     * @param int $scale
     * @return $this
     */
    public function decimal($column, $length, $scale = 2)
    {
        return $this->addColumn($column, self::TYPE_DECIMAL, array('length' => $length, 'scale' => $scale));
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
     * Add a string(varchar) column
     *
     * @param string $column
     * @param int $length
     * @return $this
     */
    public function string($column, $length = 255)
    {
        return $this->addColumn($column, self::TYPE_STRING, array('length' => $length));
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
        return $this->addColumn($column, self::TYPE_INT, array('length' => $length));
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
        return $this->addColumn($column, self::TYPE_TINY_INT, array('length' => $length));
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
        return $this->addColumn($column, self::TYPE_SMALL_INT, array('length' => $length));
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
     * @param string|array $columns
     * @param string $name
     * @param string $type
     * @return $this
     */
    protected function addIndex($columns, $name, $type)
    {
        $columns = (array) $columns;
        $name || $name = $this->generateIndexName($columns);

        $this->indexes[$name] = array(
            'columns' => $columns,
            'type' => $type,
        );

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
        $this->int($column)->unsigned()->autoIncrement();

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
        return $this->int('created_by')->int('updated_by');
    }

    /**
     * Add deleted_at and deleted_by columns to current table
     *
     * @return $this
     */
    public function softDeletable()
    {
        return $this->timestamp('deleted_at')->int('deleted_by');
    }

    /**
     * @deprecated
     */
    public function timestampsV1()
    {
        return $this->timestamp('createTime')->timestamp('updateTime');
    }

    /**
     * @deprecated
     */
    public function userstampsV1()
    {
        return $this->int('createUser')->int('updateUser');
    }

    /**
     * @deprecated
     */
    public function softDeletableV1()
    {
        return $this->timestamp('deleteTime')->int('deleteUser');
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
        $this->columns[$from] = array('command' => 'rename', 'from' => $from, 'to' => $to);

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
        $sql = sprintf('RENAME TABLE %s TO %s', $from, $to);
        $this->db->executeUpdate($sql);

        return $this;
    }
}
