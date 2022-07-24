<?php

namespace Wei;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;

/**
 * The string util service
 */
class Str extends Base
{
    /**
     * @var Inflector|null
     */
    protected $inflector;

    /**
     * The cache of snake strings
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel strings
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     *  Returns a word in plural form
     *
     * @param string $word
     * @return string
     * @experimental will remove doctrine dependency
     * @svc
     */
    protected function pluralize(string $word): string
    {
        return $this->getInflector()->pluralize($word);
    }

    /**
     * Returns a word in singular form
     *
     * @param string $word
     * @return string
     * @experimental will remove doctrine dependency
     * @svc
     */
    protected function singularize(string $word): string
    {
        return $this->getInflector()->singularize($word);
    }

    /**
     * Convert a input to snake case
     *
     * @param string $input
     * @param string $delimiter
     * @return string
     * @svc
     */
    protected function snake(string $input, string $delimiter = '_'): string
    {
        if (isset(static::$snakeCache[$input][$delimiter])) {
            return static::$snakeCache[$input][$delimiter];
        }

        $value = $input;
        if (!ctype_lower($input)) {
            $value = strtolower(preg_replace('/(?<!^)[A-Z]/', $delimiter . '$0', $input));
        }

        return static::$snakeCache[$input][$delimiter] = $value;
    }

    /**
     * Convert a input to camel case
     *
     * @param string $input
     * @return string
     * @svc
     */
    protected function camel(string $input): string
    {
        if (isset(static::$camelCache[$input])) {
            return static::$camelCache[$input];
        }

        return static::$camelCache[$input] = lcfirst(str_replace(' ', '', ucwords(strtr($input, '_-', '  '))));
    }

    /**
     * Convert a input to dash case
     *
     * @param string $input
     * @return string
     * @svc
     */
    protected function dash(string $input): string
    {
        return $this->snake($input, '-');
    }

    /**
     * Get the inflector instance.
     *
     * @return Inflector
     */
    public function getInflector(): Inflector
    {
        if (!$this->inflector) {
            $this->inflector = InflectorFactory::create()->build();
        }
        return $this->inflector;
    }
}
