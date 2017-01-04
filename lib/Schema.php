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
    protected $charset = 'utf8mb4';

    /**
     * @var string
     */
    protected $collate = 'utf8mb4_unicode_ci';

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
        'unique' => false,
        'comment' => '',
        'unsigned' => false,
        'change' => false,
    ];

    protected $typeDefaults = [
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
        ],
    ];

    /**
     * @var array
     */
    protected $unsignedTypes = [
        self::TYPE_BIG_INT,
        self::TYPE_BOOL,
        self::TYPE_DOUBLE,
        self::TYPE_INT,
        self::TYPE_TINY_INT,
        self::TYPE_SMALL_INT,
    ];

    /**
     * @var array
     */
    protected $stringTypes = [
        self::TYPE_CHAR,
        self::TYPE_STRING,
    ];

    public function table($table)
    {
        $this->reset();
        $this->table = $table;

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

    public function dropColumn($column)
    {
        $this->columns[$column] = ['drop' => true];

        return $this;
    }

    public function after($column)
    {
        return $this->updateLastColumn('after', $column);
    }

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

    protected function getCreateDefinition()
    {
        $columnSqls = [];
        foreach ($this->columns as $column => $options) {
            $columnSqls[] .= '  ' . $this->getColumnSql($column, $options);
        }

        foreach ($this->indexes as $index => $options) {
            $columnSqls[] .= ' ' . $this->getIndexSql($index, $options);
        }

        $sql = "\n" . implode(",\n", $columnSqls) . "\n";

        return $sql;
    }

    protected function getIndexSql($index, $options)
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

    protected function getColumnSql($column, array $options)
    {
        $sql = '';

        if (isset($options['drop'])) {
            $sql .= 'DROP COLUMN ' . $column;

            return $sql;
        }

        if ($this->isChange) {
            if ($options['change']) {
                $sql .= 'CHANGE COLUMN ' . $column . ' ';
            } else {
                $sql .= 'ADD COLUMN ';
            }
        }

        $sql .= $column . ' ' . $this->getTypeSql($options) . ' ';
        $sql .= $this->getUnsignedSql($options);
        $sql .= $this->getNullSql($options['null']);

        // Auto increment do not have default value
        if ($this->autoIncrement == $column) {
            $sql .= ' AUTO_INCREMENT';
        } else {
            $defaultSql = $this->getDefaultSql($options);
            if ($defaultSql) {
                $sql .= ' ' . $defaultSql;
            }
        }

        if ($options['comment']) {
            $sql .= " COMMENT '" . $options['comment'] . "'";
        }

        if (isset($options['after'])) {
            $sql .= ' AFTER ' . $options['after'];
        }

        return $sql;
    }

    protected function getTypeSql($options)
    {
        $driver = $this->db->getDriver();
        $sql = $this->typeMaps[$driver][$options['type']];

        if ($options['length']) {
            if ($options['scale']) {
                $sql .= '(' . $options['length'] . ', ' . $options['scale'] . ')';
            } else {
                $sql .= '(' . $options['length'] . ')';
            }
        }

        return $sql;
    }

    protected function getUnsignedSql($options)
    {
        if (isset($options['unsigned'])) {
            return $options['unsigned'] ? 'unsigned ' : '';
        }

        if ($this->autoUnsigned && in_array($options['type'], $this->unsignedTypes)) {
            return 'unsigned ';
        }

        return '';
    }

    protected function getNullSql($null)
    {
        return $null ? 'NULL' : 'NOT NULL';
    }

    protected function getDefaultSql($options)
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
     * @param string $table
     * @return $this
     */
    public function dropIfExists($table)
    {
        return $this->drop($table, true);
    }

    public function hasTable($table)
    {
        try {
            return (bool) $this->db->getTableFields($table);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * @return $this
     */
    public function exec()
    {
        $this->db->executeUpdate($this->getSql());

        return $this;
    }

    public function bigInt($column)
    {
        return $this->addColumn($column, self::TYPE_BIG_INT);
    }

    public function bool($column)
    {
        return $this->addColumn($column, self::TYPE_BOOL);
    }

    public function char($column, $length = 255)
    {
        return $this->addColumn($column, self::TYPE_CHAR, ['length' => $length]);
    }

    public function decimal($column, $length, $scale = 2)
    {
        return $this->addColumn($column, self::TYPE_DECIMAL, ['length' => $length, 'scale' => $scale]);
    }

    public function double($column)
    {
        return $this->addColumn($column, self::TYPE_DOUBLE);
    }

    public function string($column, $length = 255)
    {
        return $this->addColumn($column, self::TYPE_STRING, ['length' => $length]);
    }

    public function int($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_INT, ['length' => $length]);
    }

    public function longText($column)
    {
        return $this->addColumn($column, self::TYPE_LONG_TEXT);
    }

    public function mediumInt($column)
    {
        return $this->addColumn($column, self::TYPE_MEDIUM_INT);
    }

    public function mediumText($column)
    {
        return $this->addColumn($column, self::TYPE_MEDIUM_TEXT);
    }

    public function tinyInt($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_TINY_INT, ['length' => $length]);
    }

    public function smallInt($column, $length = null)
    {
        return $this->addColumn($column, self::TYPE_SMALL_INT, ['length' => $length]);
    }

    public function text($column)
    {
        return $this->addColumn($column, self::TYPE_TEXT);
    }

    public function date($column)
    {
        return $this->addColumn($column, self::TYPE_DATE);
    }

    public function datetime($column)
    {
        return $this->addColumn($column, self::TYPE_DATETIME);
    }

    public function timestamp($column)
    {
        return $this->addColumn($column, self::TYPE_TIMESTAMP);
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function comment($comment)
    {
        return $this->updateLastColumn('comment', $comment);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function defaults($value)
    {
        return $this->updateLastColumn('default', $value);
    }

    /**
     * @param bool $nullable
     * @return $this
     */
    public function nullable($nullable = true)
    {
        return $this->updateLastColumn('nullable', $nullable);
    }

    /**
     * @param bool $unsigned
     * @return $this
     */
    public function unsigned($unsigned = true)
    {
        return $this->updateLastColumn('unsigned', $unsigned);
    }

    /**
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function unique($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

    /**
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function primary($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

    /**
     * @param string|array $columns
     * @param string|null $name
     * @return $this
     */
    public function index($columns, $name = null)
    {
        return $this->addIndex($columns, $name, __FUNCTION__);
    }

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

    protected function generateIndexName($columns)
    {
        return implode('_', $columns);
    }

    /**
     * @return $this
     */
    public function autoIncrement()
    {
        $this->autoIncrement = $this->lastColumn;

        return $this;
    }

    /**
     * Helper
     *
     * @param string $column
     * @return $this
     */
    public function id($column = 'id')
    {
        $this->int($column)->unsigned()->autoIncrement();

        return $this->primary($column);
    }

    public function timestampsV2()
    {
        return $this->timestamp('created_at')->timestamp('updated_at');
    }

    public function userstampsV2()
    {
        return $this->int('created_by')->int('updated_by');
    }

    public function softDeletableV2()
    {
        return $this->timestamp('deleted_at')->int('deleted_by');
    }

    public function timestamps()
    {
        return $this->timestamp('createTime')->timestamp('updateTime');
    }

    public function userstamps()
    {
        return $this->int('createUser')->int('updateUser');
    }

    public function softDeletable()
    {
        return $this->timestamp('deleteTime')->int('deleteUser');
    }

    public function tableComment($comment)
    {
        $this->tableComment = $comment;

        return $this;
    }
}
