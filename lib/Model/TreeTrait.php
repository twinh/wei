<?php

namespace Wei\Model;

use InvalidArgumentException;
use Wei\Snowflake;

/**
 * @property $this $parent
 * @property $this|$this[] $children
 * @property $this $root
 * @property $this|$this[] $ancestors
 * @property $this|$this[] $descendants
 * @property string $pathColumn The column contains the tree path
 * @property string $levelColumn The column contains the tree level
 * @property string $parentIdColumn The column contains the tree parent ID
 * @property string $pathSeparator A character used to split node paths
 */
trait TreeTrait
{
    /**
     * The descendants need to update after the current node update
     *
     * @var array
     * @internal
     */
    protected $updateDescendants = [];

    /**
     * @var array<int|string>
     * @internal
     */
    protected static $deletingTreeIds = [];

    public static function bootTreeTrait()
    {
        static::onModelEvent('init', 'initTree');
        static::onModelEvent('beforeCreate', 'beforeCreateTree');
        static::onModelEvent('beforeUpdate', 'beforeUpdateTree');
        static::onModelEvent('afterUpdate', 'afterUpdateTree');
        static::onModelEvent('afterDestroy', 'afterDestroyTree');
    }

    /**
     * @return $this
     */
    public function parent(): self
    {
        return $this->belongsTo(self::class, null, $this->getParentIdColumn());
    }

    /**
     * @return $this|$this[]
     */
    public function children(): self
    {
        return $this->hasMany(self::class, $this->getParentIdColumn());
    }

    /**
     * Create the root node query
     *
     * @return $this
     */
    public function root(): self
    {
        $pathColumn = $this->getPathColumn();
        return static::new()
            ->where($pathColumn, $this->getRootPaths() ?: null)
            ->setRelation([
                'localKey' => $pathColumn,
                'match' => function (self $related, self $dbModel) {
                    return $dbModel->isAncestorOf($related);
                },
            ]);
    }

    /**
     * Create the ancestor nodes query
     *
     * @return $this|$this[]
     */
    public function ancestors(): self
    {
        $pathColumn = $this->getPathColumn();
        return static::newColl()
            ->where($pathColumn, $this->getAncestorPaths() ?: null)
            ->asc($pathColumn)
            ->setRelation([
                'localKey' => $pathColumn,
                'match' => function (self $related, self $dbModel) {
                    return $dbModel->isAncestorOf($related);
                },
            ]);
    }

    /**
     * Create the descendant node query
     *
     * @return $this
     */
    public function descendants(): self
    {
        $pathColumn = $this->getPathColumn();
        return static::newColl()
            ->where(function (self $node) {
                $pathSeparator = $this->getPathSeparator();
                foreach ($this->getDescendantPrefixes() as $prefix) {
                    // NOTE: can't hit index
                    $node->orWhere($this->getPathColumn(), 'LIKE', $prefix . $pathSeparator . '%');
                }
            })
            ->setRelation([
                'localKey' => $pathColumn,
                'match' => function (self $related, self $dbModel) {
                    return $dbModel->isDescendantOf($related);
                },
            ]);
    }

    /**
     * Create the sibling nodes query
     *
     * @return $this
     */
    public function siblings(): self
    {
        $pk = $this->getPrimaryKey();
        return self::new()
            ->whereNot($pk, $this->getColumnValue($pk))
            ->where($this->getParentIdColumn(), $this->getColumnValue($this->getParentIdColumn()));
    }

    /**
     * Instance a new child node
     *
     * @param iterable $attributes
     * @return $this
     */
    public function addNode(iterable $attributes = []): self
    {
        return static::new($attributes)->setColumnValue(
            $this->getParentIdColumn(),
            $this->getColumnValue($this->getPrimaryKey())
        );
    }

    /**
     * Create a new child node
     *
     * @param iterable $attributes
     * @return $this
     */
    public function saveNode(iterable $attributes = []): self
    {
        return $this->addNode($attributes)->save();
    }

    /**
     * Return node id and descendants ids, useful for search
     *
     * @return array
     */
    public function getSelfAndDescendantsIds(): array
    {
        return array_merge(
            [$this->getColumnValue($this->getPrimaryKey())],
            $this->descendants->getAll($this->getPrimaryKey())
        );
    }

