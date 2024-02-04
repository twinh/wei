<?php

namespace Wei\Model;

use Wei\Ret;

/**
 * Add collection functions to the model
 *
 * @internal Expected to be used only by ModelTrait
 */
trait CollTrait
{
    /**
     * Whether it contains multiple or single row data
     *
     * @var bool
     */
    protected $coll = false;

    /**
     * Create a new model collection
     *
     * @param iterable $attributes
     * @param array $options
     * @return $this|$this[]
     * @phpstan-return $this
     */
    public static function newColl(iterable $attributes = [], array $options = []): self
    {
        return static::new($attributes, ['coll' => true] + $options);
    }

    /**
     * Returns whether it contains multiple or single row data
     *
     * @return bool
     */
    public function isColl(): bool
    {
        return $this->coll;
    }

    /**
     * Clear the default attribute values and convert to a collection
     *
     * @return $this|$this[]
     * @phpstan-return $this
     */
    public function beColl(): self
    {
        $this->attributes = [];
        $this->coll = true;

        return $this;
    }

    /**
     * Merges attributes into collection and save to database, including insert, update and delete
     *
     * @param array $attributes A two-dimensional array
     * @param array $extra The extra attributes for new rows
     * @param string|null $sortColumn
     * @return $this
     */
    public function saveColl(array $attributes, array $extra = [], ?string $sortColumn = null): self
    {
        // 1. Uses primary key as data index
        $newAttributes = [];
        $primaryKey = $this->getPrimaryKey();
        foreach ($this->attributes as $key => $record) {
            unset($this->attributes[$key]);
            // Ignore default data
            if ($record instanceof $this) {
                $newAttributes[$record[$primaryKey]] = $record;
            }
        }
        $this->attributes = $newAttributes;

        // 2. Removes empty rows from data
        foreach ($attributes as $index => $row) {
            if (empty($row)) {
                unset($attributes[$index]);
            }
        }

        // 3. Removes missing rows
        $existIds = [];
        foreach ($attributes as $row) {
            if (is_object($row)) {
                $row = (array) $row;
            }

            if (isset($row[$primaryKey]) && null !== $row[$primaryKey]) {
                $existIds[] = $row[$primaryKey];
            }
        }
        /** @var static $record */
        foreach ($this->attributes as $key => $record) {
            if (!in_array($record[$primaryKey], $existIds, true)) {
                $record->destroy();
                unset($this->attributes[$key]);
            }
        }

        // 4. Merges existing rows or create new rows
        foreach ($attributes as $index => $row) {
            if (is_object($row)) {
                $row = (array) $row;
            }

            if ($sortColumn) {
                $row[$sortColumn] = $index;
            }
            if (isset($row[$primaryKey], $this->attributes[$row[$primaryKey]])) {
                $this->attributes[$row[$primaryKey]]->fromArray($row);
            } else {
                $this->offsetSet(null, static::new()->fromArray($extra + $row));
            }
        }
        $this->attributes = array_values($this->attributes);

        // 5. Save and return
        return $this->save();
    }

    /**
     * Set field value for every record in the collection
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setAll(string $name, $value): self
    {
        foreach ($this->attributes as $i => $item) {
            $this->attributes[$i][$name] = $value;
        }
        return $this;
    }

    /**
     * Return the value of field from every record in the collection
     *
     * @param string $name
     * @return array
     */
    public function getAll(string $name): array
    {
        $items = [];
        foreach ($this->attributes as $item) {
            $items[] = $item[$name];
        }
        return $items;
    }

    /**
     * Filters elements of the collection using a callback function
     *
     * @param callable $fn
     * @return $this|$this[]
     * @phpstan-return $this
     */
    public function filter(callable $fn)
    {
        $this->ensureColl();

        $attributes = array_filter($this->attributes, $fn);
        return static::newColl($attributes)->setOption([
            'new' => $this->new,
        ]);
    }

    /**
     * Returns the record number in collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * Retrieve an array iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * Check if the key name exists in the collection
     *
     * @param string|int $name
     * @return bool
     */
    protected function hasColl($name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Set the value of the specified key name in the collection
     *
     * @param string|int|null $name
     * @param self $value
     * @return $this
     */
    protected function setCollValue($name, self $value): self
    {
        // Support $coll[] = $value;
        if (null === $name) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    /**
     * Get the value of the specified key name in the collection
     *
     * @param string|int $name
     * @return self
     */
    protected function &getCollValue($name): self
    {
        return $this->attributes[$name];
    }

    /**
     * Apply the specified method to the attribute of the collection
     *
     * @param string $method
     * @param array $args
     * @return array
     * @internal
     */
    protected function mapColl(string $method, array $args = []): array
    {
        $results = [];
        foreach ($this->attributes as $key => $model) {
            $results[$key] = $model->{$method}(...$args);
        }
        return $results;
    }

    /**
     * Throw an exception if the current object is not a collection
     *
     * @internal
     */
    protected function ensureColl(): void
    {
        if (!$this->coll) {
            $traces = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            throw new \BadMethodCallException(sprintf(
                'Method "%s" can be called when the object is a collection',
                $traces[1]['function']
            ));
        }
    }

    /**
     * Convert the collection to list data
     *
     * @param array $merge
     * @return Ret
     * @internal
     */
    protected function collToRet(array $merge = []): Ret
    {
        $this->ensureColl();
        return $this->suc(array_merge([
            'data' => $this,
            'page' => $this->getQueryPart('page'),
            'limit' => $this->getQueryPart('limit'),
            'total' => $this->cnt(),
        ], $merge));
    }
}
