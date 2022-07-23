<?php

namespace Wei\Db;

use Wei\BaseCache;
use Wei\TagCache;

/**
 * Add cache functions to the query builder
 *
 * @mixin \TagCacheMixin
 * @internal Expected to be used only by QueryBuilder and ModelTrait
 */
trait QueryBuilderCacheTrait
{
    /**
     * The default cache time
     *
     * @var int
     */
    protected $defaultCacheTime = 60;

    /**
     * The specified cache time
     *
     * @var int|null
     */
    protected $cacheTime;

    /**
     * The key name of cache
     *
     * @var string|null
     */
    protected $cacheKey;

    /**
     * The cache tags
     *
     * @var array|true|null
     */
    protected $cacheTags;

    /**
     * Set the key name of cache
     *
     * @param string|null $cacheKey
     * @return $this
     */
    public function setCacheKey(?string $cacheKey): self
    {
        $this->cacheKey = $cacheKey;
        return $this;
    }

    /**
     * Generate cache key form query and params
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey ?: md5(
            $this->getDb()->getDbname() . $this->getSql()
            . serialize($this->queryParams) . serialize($this->queryParamTypes)
        );
    }

    /**
     * Set or remove cache time for the query
     *
     * @param int|null $seconds
     * @return $this
     * @svc
     */
    protected function setCacheTime(?int $seconds): self
    {
        $this->cacheTime = $seconds;
        return $this;
    }

    /**
     * Returns the expire seconds of cache
     */
    public function getCacheTime(): int
    {
        return null === $this->cacheTime ? $this->defaultCacheTime : $this->cacheTime;
    }

    /**
     * Set or remove the tags of cache
     *
     * @param array|true|null $cacheTags
     * @return $this
     */
    public function setCacheTags($cacheTags = true): self
    {
        $this->cacheTags = $cacheTags;
        return $this;
    }

    /**
     * Returns the tags of cache
     */
    public function getCacheTags(): ?array
    {
        if (true === $this->cacheTags) {
            $cacheTags[] = $this->getTable();
            foreach ($this->queryParts['join'] as $join) {
                $cacheTags[] = $join['table'];
            }
            return $cacheTags;
        }
        return $this->cacheTags;
    }

    /**
     * Clear cache that tagged with current table name
     */
    public function clearTagCache(): self
    {
        if (!$this->cacheTags) {
            $this->setCacheTags();
        }

        $this->tagCache($this->getCacheTags())->clear();
        return $this;
    }

    /**
     * Indicates whether has configured cache key or tags
     */
    protected function hasCacheConfig(): bool
    {
        return $this->cacheKey || $this->cacheTags;
    }

    /**
     * Returns the cache service
     *
     * @return TagCache|BaseCache
     */
    protected function getCache(): BaseCache
    {
        if ($this->cacheTags) {
            return $this->tagCache($this->getCacheTags());
        } else {
            return $this->cache;
        }
    }
}