    /**
     * Check if the current node is ancestor of the specified node
     *
     * @param self $node
     * @return bool
     */
    public function isAncestorOf(self $node): bool
    {
        if ($this->isNew()) {
            // New model don't have path, Avoid empty needle error
            return false;
        }
        $pathColumn = $this->getPathColumn();
        return 0 === strpos($node->getColumnValue($pathColumn), $this->getColumnValue($pathColumn));
    }

    /**
     * Check if the current node is descendant of the specified node
     *
     * @param self $node
     * @return bool
     */
    public function isDescendantOf(self $node): bool
    {
        if ($node->isNew()) {
            // New model don't have path, Avoid empty needle error
            return false;
        }
        $pathColumn = $this->getPathColumn();
        return 0 === strpos($this->getColumnValue($pathColumn), $node->getColumnValue($pathColumn));
    }

    /**
     * Convert collection to tree
     *
     * @coll
     */
    public function toTree(): self
    {
        $nodes = [];
        $parentIdColumn = $this->getParentIdColumn();
        $roots = [];

        foreach ($this->attributes as $node) {
            $nodes[$node->id] = $node;
        }
        foreach ($this->attributes as $node) {
            $parent = $nodes[$node->getColumnValue($parentIdColumn)] ?? null;
            if ($parent) {
                if (!$parent->isLoaded('children')) {
                    $coll = static::newColl();
                    $parent->setRelationValue('children', $coll);
                }
                $parent->children[] = $node;
            } else {
                $roots[] = $node;
            }
        }
        $this->attributes = $roots;
        return $this;
    }

    /**
     * Load descendants to `children` relation
     *
     * @return $this
     * @experimental
     */
    public function loadTree(): self
    {
        $children = [];
        $parentIdColumn = $this->getParentIdColumn();
        $nodes = $this->descendants;

        foreach ($nodes as $node) {
            $children[$node->getColumnValue($parentIdColumn)][] = $node;
        }

        $nodes[] = $this;
        foreach ($nodes as $node) {
            $coll = static::newColl();
            $pk = $node->getColumnValue($node->getPrimaryKey());
            if (isset($children[$pk]) && $children[$pk]) {
                $coll->setAttributes($children[$pk]);
            }
            $node->setRelationValue('children', $coll);
        }

        return $this;
    }

    /**
     * Update tree path and level, use to add path and level to table after migrated
     *
     * ```php
     * CategoryModel::whereNotHas('parentId')->all()->updateTree();
     * ```
     *
     * @return $this
     * @experimental
     */
    public function updateTree(): self
    {
        // Eager load for collection
        $this->load('children');

        if ($this->isColl()) {
            $this->mapColl(__FUNCTION__);
            return $this;
        }

        $this->generateTreeAttributes();
        $this->save();
        foreach ($this->children as $child) {
            $child->{__FUNCTION__}();
        }
        return $this;
    }

    protected function getLevelColumn(): string
    {
        return $this->levelColumn ?? 'level';
    }

    protected function getPathColumn(): string
    {
        return $this->pathColumn ?? 'path';
    }

    protected function getParentIdColumn(): string
    {
        return $this->parentIdColumn ?? 'parent_id';
    }

    protected function getPathSeparator(): string
    {
        return $this->pathSeparator ?? ',';
    }

    /**
     * @return string[]
     */
    protected function getRootPaths(): array
    {
        if ($this->isColl()) {
            return array_unique(array_merge(...$this->mapColl(__FUNCTION__)));
        }

        [$rootPath] = explode($this->getPathSeparator(), $this->getColumnValue($this->getPathColumn()), 2);
        return [$rootPath];
    }

    /**
     * @return string[]
     */
    protected function getAncestorPaths(): array
    {
        if ($this->isColl()) {
            return array_unique(array_merge(...$this->mapColl(__FUNCTION__)));
        }

        $lastPath = '';
        $ancestorPaths = [];
        $pathColumn = $this->getPathColumn();
        $pathSeparator = $this->getPathSeparator();

        $paths = explode($pathSeparator, $this->getColumnValue($pathColumn));
        array_pop($paths);

        foreach ($paths as $path) {
            if ('' !== $lastPath) {
                $lastPath .= $pathSeparator;
            }
            $lastPath .= $path;
            $ancestorPaths[] = $lastPath;
        }

        return $ancestorPaths;
    }

