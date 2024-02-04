<?php

namespace Wei\Model;

use Wei\BaseModel;
use Wei\Cls;
use Wei\ModelTrait;
use Wei\Str;

/**
 * Add relation functions to the model
 *
 * @internal Expected to be used only by ModelTrait
 */
trait RelationTrait
{
    /**
     * The relation config
     *
     * @var array
     * @internal
     */
    protected $relation = [];

    /**
     * The loaded relation values
     *
     * @var array
     */
    protected $relationValues = [];

    /**
     * The parameter values for the relation base query
     *
     * @var mixed
     */
    protected $relationParams;

    /**
     * The relations that the current object has loaded
     *
     * @var array
     * @deprecated use $relationValues
     */
    protected $loadedRelations = [];

    /**
     * The relations that have been joined
     *
     * @var array
     * @internal
     */
    protected $joinRelations = [];

    /**
     * Save with relation data
     *
     * Use with relation calls like
     *
     * ```php
     * $user->profile()->saveRelation([]);
     * $user->emails()->saveRelation([[], []]);
     * ```
     *
     * @param array $attributes
     * @return $this
     * @experimental
     */
    public function saveRelation(array $attributes = []): self
    {
        $relationAttributes = [$this->relation['foreignKey'] => $this->relation['localValue']];

        if ($this->coll) {
            $this->all();
            $this->saveColl($attributes, $relationAttributes);
        } else {
            $this->findOrInitBy()->fromArray($attributes)->save($relationAttributes);
        }
        return $this;
    }

    /**
     * @template T of BaseModel
     * @param BaseModel|string $model
     * @phpstan-param BaseModel|class-string<T> $model
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return T
     */
    public function hasOne($model, $foreignKey = null, $localKey = null): BaseModel
    {
        $related = $this->instanceRelationModel($model);
        $localKey || $localKey = $this->getPrimaryKey();
        $foreignKey || $foreignKey = $this->getForeignKey();

        $localKey = $this->convertToPhpKey($localKey);
        $foreignKey = $this->convertToPhpKey($foreignKey);

        $value = $this->getRelationParams($localKey);
        $related->setRelation([
            'localKey' => $localKey,
            'foreignKey' => $foreignKey,
            'localValue' => $value,
        ]);

        $related->where($foreignKey, $value);

        return $related;
    }

    /**
     * @template T of BaseModel
     * @param self|string $model
     * @phpstan-param self|class-string<T> $model
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return T
     */
    public function hasMany($model, $foreignKey = null, $localKey = null): BaseModel
    {
        return $this->hasOne($model, $foreignKey, $localKey)->beColl();
    }

    /**
     * @template T of BaseModel
     * @param self|string $model
     * @phpstan-param self|class-string<T> $model
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return T
     */
    public function belongsTo($model, $foreignKey = null, $localKey = null): BaseModel
    {
        $related = $this->instanceRelationModel($model);
        $foreignKey || $foreignKey = $this->getPrimaryKey();
        $localKey || $localKey = Str::snake($related->getModelBaseName()) . '_' . $this->getPrimaryKey();

        return $this->hasOne($related, $foreignKey, $localKey);
    }

    /**
     * @template T of BaseModel
     * @param self|string $model
     * @phpstan-param self|class-string<T> $model
     * @param string|null $junctionTable
     * @param string|null $foreignKey
     * @param string|null $relatedKey
     * @return T
     */
    public function belongsToMany($model, $junctionTable = null, $foreignKey = null, $relatedKey = null): BaseModel
    {
        $related = $this->instanceRelationModel($model);
        $name = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];

        if ($junctionTable && class_exists($junctionTable)) {
            /** @var class-string<BaseModel> $junctionClass */
            $junctionClass = $junctionTable;
            $junctionTable = $junctionClass::getTable();
        } else {
            // Example: convert "[ArticleModel, TagModel]" to "articlesTagModel"
            $names = [$this->getModelBaseName(), $related->getModelBaseName()];
            sort($names);
            $serviceName = Str::pluralize(lcfirst($names[0])) . ucfirst($names[1]);
            if ('Model' === substr(Cls::baseName(static::class), -5)) {
                $serviceName .= 'Model';
            }
            $junctionClass = $this->wei->getClass($serviceName);
        }

        $primaryKey = $this->getPrimaryKey();
        $junctionTable || $junctionTable = $this->getJunctionTable($related);
        $foreignKey || $foreignKey = $this->getForeignKey();
        $relatedKey || $relatedKey = Str::snake($related->getModelBaseName()) . '_' . $primaryKey;

        $related->setRelation([
            'junctionTable' => $junctionTable,
            'relatedKey' => $this->convertToPhpKey($relatedKey),
            'foreignKey' => $this->convertToPhpKey($foreignKey),
            'localKey' => $this->convertToPhpKey($primaryKey),
            // @experimental
            'name' => $name,
            'foreignModel' => $this,
            'foreignValue' => $this->getRelationParams($primaryKey),
            'junctionClass' => $junctionClass,
        ]);

