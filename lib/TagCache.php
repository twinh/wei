<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A cache service that support tagging
 *
 * @property \Wei\BaseCache $cache
 */
class TagCache extends BaseCache
{
    /**
     * The tag names
     *
     * @var array
     */
    protected $tags = [];

    /**
     * The generated tags namespace
     *
     * @var string
     */
    protected $tagsNamespace = '';

    /**
     * Manager: The instanced tagged cache objects
     *
     * @var array
     */
    protected $services = [];

    /**
     * Manager: Create a new cache service with tagging support
     *
     * @param string $tag
     * @param mixed $ignore1
     * @param mixed $ignore2
     * @return $this
     */
    public function __invoke($tag, $ignore1 = null, $ignore2 = null)
    {
        $tags = is_array($tag) ? $tag : func_get_args();
        sort($tags);
        $name = implode(':', $tags);

        if (!isset($this->services[$name])) {
            $this->services[$name] = new static([
                'wei' => $this->wei,
                'tags' => $tags,
            ]);
        }
        return $this->services[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->cache->get($this->getKey($key), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->cache->set($this->getKey($key), $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(string $key): bool
    {
        return $this->cache->delete($this->getKey($key));
    }

    /**
     * {@inheritdoc}
     */
    protected function has(string $key): bool
    {
        return $this->cache->has($this->getKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return $this->cache->add($this->getKey($key), $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        return $this->cache->replace($this->getKey($key), $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function incr($key, $offset = 1)
    {
        return $this->cache->incr($this->getKey($key), $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $data = [];
        foreach ($this->tags as $tag) {
            $data[$this->getTagKey($tag)] = $this->generateTagValue();
        }
        $this->cache->setMulti($data);
        $this->reload();
        return $this;
    }

    /**
     * Set tag names and reload tags namespace
     *
     * @param array $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        $this->reload();
        return $this;
    }

    /**
     * Reload tags namespace
     *
     * @return $this
     */
    public function reload()
    {
        $this->tagsNamespace = $this->getTagsNamespace();
        return $this;
    }

    /**
     * Returns the item cache key
     *
     * @param string $key
     * @return string
     */
    protected function getKey($key)
    {
        return $this->namespace . $this->tagsNamespace . ':' . $key;
    }

    /**
     * Returns tags namespace
     *
     * @return string
     */
    protected function getTagsNamespace()
    {
        return implode(':', $this->tags) . ':' . md5(implode(':', $this->getTagValues()));
    }

    /**
     * Returns tag values
     *
     * @return array
     */
    protected function getTagValues()
    {
        // Step1 Get tags value from cache
        $keys = array_map([$this, 'getTagKey'], $this->tags);
        $values = $this->cache->getMulti($keys);

        // Step2 Initialize new tags value
        $emptyKeys = array_diff($values, array_filter($values));
        if ($emptyKeys) {
            $newValues = $this->setTagValues($emptyKeys);
            $values = array_merge($values, $newValues);
        }

        return $values;
    }

    /**
     * Initializes tag values by cache keys
     *
     * @param array $keys
     * @return array
     */
    protected function setTagValues($keys)
    {
        $values = [];
        foreach ($keys as $key => $value) {
            $values[$key] = $this->generateTagValue();
        }
        $this->cache->setMulti($values);
        return $values;
    }

    /**
     * Returns the tag's cache key
     *
     * @param string $name
     * @return string
     */
    protected function getTagKey($name)
    {
        return $this->namespace . 'tag:' . $name;
    }

    /**
     * Generates a tag value
     *
     * @return string
     */
    protected function generateTagValue()
    {
        return strtr(uniqid('', true), ['.' => '']);
    }
}
