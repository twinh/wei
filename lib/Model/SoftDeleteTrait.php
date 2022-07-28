<?php

namespace Wei\Model;

use Wei\BaseModel;
use Wei\Time;

/**
 * Add soft delete function to model class
 *
 * @property-read string $deletedAtColumn The column contains delete time
 * @property-read string $deletedByColumn The column contains delete user id
 * @property-read string $purgedAtColumn The column contains purge time
 * @property-read string $purgedByColumn The column contains purge user id
 * @property-read string $enableTrash Whether enable trash or not
 * @property-read string $deletedStatusColumn The column contains delete status value
 */
trait SoftDeleteTrait
{
    /**
     * Indicates whether really remove the record from database
     *
     * @var bool
     */
    protected $reallyDestroy = false;

    /**
     * Bootstrap the trait
     *
     * @param BaseModel $initModel
     */
    public static function bootSoftDeleteTrait(BaseModel $initModel): void
    {
        $initModel->addDefaultScope('withoutDeleted');
        static::onModelEvent('init', 'addedDeleteColumnToGuarded');
        static::onModelEvent('destroy', 'executeSoftDelete');
    }

    /**
     * Indicate whether the model has been soft deleted
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return (bool) $this->get($this->getDeletedAtColumn());
    }

    /**
     * Indicate whether the model has been purged
     *
     * @return bool
     */
    public function isPurged(): bool
    {
        return (bool) $this->get($this->getPurgedAtColumn());
    }

    /**
     * @return bool
     */
    public function isEnableTrash(): bool
    {
        return property_exists($this, 'enableTrash') ? $this->enableTrash : false;
    }

    /**
     * Restore the record to the normal state
     *
     * @return $this
     */
    public function restore(): self
    {
        $data = [
            $this->getDeletedAtColumn() => null,
            $this->getDeletedByColumn() => 0,
        ];
        if ($statusColumn = $this->getDeleteStatusColumn()) {
            $data[$statusColumn] = $this->getRestoreStatusValue();
        }
        return $this->saveAttributes($data);
    }

    /**
     * Delete the record in the trash
     *
     * @return $this
     */
    public function purge(): self
    {
        return $this->saveAttributes([
            $this->getPurgedAtColumn() => Time::now(),
            $this->getPurgedByColumn() => $this->wei->user->id ?: 0,
        ]);
    }

    /**
     * Restore the record to the trash
     *
     * @return $this
     */
    public function restorePurge(): self
    {
        return $this->saveAttributes([
            $this->getPurgedAtColumn() => null,
            $this->getPurgedByColumn() => 0,
        ]);
    }

    /**
     * Really remove the record from database
     *
     * @param int|string $id
     * @return $this
     * @svc
     */
    protected function reallyDestroy($id = null): self
    {
        $this->reallyDestroy = true;
        $this->destroy($id);
        $this->reallyDestroy = false;

        return $this;
    }

    /**
     * Add a query to filter soft deleted records
     *
     * @return $this
     * @svc
     */
    protected function withoutDeleted(): self
    {
        if ($statusColumn = $this->getDeleteStatusColumn()) {
            return $this->where($statusColumn, '!=', $this->getDeleteStatusValue());
        } else {
            return $this->whereNull($this->getDeletedAtColumn());
        }
    }

    /**
     * Add a query to return only deleted records
     *
     * @return $this
     * @svc
     */
    protected function onlyDeleted(): self
    {
        $this->unscoped('withoutDeleted');

        if ($this->isEnableTrash()) {
            $this->whereNull($this->getPurgedAtColumn());
        }

        if ($statusColumn = $this->getDeleteStatusColumn()) {
            return $this->where($statusColumn, $this->getDeleteStatusValue());
        } else {
            return $this->whereNotNull($this->getDeletedAtColumn());
        }
    }

    /**
     * Remove "withoutDeleted" in the query, expect to return all records
     *
     * @return $this
     * @svc
     */
    protected function withDeleted(): self
    {
        return $this->unscoped('withoutDeleted');
    }

    /**
     * Add a query to return only purged records
     *
     * @return $this
     * @svc
     */
    protected function onlyPurged(): self
    {
        return $this->unscoped('withoutDeleted')->whereNotNull($this->getPurgedAtColumn());
    }

    protected function getDeletedAtColumn(): string
    {
        return $this->deletedAtColumn ?? 'deleted_at';
    }

    protected function getDeletedByColumn(): string
    {
        return $this->deletedByColumn ?? 'deleted_by';
    }

    protected function getPurgedAtColumn(): string
    {
        return $this->purgedAtColumn ?? 'purged_at';
    }

    protected function getPurgedByColumn(): string
    {
        return $this->purgedByColumn ?? 'purged_by';
    }

    /**
     * Get the column of delete status
     *
     * The model class can override this method to customize the value of the delete state
     *
     * @return string|null
     */
    protected function getDeleteStatusColumn(): ?string
    {
        return property_exists($this, 'deleteStatusColumn') ? $this->deleteStatusColumn : null;
    }

    /**
     * Get the value of delete status
     *
     * The model class can override this method to customize the value of the delete state
     *
     * @return int|string
     */
    protected function getDeleteStatusValue()
    {
        return 1;
    }

    /**
     * Get the value of restore status
     *
     * The model class can override this method to customize the value of the restore state
     *
     * @return int|string
     */
    protected function getRestoreStatusValue()
    {
        return 0;
    }

    /**
     * @internal
     */
    protected function executeSoftDelete(): bool
    {
        if ($this->reallyDestroy) {
            return true;
        }

        $data = [
            $this->getDeletedAtColumn() => Time::now(),
            $this->getDeletedByColumn() => $this->wei->user->id ?: 0,
        ];
        if ($statusColumn = $this->getDeleteStatusColumn()) {
            $data[$statusColumn] = $this->getDeleteStatusValue();
        }
        $this->saveAttributes($data);

        // Return false to stop the default delete behavior
        return false;
    }

    /**
     * @internal
     */
    protected function addedDeleteColumnToGuarded(): void
    {
        $this->guarded = array_merge($this->guarded, array_filter([
            $this->getDeletedAtColumn(),
            $this->getDeletedByColumn(),
            $this->getDeleteStatusColumn(),
            $this->getPurgedAtColumn(),
            $this->getPurgedByColumn(),
        ]));
    }
}