        $relatedTable = $related->getTable();
        $related->select($relatedTable . '.*')
            ->where([$junctionTable . '.' . $foreignKey => $this->getRelationParams($primaryKey)])
            ->innerJoin($junctionTable, $junctionTable . '.' . $relatedKey, '=', $relatedTable . '.' . $primaryKey)
            ->beColl();

        return $related;
    }

    /**
     * Eager load relations
     *
     * @param array|string $names
     * @return $this|$this[]
     * @phpstan-return $this
     */
    public function load($names): self
    {
        foreach ((array) $names as $name) {
            $parts = explode('.', $name, 2);
            $name = $parts[0];
            $next = $parts[1] ?? null;

            if ($this->isLoaded($name)) {
                $value = $this->getRelationValue($name);
            } else {
                $value = $this->isColl() ? $this->loadCollRelation($name) : $this->loadOneRelation($name);
                $this->setRelationValue($name, $value);
            }

            if ($next && $value) {
                $value->load($next);
            }
        }

        return $this;
    }

    /**
     * Check if the relation is loaded
     *
     * @param string $name
     * @return bool
     */
    public function isLoaded(string $name): bool
    {
        return array_key_exists($name, $this->relationValues);
    }

    /**
     * Set relation value
     *
     * @param string $name
     * @param BaseModel|null $value
     * @return $this
     */
    public function setRelationValue(string $name, ?BaseModel $value): self
    {
        $this->relationValues[$name] = $value;
        $this->loadedRelations[$name] = true;
        return $this;
    }

    /**
     * Remove relation value
     *
     * @param string $name
     * @return $this
     */
    public function removeRelationValue(string $name): self
    {
        unset($this->relationValues[$name]);
        return $this;
    }

    /**
     * Save data to junction table
     *
     * @param mixed $ids
     * @return void
     * @experimental
     */
    public function syncRelation($ids)
    {
        $relation = $this->relation;

        // Load all junction models
        /** @var BaseModel|BaseModel[] $coll */
        $coll = $relation['junctionClass']::new();
        $coll->where($relation['foreignKey'], $relation['foreignValue'])->all();

        // Remove missing models
        $ids = (array) $ids;
        $existIds = [];
        foreach ($coll as $model) {
            $id = $model->getColumnValue($relation['relatedKey']);
            if (!in_array($id, $ids, true)) {
                $model->destroy();
            } else {
                $existIds[] = $id;
            }
        }

        // Add new models
        $newIds = array_diff($ids, $existIds);
        if ($newIds) {
            foreach ($newIds as $id) {
                /** @var BaseModel $model */
                $model = $relation['junctionClass']::new();
                $model->saveAttributes([
                    $relation['foreignKey'] => $relation['foreignValue'],
                    $relation['relatedKey'] => $id,
                ]);
            }
        }

        // Remove loaded values
        $relation['foreignModel']->removeRelationValue($relation['name']);
    }

    /**
     * Add a (inner) join base on the relation to the query
     *
     * @param string|array $name
     * @param string $type
     * @return $this
     * @svc
     */
    protected function joinRelation($name, string $type = 'INNER'): self
    {
        foreach ((array) $name as $item) {
            if (isset($this->joinRelations[$item][$type])) {
                continue;
            }
            $this->joinRelations[$item][$type] = true;

            $related = $this->getRelationModel($item);
            $config = $related->getRelation();
            $table = $related->getTable();

            // Dealing with different databases
            if ($related->getDb() !== $this->getDb()) {
                $table = $related->getDb()->getDbname() . '.' . $table;
            }

            $this->join(
                $table,
                $table . '.' . $config['foreignKey'],
                '=',
                $this->getTable() . '.' . $config['localKey'],
                $type
            );
        }
        return $this;
    }

    /**
     * Add a inner join base on the relation to the query
     *
     * @param string|array $name
     * @return $this
     * @svc
     */
    protected function innerJoinRelation($name): self
    {
        return $this->joinRelation($name);
    }

    /**
     * Add a left join base on the relation to the query
     *
     * @param string|array $name
     * @return $this
     * @svc
     */
    protected function leftJoinRelation($name): self
    {
        return $this->joinRelation($name, 'LEFT');
    }

    /**
     * Add a right join base on the relation to the query
     *
     * @param string|array $name
     * @return $this
     * @svc
     */
    protected function rightJoinRelation($name): self
    {
        return $this->joinRelation($name, 'RIGHT');
    }

    /**
     * Convert relation values to array
     *
     * @return array
     */
    protected function relationToArray(): array
    {
        $data = [];
        foreach ($this->relationValues as $name => $value) {
            $data[$name] = $value ? $value->toArray() : null;
        }
        return $data;
    }

    /**
     * @param string $name
     * @return BaseModel
     * @internal
     */
    protected function loadCollRelation(string $name): BaseModel
    {
        $related = $this->getRelationModel($name);
        $isColl = $related->isColl();
        $relation = $related->getRelation();

        // @experimental
        if (isset($relation['match'])) {
            return $this->loadCustomRelation($related, $relation, $name);
        }

        // Fetch relation model data
        $ids = $this->getAll($relation['localKey']);
        $ids = array_unique(array_filter($ids));

        if ($ids) {
            $this->relationParams = $ids;
            $related = $this->{$name}();
            $this->relationParams = null;
        } else {
            $related->beColl();
        }

        // Load relation data
        if (isset($relation['junctionTable'])) {
            $models = $this->loadBelongsToMany($related, $relation, $name);
        } elseif ($isColl) {
            $models = $this->loadHasMany($related, $relation, $name);
        } else {
            $models = $this->loadHasOne($related, $relation, $name);
        }

        return $models;
    }

    /**
     * @param string $name
     * @return BaseModel|null
     * @internal
     */
    protected function loadOneRelation(string $name): ?BaseModel
    {
        $related = $this->getRelationModel($name);
        $relation = $related->getRelation();
        $localValue = $this->get($relation['localKey']);

        // hasMany/belongsToMany
        if ($related->isColl()) {
            if ($localValue) {
                return $related->all();
            } else {
                return $related;
            }
        }

        // hasOne
        if ($localValue) {
            return $related->first() ?: null;
        } else {
            return null;
        }
    }

    /**
     * @param BaseModel|null $related
     * @param array $relation
     * @param string $name
     * @return $this|$this[]
     */
    protected function loadHasOne(?BaseModel $related, array $relation, string $name)
    {
        if ($related) {
            $models = $related->all()->indexBy($relation['foreignKey']);
        } else {
            $models = [];
        }

        /** @var static $row */
        foreach ($this->attributes as $row) {
            $row->setRelationValue($name, $models[$row[$relation['localKey']]] ?? null);
        }
        return $models;
    }

    /**
     * @param BaseModel|null $related
     * @param array $relation
     * @param string $name
     * @return BaseModel|BaseModel[]
     * @phpstan-return BaseModel
     */
    protected function loadHasMany(?BaseModel $related, array $relation, string $name)
    {
        $coll = [];
        $data = $related ? $related->fetchAll() : [];
        $hasForeignKey = $related && $related->hasColumn($relation['foreignKey']);

        // An array containing model objects grouped by relational foreign keys
        $groupBy = [];

        foreach ($data as $row) {
            $localValue = $row[$relation['localKey']];
            if (isset($coll[$localValue])) {
                $groupBy[$row[$relation['foreignKey']]][] = $coll[$localValue];
            } else {
                $model = $related::new([], ['new' => false]);
                $groupBy[$row[$relation['foreignKey']]][] = $model;

                // Remove external data
                if (!$hasForeignKey) {
                    unset($row[$relation['foreignKey']]);
                }
                $model->setAttributesFromDb($row);
                $coll[$localValue] = $model;
            }
        }

        foreach ($this->attributes as $model) {
            $modelRelation = $related::newColl();
            $model->setRelationValue($name, $modelRelation);

            // NOTE: 从数据库取出为 string, 因此必须转换了再比较
            $localValue = (string) $model->getColumnValue($relation['localKey']);
            if (isset($groupBy[$localValue])) {
                $modelRelation->setAttributes($groupBy[$localValue]);
            }
        }

        return $related ? $related::newColl(array_values($coll)) : null;
    }

    /**
     * @param BaseModel|null $related
     * @param array $relation
     * @param string $name
     * @return BaseModel|BaseModel[]
     * @phpstan-return BaseModel
     */
    protected function loadBelongsToMany(?BaseModel $related, array $relation, string $name)
    {
        if ($related) {
            $related->select($relation['junctionTable'] . '.' . $relation['foreignKey']);
        }

        return $this->loadHasMany($related, $relation, $name);
    }

    /**
     * @param BaseModel $related
     * @param array $relation
     * @param string $name
     * @return BaseModel|BaseModel[]
     * @phpstan-return BaseModel
     * @experimental
     */
    protected function loadCustomRelation(BaseModel $related, array $relation, string $name)
    {
        $isMany = $related->isColl();
        $coll = $related::newColl();
        $data = $related->fetchAll();

        foreach ($data as $row) {
            $model = $related::new([], ['new' => false]);
            $model->setAttributesFromDb($row);
            $coll[] = $model;

            foreach ($this->attributes as $attribute) {
                // TODO refactor one to one logic
                // one to one has loaded
                if (!$isMany && $attribute->isLoaded($name)) {
                    continue;
                }

                if (!$relation['match']($attribute, $model)) {
                    continue;
                }

                // one to one
                if (!$isMany) {
                    $attribute->setRelationValue($name, $model);
                    continue;
                }

                // one to many
                if (!$attribute->isLoaded($name)) {
                    $relationValue = $related::newColl();
                    $attribute->setRelationValue($name, $relationValue);
                } else {
                    $relationValue = $attribute->getRelationValue($name);
                }
                $relationValue[] = $model;
            }
        }

        return $coll;
    }

    /**
     * Generate the foreign key name
     *
     * @return string
     */
    protected function getForeignKey(): string
    {
        return Str::snake($this->getModelBaseName()) . '_' . $this->getPrimaryKey();
    }

    /**
     * Generate the junction table name
     *
     * @param BaseModel $related
     * @return string
     */
    protected function getJunctionTable(BaseModel $related): string
    {
        $tables = [$this->getTable(), $related->getTable()];
        sort($tables);

        return implode('_', $tables);
    }

    /**
     * Return the parameter values for the relation base query
     *
     * @param string $column
     * @return mixed
     */
    protected function getRelationParams(string $column)
    {
        return $this->coll ? $this->relationParams : $this->getColumnValue($column);
    }

    /**
     * Create a relation model object with the model name or class name specified by the user,
     * or return the parameter if the parameter is a model object
     *
     * @param object|string $model
     * @return BaseModel
     */
    protected function instanceRelationModel($model): BaseModel
    {
        if ($model instanceof BaseModel) {
            return $model;
        }

        if (is_subclass_of($model, BaseModel::class)) {
            return forward_static_call([$model, 'new']);
        }

        throw new \InvalidArgumentException(sprintf(
            'Expected "model" argument to be a subclass or an instance of BaseModel, "%s" given',
            // @phpstan-ignore-next-line Else branch is unreachable because ternary operator condition is always true.
            is_object($model) ? get_class($model) : (is_string($model) ? $model : gettype($model))
        ));
    }

    /**
     * Call the relation method to receive the relation model object,
     * if the relation method does not exist, or the specified method is not a relation method,
     * an exception will be thrown
     *
     * @param string $name
     * @param bool $throw
     * @return BaseModel|null
     * @internal For model use only
     */
    public function getRelationModel(string $name, bool $throw = true): ?BaseModel
    {
        // Ignore parent method
        if (method_exists(ModelTrait::class, $name)) {
            return null;
        }

        if (!method_exists($this, $name)) {
            return null;
        }

        // WARNING: Do not pass any untrusted names to avoid being attacked
        $related = $this->{$name}();

        if (!$related instanceof BaseModel) {
            if ($throw) {
                throw new \LogicException(sprintf(
                    'Expected method "%s" to return an instance of BaseModel, but returns "%s"',
                    $name,
                    is_object($related) ? get_class($related) : gettype($related)
                ));
            } else {
                return null;
            }
        }

        return $related;
    }

    /**
     * Load and return the relation value
     *
     * @param string $name
     * @param bool $exists
     * @param bool $throw
     * @return BaseModel|null
     */
    protected function &getRelationValue(string $name, ?bool &$exists = null, bool $throw = true): ?BaseModel
    {
        $exists = true;
        if (array_key_exists($name, $this->relationValues)) {
            return $this->relationValues[$name];
        }

        $related = $this->getRelationModel($name, $throw);
        if (!$related) {
            $exists = false;
            return $related;
        }

        $this->load($name);
        return $this->relationValues[$name];
    }

    /**
     * Check if the model method defines the "Relation" attribute (or the "@Relation" tag in doc comment)
     *
     * This method only checks whether the specified method has the "Relation" attribute,
     * and does not check the actual logic.
     * It is provided for external use to avoid directly calling `$this->$relation()` to cause attacks.
     *
     * @param string $method
     * @return bool
     * @svc
     */
    protected function isRelation(string $method): bool
    {
        try {
            $ref = new \ReflectionMethod($this, $method);
        } catch (\ReflectionException $e) {
            return false;
        }

        // PHP 8
        if (method_exists($ref, 'getAttributes') && $ref->getAttributes(Relation::class)) {
            return true;
        }

        // Compat with PHP less than 8
        return false !== strpos($ref->getDocComment() ?: '', '@Relation');
    }

    /**
     * Set the relation config
     *
     * @param array $relation
     * @return $this
     * @internal For model use only
     */
    public function setRelation(array $relation): self
    {
        $this->relation = $relation;
        return $this;
    }

    /**
     * Return the relation config
     *
     * @return array
     * @internal For model use only
     */
    public function getRelation(): array
    {
        return $this->relation;
    }
}