    /**
     * @return string[]
     */
    protected function getDescendantPrefixes(): array
    {
        if ($this->isColl()) {
            return $this->getAll($this->getPathColumn());
        }
        return [
            $this->getColumnValue($this->getPathColumn()),
        ];
    }

    protected function initTree()
    {
        $this->hidden[] = $this->getPathColumn();
    }

    /**
     * Generate the node path and level
     *
     * @return void
     */
    protected function beforeCreateTree()
    {
        $this->generateTreeAttributes();
    }

    /**
     * Generate the path and level if parent is changed
     *
     * @return void
     */
    protected function beforeUpdateTree()
    {
        if (!$this->isChanged($this->getParentIdColumn())) {
            $this->updateDescendants = [];
            return;
        }

        // Avoid use old parent value
        $this->removeRelationValue('parent');

        $parentId = $this->getColumnValue($this->getParentIdColumn());

        if ($parentId === $this->getColumnValue($this->getPrimaryKey())) {
            throw new InvalidArgumentException('Node can\'t move to self');
        }

        if ($parentId && $this->isAncestorOf($this->parent)) {
            throw new InvalidArgumentException('Node can\'t move to child');
        }

        $this->updateDescendants = [
            'oldPath' => $this->getColumnValue($this->getPathColumn()),
            'oldLevel' => $this->getColumnValue($this->getLevelColumn()),
            'descendants' => $this->descendants,
        ];
        $this->generateTreeAttributes();
    }

    /**
     * Update descendants path and level
     *
     * @return void
     */
    protected function afterUpdateTree()
    {
        if (!$this->updateDescendants) {
            return;
        }

        $pathColumn = $this->getPathColumn();
        $levelColumn = $this->getLevelColumn();
        $newPath = $this->getColumnValue($pathColumn);
        $oldPath = $this->updateDescendants['oldPath'];
        $levelOffset = $this->getColumnValue($levelColumn) - $this->updateDescendants['oldLevel'];

        $descendants = $this->updateDescendants['descendants'];
        foreach ($descendants as $descendant) {
            $descendant->setColumnValue(
                $pathColumn,
                str_replace($oldPath, $newPath, $descendant->getColumnValue($pathColumn))
            );
            $descendant->setColumnValue($levelColumn, $descendant->getColumnValue($levelColumn) + $levelOffset);
        }
        $descendants->save();
    }

    /**
     * Destroy the descendants
     *
     * @return void
     */
    protected function afterDestroyTree()
    {
        if (in_array($this->getColumnValue($this->getPrimaryKey()), static::$deletingTreeIds, true)) {
            return;
        }

        $descendants = $this->descendants;
        static::$deletingTreeIds = $descendants->getAll($this->getPrimaryKey());
        $descendants->destroy();
        static::$deletingTreeIds = [];
    }

    /**
     * Generate the node path and level
     *
     * @return void
     */
    protected function generateTreeAttributes()
    {
        $this->setColumnValue($this->getPathColumn(), $this->generateTreePath());
        $this->setColumnValue($this->getLevelColumn(), $this->generateTreeLevel());
    }

    /**
     * Generate the path with the parent path
     *
     * @return string
     */
    protected function generateTreePath(): string
    {
        $path = '';
        if ($this->getColumnValue($this->getParentIdColumn()) && $this->parent) {
            $path = $this->parent->getColumnValue($this->getPathColumn()) . $this->getPathSeparator();
        }
        return $path . $this->generateNodePath();
    }

    /**
     * Generate the path for current node
     *
     * If performance matters, we may
     *
     * 1. covert to larger base
     * 2. run a script to rebuild a shorter path like "0,0", "0,1"
     *
     * @return string
     */
    protected function generateNodePath(): string
    {
        return base_convert($this->getColumnValue($this->getPrimaryKey()) ?: Snowflake::next(), 10, 36);
    }

    /**
     * Generate the level base on the parent level
     *
     * @return int
     */
    protected function generateTreeLevel(): int
    {
        if ($this->getColumnValue($this->getParentIdColumn()) && $this->parent) {
            return $this->parent->getColumnValue($this->getLevelColumn()) + 1;
        }
        return 1;
    }
}
