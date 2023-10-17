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
     * Singular inflector rules
     *
     * @var array
     */
    protected $singularRules = [
        '/(o|x|ch|ss|sh)es$/i' => '\1', // heroes, potatoes, tomatoes
        '/([^aeiouy]|qu)ies$/i' => '\1y', // histories
        '/s$/i' => '',
    ];

    /**
     * The plural to singular array
     *
     * @var array
     */
    protected $singulars = [
        'aliases' => 'alias',
        'analyses' => 'analysis',
        'buses' => 'bus',
        'children' => 'child',
        'cookies' => 'cookie',
        'criteria' => 'criterion',
        'data' => 'datum',
        'lives' => 'life',
        'matrices' => 'matrix',
        'men' => 'man',
        'menus' => 'menu',
        'monies' => 'money',
        'news' => 'news',
        'people' => 'person',
        'quizzes' => 'quiz',
    ];

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
     * Returns a word in singular form.
     *
     * The implementation is borrowed from Doctrine Inflector
     *
     * @param string $word
     * @return string
     * @link https://github.com/doctrine/inflector
     * @svc
     */
    protected function singularize($word)
    {
        if (isset($this->singulars[$word])) {
            return $this->singulars[$word];
        }

        foreach ($this->singularRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                $this->singulars[$word] = preg_replace($rule, $replacement, $word);
                return $this->singulars[$word];
            }
        }

        $this->singulars[$word] = $word;
        return $word;
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
     * Truncate a string to specified length and append
     *
     * @param string|null $str
     * @param int $length
     * @param string $ellipsis
     * @return string|null
     * @svc
     */
    protected function truncate(?string $str, int $length, string $ellipsis = '…'): ?string
    {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length - mb_strlen($ellipsis)) . $ellipsis;
        }
        return $str;
    }

    /**
     * Truncate a string to specified length
     *
     * @param string|null $str
     * @param int $length
     * @return string|null
     * @svc
     */
    protected function cut(?string $str, int $length): ?string
    {
        return $this->truncate($str, $length, '');
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
