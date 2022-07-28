<?php

namespace Wei;

use InvalidArgumentException;
use Wei\Db\QueryBuilderCacheTrait;
use Wei\Db\QueryBuilderTrait;
use Wei\Model\CastTrait;
use Wei\Model\CollTrait;
use Wei\Model\DefaultScopeTrait;
use Wei\Model\EventTrait;
use Wei\Model\RelationTrait;

/**
 * The main functions of the model, expected to be used with \Wei\BaseModel
 */
trait ModelTrait
{
    use CastTrait;
    use CollTrait;
    use DefaultScopeTrait;
    use EventTrait;
    use QueryBuilderCacheTrait;
    use QueryBuilderTrait {
        addQueryPart as private parentAddQueryPart;
        execute as private parentExecute;
        indexBy as private parentIndexBy;
    }
    use RelationTrait;
    use RetTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        // 1. Init service container
        $this->wei = $options['wei'] ?? Wei::getContainer();

        // 2. Set common and model config before set options
        $this->boot();
        $this->triggerModelEvent('init');

        // 3. Set options and add default value to model attributes
        parent::__construct($options);
        if (!$this->coll) {
            $this->attributes += $this->getColumnValues('default');
        }

        // 4. Clear changed status after set attributes
        $this->resetChanges();
    }

    /**
     * Create a new model object
     *
     * @param array $attributes
     * @param array $options
     * @return $this
     */
    public static function new($attributes = [], array $options = []): self
    {
        /** @var class-string<$this> $class */
        $class = static::getServiceClass();
        return new $class($options + ['attributes' => $attributes]);
    }

    /**
     * Returns the record and relative records data as JSON string
     *
     * @param array $returnFields A indexed array specified the fields to return
     * @return string
     */
    public function toJson(array $returnFields = []): string
    {
        return json_encode($this->toArray($returnFields));
    }

    /**
     * Get guarded columns
     *
     * @return string[]
     */
    public function getGuarded(): array
    {
        return $this->guarded;
    }

    /**
     * Set guarded columns
     *
     * @param array $guarded
     * @return $this
     */
    public function setGuarded(array $guarded): self
    {
        $this->guarded = $guarded;
        return $this;
    }

    /**
     * Get fillable columns
     *
     * @return string[]
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }

    /**
     * Set fillable columns
     *
     * @param array $fillable
     * @return $this
     */
    public function setFillable(array $fillable): self
    {
        $this->fillable = $fillable;
        return $this;
    }

    /**
     * Check if the field is assignable through fromArray method
     *
     * @param string $column
     * @return bool
     */
    public function isFillable(string $column): bool
    {
        $fillable = $this->getFillable();
        return !in_array($column, $this->getGuarded(), true) && !$fillable || in_array($column, $fillable, true);
    }

    /**
     * Set each attribute value, without checking whether the column is fillable
     *
     * @param iterable $attributes
     * @return $this
     */
    public function setAttributes(iterable $attributes): self
    {
        // Replace all attributes of the collection
        if ($this->coll) {
            $this->attributes = [];
        }
        foreach ($attributes as $column => $value) {
            $this->set($column, $value);
        }
        return $this;
    }

    /**
     * Reload the record data from database
     *
     * @return $this
     */
    public function reload(): self
    {
        $primaryKey = $this->getPrimaryKey();
        $this->setAttributesFromDb($this->executeSelect([$primaryKey => $this->get($primaryKey)]));
        $this->resetChanges();
        return $this;
    }

    /**
     * Receives the model column value
     *
     * @param string|int $name
     * @param bool|null $exists
     * @param bool $throwException
     * @return mixed
     * @throws InvalidArgumentException When column not found
     */
    public function &get($name, bool &$exists = null, bool $throwException = true)
    {
        $exists = true;

        // Receive collection value
        if ($this->coll && $this->hasColl($name)) {
            return $this->getCollValue($name);
        }

        // Receive column value
        if ($this->hasColumn($name)) {
            return $this->getColumnValue($name);
        }

        // Receive virtual column value
        if ($this->hasVirtual($name)) {
            return $this->getVirtualValue($name);
        }

        // Receive relation
        $result = $this->getRelationValue($name, $exists, $throwException);
        if ($exists) {
            return $result;
        }

        $exists = false;
        if ($throwException) {
            throw new InvalidArgumentException('Invalid property: ' . $name);
        } else {
            $null = null;
            return $null;
        }
    }

    /**
     * Remove the attribute value by name
     *
     * @param string|int $name The name of field
     * @return $this
     */
    public function remove($name): self
    {
        return $this->unsetAttribute($name);
    }

    /**
     * Increment a field
     *
     * @param string $name
     * @param int|float|string $offset
     * @return $this
     */
    public function incr(string $name, $offset = 1): self
    {
        $this[$name] = $this->db->raw($this->convertToDbKey($name) . ' + ' . $offset);
        return $this;
    }

    /**
     * Decrement a field
     *
     * @param string $name
     * @param int|float|string $offset
     * @return $this
     */
    public function decr(string $name, $offset = 1): self
    {
        $this[$name] = $this->db->raw($this->convertToDbKey($name) . ' - ' . $offset);
        return $this;
    }

    /**
     * Check if it's a new record and has not save to database
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    public function boot(): void
    {
        $class = static::class;

        if (isset(static::$booted[$class])) {
            return;
        }
        static::$booted[$class] = true;

        $cls = $this->wei->cls;
        foreach ($cls->usesDeep($this) as $trait) {
            $method = 'boot' . $cls->baseName($trait);
            if (method_exists($class, $method)) {
                $this->{$method}($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get hidden columns
     *
     * @return string[]
     */
    public function getHidden(): array
    {
        return $this->hidden;
    }

    /**
     * Set hidden columns
     *
     * @param string|array $hidden
     * @return $this
     */
    public function setHidden($hidden): self
    {
        $this->hidden = (array) $hidden;

        return $this;
    }

    /**
     * Check if the model's attributes or the specified column is changed
     *
     * @param string|null $column
     * @return bool
     */
    public function isChanged(string $column = null): bool
    {
        $this->removeUnchanged($column);

        if ($column) {
            return array_key_exists($column, $this->changes);
        }
        return (bool) $this->changes;
    }

    /**
     * Return the column that has been changed
     *
     * @param string|null $column
     * @return array|string|null
     */
    public function getChanges(string $column = null)
    {
        $this->removeUnchanged($column);

        if ($column) {
            return $this->changes[$column] ?? null;
        }
        return $this->changes;
    }

    /**
     * @param string $name
     * @param int|float|string $offset
     * @phpstan-param int|float|numeric-string $offset
     * @return $this
     */
    public function incrSave(string $name, $offset = 1): self
    {
        $value = $this->get($name) + $offset;
        $this->incr($name, $offset)->save();
        $this->set($name, $value);
        $this->resetChanges($name);

        return $this;
    }

    /**
     * @param string $name
     * @param int|float|string $offset
     * @phpstan-param int|float|numeric-string $offset
     * @return $this
     */
    public function decrSave(string $name, $offset = 1): self
    {
        return $this->incrSave($name, -$offset);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function existOrFail(): self
    {
        if ($this->new) {
            throw new \Exception('Record not found', 404);
        }
        return $this;
    }

    /**
     * Returns whether the model was inserted in the this request
     *
     * @return bool
     */
    public function wasRecentlyCreated(): bool
    {
        return $this->wasRecentlyCreated;
    }

    /**
     * Sets the primary key column
     *
     * @param string $primaryKey
     * @return $this
     */
    public function setPrimaryKey(string $primaryKey): self
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * Returns the primary key column
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Check if the offset exists
     *
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->issetAttribute($offset);
    }

    /**
     * Get the offset value
     *
     * @param string|int $offset
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set the offset value
     *
     * @param string|int|null $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Unset the offset
     *
     * @param string|int $offset
     */
    public function offsetUnset($offset)
    {
        $this->unsetAttribute($offset);
    }

    /**
     * @param string|int $name
     * @return Base|$this
     * @throws \Exception
     */
    public function &__get($name)
    {
        $value = &$this->get($name, $exists, false);
        if ($exists) {
            return $value;
        }

        // Receive other services
        return $this->getServiceValue($name);
    }

    /**
     * @param string|int|null $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value = null)
    {
        $result = $this->set($name, $value, false);
        if ($result) {
            return;
        }

        if ($this->wei->has($name)) {
            return $this->{$name} = $value;
        }

        throw new InvalidArgumentException('Invalid property: ' . $name);
    }

    /**
     * Check if property exists
     *
     * @param string|int $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->issetAttribute($name);
    }

    /**
     * Remove the attribute value by name
     *
     * @param string|int $name The name of field
     */
    public function __unset($name): void
    {
        $this->unsetAttribute($name);
    }

    /**
     * Set each attribute value, without checking whether the column is fillable, and save the model
     *
     * @param iterable $attributes
     * @return $this
     * @svc
     */
    protected function saveAttributes(iterable $attributes = []): self
    {
        $attributes && $this->setAttributes($attributes);
        return $this->save();
    }

    /**
     * Returns the record data as array
     *
     * @param array|callable $returnFields A indexed array specified the fields to return
     * @param callable|null $prepend
     * @return array
     * @svc
     */
    protected function toArray($returnFields = [], callable $prepend = null): array
    {
        if ($this->coll) {
            return $this->mapColl(__FUNCTION__, func_get_args());
        }

        if (is_callable($returnFields)) {
            $prepend = $returnFields;
            $returnFields = [];
        }

        $data = [];
        $columns = $this->getToArrayColumns($returnFields ?: $this->getColumnNames());
        foreach ($columns as $column) {
            $data[$column] = $this->get($column);
        }

        if ($prepend) {
            $data = $prepend($this) + $data;
        }

        if (!$returnFields) {
            $data += $this->virtualToArray() + $this->relationToArray();
        }

        return $data;
    }

    /**
     * Returns the success result with model data
     *
     * @param mixed $merge
     * @return Ret
     * @svc
     */
    protected function toRet($merge = []): Ret
    {
        if ($this->coll) {
            return $this->collToRet($merge);
        } else {
            return $this->suc($merge + ['data' => $this])->setMetadata('model', $this);
        }
    }

    /**
     * Return the record table name
     *
     * @return string
     * @svc
     */
    protected function getTable(): string
    {
        if (!isset($this->table)) {
            $str = $this->wei->str;
            $this->table = $str->pluralize($str->snake($this->getModelBaseName()));
        }
        return $this->table;
    }

    /**
     * Return the class base name without "Model" suffix of the class
     *
     * @return string
     * @internal For model use only
     */
    public function getModelBaseName(): string
    {
        $name = Cls::baseName(static::class);
        if ('Model' !== $name && 'Model' === substr($name, -5)) {
            $name = substr($name, 0, -5);
        }
        return $name;
    }

    /**
     * Return the unique name that identifies the model service
     *
     * @return string
     * @todo throw exception when found duplicate names
     */
    protected function getModelUniqueName(): string
    {
        $name = Cls::baseName(static::class);
        if ('Model' !== $name && 'Model' !== substr($name, -5)) {
            $name .= 'Model';
        }
        return $name;
    }

    /**
     * Import a PHP array in this record
     *
     * @param iterable $array
     * @return $this
     * @svc
     */
    protected function fromArray(iterable $array): self
    {
        if ($this->coll) {
            return $this->setAttributes($array);
        }

        foreach ($array as $name => $value) {
            if (!$this->isFillable($name)) {
                continue;
            }

            if ($this->hasColumn($name)) {
                $this->setColumnValue($name, $value);
                continue;
            }

            if ($this->hasVirtual($name)) {
                $this->setVirtualValue($name, $value);
            }
        }
        return $this;
    }

    /**
     * Save the record or data to database
     *
     * @param iterable $attributes
     * @return $this
     * @svc
     */
    protected function save(iterable $attributes = []): self
    {
        // 1. Merges attributes from parameters
        $attributes && $this->fromArray($attributes);

        // 2.1 Loop and save collection records
        if ($this->coll) {
            $this->mapColl(__FUNCTION__);
            return $this;
        }

        // 2.2 Saves single record
        $isNew = $this->new;
        $primaryKey = $this->getPrimaryKey();

        $this->setColumnStamps();

        // 2.2.2 Triggers before callbacks
        $this->triggerModelEventWithMethod('beforeSave');
        $this->triggerModelEventWithMethod($isNew ? 'beforeCreate' : 'beforeUpdate');

        if ($isNew) {
            $attributes = $this->convertToDbAttributes();

            // 2.2.3.1 Inserts new record
            // Removes primary key value when it's empty to avoid SQL error
            if (array_key_exists($primaryKey, $attributes) && !$attributes[$primaryKey]) {
                unset($attributes[$primaryKey]);
            }

            $this->executeInsert($attributes);
            $this->new = false;
            $this->wasRecentlyCreated = true;

            // Receives primary key value when it's empty
            if (!isset($attributes[$primaryKey]) || !$attributes[$primaryKey]) {
                // Prepare sequence name for PostgreSQL
                $sequence = sprintf('%s_%s_seq', $this->getDb()->getTable($this->getTable()), $primaryKey);
                $this->setAttributeFromDb($primaryKey, $this->getDb()->lastInsertId($sequence));
            }
        } else {
            // 2.2.3.2 Updates existing record
            if ($attributes = $this->getUpdateAttributes()) {
                $this->executeUpdate($attributes, [$primaryKey => $this->getColumnValue($primaryKey)]);
            }
        }

        // 2.2.4 Reset changed attributes
        $this->resetChanges();

        // 2.2.5. Triggers after callbacks
        $this->triggerModelEventWithMethod($isNew ? 'afterCreate' : 'afterUpdate');
        $this->triggerModelEventWithMethod('afterSave');

        return $this;
    }

    /**
     * Delete the current record and trigger the beforeDestroy and afterDestroy callback
     *
     * @param int|string $id
     * @return $this
     * @svc
     */
    protected function destroy($id = null): self
    {
        $id && $this->find($id);

        if ($this->coll) {
            $this->mapColl(__FUNCTION__);
            return $this;
        }

        $this->triggerModelEventWithMethod('beforeDestroy');
        $result = $this->triggerModelEvent('destroy');
        if (false !== $result) {
            $this->executeDestroy();
        }
        $this->triggerModelEventWithMethod('afterDestroy');

        return $this;
    }

    /**
     * Find a record by primary key, or throws 404 exception if record not found, then destroy the record
     *
     * @param string|int $id
     * @return $this
     * @throws \Exception when record not found
     * @svc
     */
    protected function destroyOrFail($id): self
    {
        return $this->findOrFail($id)->destroy();
    }

    protected function executeDestroy()
    {
        $primaryKey = $this->getPrimaryKey();
        $this->executeDelete([$primaryKey => $this->getColumnValue($primaryKey)]);
        $this->new = true;
    }

    /**
     * Set the record field value
     *
     * @param string|int|null $name
     * @param mixed $value
     * @param bool $throwException
     * @return $this|false
     * @svc
     */
    protected function set($name, $value, bool $throwException = true)
    {
        if ($this->coll) {
            return $this->setCollValue($name, $value);
        }

        if ($this->hasColumn($name)) {
            return $this->setColumnValue($name, $value);
        }

        if ($this->hasVirtual($name)) {
            return $this->setVirtualValue($name, $value);
        }

        if ($throwException) {
            throw new InvalidArgumentException('Invalid property: ' . (null === $name ? '[null]' : $name));
        } else {
            return false;
        }
    }

    /**
     * Executes the generated SQL and returns the found record object or false
     *
     * @param int|string|array|null $id
     * @return $this|null
     * @svc
     */
    protected function find($id): ?self
    {
        return $this->findBy($this->getPrimaryKey(), $id);
    }

    /**
     * Find a record by primary key, or throws 404 exception if record not found
     *
     * @param int|string $id
     * @return $this
     * @throws \Exception
     * @svc
     */
    protected function findOrFail($id): self
    {
        if ($this->find($id)) {
            return $this;
        } else {
            throw new \Exception('Record not found', 404);
        }
    }

    /**
     * Find a record by primary key, or init with the specified attributes if record not found
     *
     * @param int|string $id
     * @param array|object $attributes
     * @return $this
     * @svc
     */
    protected function findOrInit($id = null, $attributes = []): self
    {
        return $this->findOrInitBy([$this->getPrimaryKey() => $id], $attributes);
    }

    /**
     * Find a record by primary key, or save with the specified attributes if record not found
     *
     * @param int|string $id
     * @param array $attributes
     * @return $this
     * @svc
     */
    protected function findOrCreate($id, $attributes = []): self
    {
        $this->findOrInit($id, $attributes);
        if ($this->isNew()) {
            $this->save();
        }
        return $this;
    }

    /**
     * @param array $attributes
     * @param array|object $data
     * @return $this
     * @svc
     */
    protected function findByOrCreate($attributes, $data = []): self
    {
        $this->findOrInitBy($attributes, $data);
        if ($this->isChanged()) {
            $this->save();
        }
        return $this;
    }

    /**
     * Executes the generated SQL and returns the found record collection object or false
     *
     * @param array $ids
     * @return $this|$this[]
     * @phpstan-return $this
     * @svc
     */
    protected function findAll(array $ids): self
    {
        return $this->findAllBy($this->getPrimaryKey(), 'IN', $ids);
    }

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this|null
     * @svc
     */
    protected function findBy($column, $operator = null, $value = null): ?self
    {
        $this->coll = false;
        $data = $this->fetch(...func_get_args());
        if ($data) {
            $this->new = false;
            $this->setAttributesFromDb($data, true);
            $this->triggerModelEventWithMethod('afterFind');
            return $this;
        } else {
            return null;
        }
    }

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this|$this[]
     * @phpstan-return $this
     * @svc
     */
    protected function findAllBy($column, $operator = null, $value = null): self
    {
        $this->coll = true;
        $data = $this->fetchAll(...func_get_args());

        $records = [];
        foreach ($data as $key => $row) {
            $records[$key] = static::new([], [
                'wei' => $this->wei,
                'db' => $this->getDb(),
                'table' => $this->getTable(),
                'new' => false,
            ])->setAttributesFromDb($row, true);
            $records[$key]->triggerModelEventWithMethod('afterFind');
        }

        $this->attributes = $records;
        return $this;
    }

    /**
     * @param array $attributes
     * @param array|object $data
     * @return $this
     * @svc
     */
    protected function findOrInitBy(array $attributes = [], $data = []): self
    {
        if (!$this->findBy($attributes)) {
            // Convert to object to array
            if (is_object($data) && method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }

            $this->setAttributes($attributes);
            $this->fromArray($data);
        }
        return $this;
    }

    /**
     * Find a record by primary key value and throws 404 exception if record not found
     *
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     * @throws \Exception
     * @svc
     */
    protected function findByOrFail($column, $operator = null, $value = null): self
    {
        if ($this->findBy(...func_get_args())) {
            return $this;
        } else {
            throw new \Exception('Record not found', 404);
        }
    }

    /**
     * @param Req|null $req
     * @return $this
     * @throws \Exception
     * @svc
     */
    protected function findFromReq(Req $req = null): self
    {
        $req || $req = $this->wei->req;
        if (!$req->isPost()) {
            $this->findOrFail($req[$this->getPrimaryKey()]);
        }
        return $this;
    }

    /**
     * Executes the generated SQL and returns the found record object or null if not found
     *
     * @return $this|null
     * @svc
     */
    protected function first(): ?self
    {
        return $this->findBy(null);
    }

    /**
     * @return $this|$this[]
     * @phpstan-return $this
     * @svc
     */
    protected function all(): self
    {
        return $this->findAllBy(null);
    }

    /**
     * Coll: Specifies a field to be the key of the fetched array
     *
     * @param string $column
     * @return $this
     * @svc
     */
    protected function indexBy(string $column): self
    {
        // Expect to work with coll
        if (!$this->coll) {
            $this->beColl();
        }

        $this->parentIndexBy($column);
        $this->attributes = $this->executeIndexBy($this->attributes, $column);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute()
    {
        $this->applyDefaultScope();
        return $this->parentExecute();
    }

    /**
     * {@inheritDoc}
     */
    protected function addQueryPart(string $name, $value, bool $append = false)
    {
        $this->applyDefaultScopeBeforeAddQueryPart($name);
        return $this->parentAddQueryPart($name, $value, $append);
    }

    /**
     * Remove all change values  or the specified column change value
     *
     * @param string|null $column
     */
    protected function resetChanges(string $column = null): void
    {
        if ($column) {
            unset($this->changes[$column]);
        }
        $this->changes = [];
    }

    /**
     * Record the value of column before change
     *
     * @param string $column
     */
    protected function setChange(string $column): void
    {
        // Only record the init value of column
        // If column value change back to init value, it will be removed by the `removeUnchanged` method
        if (!array_key_exists($column, $this->changes)) {
            $this->changes[$column] = $this->getColumnValue($column);
        }
    }

    /**
     * Remove unchanged values
     *
     * ```php
     * $model = static::new(['column' => 'a']); // init column value to "a"
     * $model->column = 'b'; // $model->changes become ['column' => 'a']
     * $model->column = 'a'; // $model->changes still be ['column' => 'a']
     * $model->removeUnchanged(); // $model->changes become []
     * ```
     *
     * @param string|null $column
     * @return bool
     */
    protected function removeUnchanged(string $column = null): bool
    {
        if ($column) {
            if (!isset($this->changes[$column])) {
                return false;
            }

            $value = $this->getColumnValue($column);
            $original = $this->changes[$column];

            // If the value is an object, compare whether they have the same attributes and values,
            // and are instances of the same class.
            // @link https://www.php.net/manual/en/language.oop5.object-comparison.php
            if ($original === $value || (is_object($original) && $original == $value)) {
                unset($this->changes[$column]);
                return true;
            }
            return false;
        }

        $result = false;
        foreach ($this->changes as $column => $value) {
            if ($this->removeUnchanged($column)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * @param array $columns
     * @return array
     */
    protected function getToArrayColumns(array $columns): array
    {
        if ($hidden = $this->getHidden()) {
            $columns = array_diff($columns, $hidden);
        }

        return $columns;
    }

    protected function virtualToArray(): array
    {
        $data = [];
        $str = $this->wei->str;
        foreach ($this->virtual as $column) {
            $data[$column] = $this->{'get' . $str->camel($column) . 'Attribute'}();
        }

        return $data;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setColumnValue(string $name, $value): self
    {
        $this->setChange($name);
        return $this->setAttributeFromUser($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function &getColumnValue(string $name)
    {
        return $this->convertToPhpAttribute($name);
    }

    /**
     * @param string $name
     * @param int $source
     * @param bool $replace
     */
    protected function setAttributeSource(string $name, int $source, bool $replace = false): void
    {
        if ($replace) {
            $this->attributeSources = [$name => $source];
        } else {
            $this->attributeSources[$name] = $source;
        }
    }

    /**
     * Returns the attribute source of specified column name
     *
     * @param string $name
     * @return int
     */
    protected function getAttributeSource(string $name): int
    {
        return $this->attributeSources[$name] ?? $this->attributeSources['*'];
    }

    /**
     * Returns the service object
     *
     * @param string $name
     * @return Base
     * @throws \Exception
     */
    protected function &getServiceValue(string $name): Base
    {
        parent::__get($name);

        return $this->{$name};
    }

    /**
     * Returns the virtual column value
     *
     * @param string $name
     * @return mixed
     */
    protected function &getVirtualValue(string $name)
    {
        $result = $this->callGetter($name, $value);
        if ($result) {
            return $value;
        }

        throw new InvalidArgumentException('Invalid virtual column: ' . $name);
    }

    /**
     * Sets the virtual column value
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setVirtualValue(string $name, $value): self
    {
        $result = $this->callSetter($name, $value);
        if (!$result) {
            throw new InvalidArgumentException('Invalid virtual column: ' . $name);
        }

        return $this;
    }

    /**
     * Check if the name is virtual column
     *
     * @param mixed $name
     * @return bool
     */
    protected function hasVirtual($name): bool
    {
        return in_array($name, $this->virtual, true);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    protected function callGetter(string $name, &$value): bool
    {
        $method = 'get' . Str::camel($name) . 'Attribute';
        if ($result = method_exists($this, $method)) {
            $value = $this->{$method}();
        }
        return $result;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    protected function callSetter(string $name, $value): bool
    {
        $method = 'set' . Str::camel($name) . 'Attribute';
        if ($result = method_exists($this, $method)) {
            $this->{$method}($value);
        }
        return $result;
    }

    /**
     * Set the timestamp and user stamp columns' values
     *
     * @return $this
     */
    protected function setColumnStamps(): self
    {
        if ($this->hasColumn($this->updatedAtColumn)) {
            $this->setColumnValue($this->updatedAtColumn, date('Y-m-d H:i:s'));
        }

        if ($this->hasColumn($this->updatedByColumn)) {
            $this->setColumnValue($this->updatedByColumn, $this->wei->user->id);
        }

        if ($this->new) {
            if ($this->hasColumn($this->createdAtColumn) && !$this->getColumnValue($this->createdAtColumn)) {
                $this->setColumnValue($this->createdAtColumn, date('Y-m-d H:i:s'));
            }

            if ($this->hasColumn($this->createdByColumn) && !$this->getColumnValue($this->createdByColumn)) {
                $this->setColumnValue($this->createdByColumn, $this->wei->user->id);
            }
        }

        return $this;
    }

    /**
     * Set the value of the specified attribute, the value is received from the user
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setAttributeFromUser(string $name, $value): self
    {
        $result = $this->callSetter($name, $value);
        if ($result) {
            $this->setAttributeSource($name, static::ATTRIBUTE_SOURCE_DB);
            return $this;
        }

        $this->attributes[$name] = $value;
        $this->setAttributeSource($name, static::ATTRIBUTE_SOURCE_USER);
        return $this;
    }

    /**
     * Set all attribute values, the values are received from the database
     *
     * @param array $attributes
     * @param bool $merge
     * @return $this
     * @internal For model use only
     */
    public function setAttributesFromDb(array $attributes, bool $merge = false): self
    {
        $this->attributes = array_merge($merge ? $this->attributes : [], $attributes);
        $this->setAttributeSource('*', static::ATTRIBUTE_SOURCE_DB, true);
        return $this;
    }

    /**
     * Set the value of the specified attribute, the value is received from the database
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setAttributeFromDb(string $name, $value): self
    {
        $this->attributes[$name] = $value;
        $this->setAttributeSource($name, static::ATTRIBUTE_SOURCE_DB);
        return $this;
    }

    /**
     * Convert the specified attribute to a PHP value
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    protected function &convertToPhpAttribute(string $name)
    {
        $result = $this->callGetter($name, $value);
        if ($result) {
            return $value;
        }

        if (static::ATTRIBUTE_SOURCE_PHP === $this->getAttributeSource($name)) {
            return $this->attributes[$name];
        }

        // Data flow: user => db => php

        // Convert user data to db data
        $value = $this->convertToDbAttribute($name);

        // Then convert db data to php data
        $this->attributes[$name] = $this->castColumnToPhp($value, $name);
        $this->setAttributeSource($name, static::ATTRIBUTE_SOURCE_PHP);

        return $this->attributes[$name];
    }

    /**
     * Convert all attributes to database values
     *
     * @return array
     */
    protected function convertToDbAttributes(): array
    {
        foreach ($this->attributes as $name => $value) {
            $this->convertToDbAttribute($name);
        }
        return $this->attributes;
    }

    /**
     * Convert the specified attribute to a database value
     *
     * @param string $column
     * @return mixed
     */
    protected function convertToDbAttribute(string $column)
    {
        $value = $this->attributes[$column] ?? null;

        if (static::ATTRIBUTE_SOURCE_DB === $this->getAttributeSource($column)) {
            return $value;
        }

        // Convert to db value by setter
        $result = $this->callSetter($column, $value);
        if ($result) {
            $this->setAttributeSource($column, static::ATTRIBUTE_SOURCE_DB);
            return $this->attributes[$column];
        }

        // Convert to db value by caster
        $this->setAttributeFromDb($column, $this->castColumnToDb($value, $column));
        return $this->attributes[$column];
    }

    /**
     * Delete the specified attribute
     *
     * @param string|int $name
     * @return $this
     */
    protected function unsetAttribute($name): self
    {
        unset($this->attributes[$name]);
        $this->setAttributeSource($name, static::ATTRIBUTE_SOURCE_USER);
        return $this;
    }

    /**
     * Check if the specified attribute is set and is not NULL
     *
     * @param string|int $name
     * @return bool
     */
    protected function issetAttribute($name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Return the attribute values that should be update to database
     *
     * @return array
     */
    private function getUpdateAttributes(): array
    {
        $attributes = [];
        foreach ($this->changes as $column => $value) {
            // `removeUnchanged` will call `getColumnValue`, which may convert the USER value to a DB value,
            // and then convert the DB value to a PHP value, so here we call `convertToDbValue` in advance
            // to avoid converting the value to a DB value twice
            $attributes[$column] = $this->convertToDbAttribute($column);
            if ($this->removeUnchanged($column)) {
                unset($attributes[$column]);
            }
        }
        return $attributes;
    }

    /**
     * 获取当前类的服务名称对应的类
     *
     * @return string
     */
    private static function getServiceClass(): string
    {
        $wei = wei();
        return $wei->has($wei->getServiceName(static::class)) ?: static::class;
    }

    /**
     * @param array $conditions
     * @return int
     * @internal
     */
    private function executeDelete(array $conditions): int
    {
        return $this->getDb()->delete($this->getTable(), $this->convertKeysToDbKeys($conditions));
    }

    /**
     * @param array $conditions
     * @return array
     * @internal
     */
    private function executeSelect(array $conditions): array
    {
        return $this->convertKeysToPhpKeys(
            $this->getDb()->select($this->getTable(), $this->convertKeysToDbKeys($conditions)) ?: []
        );
    }

    /**
     * @param array $data
     * @return int
     * @internal
     */
    private function executeInsert(array $data): int
    {
        return $this->getDb()->insert($this->getTable(), $this->convertKeysToDbKeys($data));
    }

    /**
     * @param array $data
     * @param array $conditions
     * @return int
     * @internal
     */
    private function executeUpdate(array $data, array $conditions): int
    {
        return $this->getDb()->update(
            $this->getTable(),
            $this->convertKeysToDbKeys($data),
            $this->convertKeysToDbKeys($conditions)
        );
    }
}
