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
     * The singular inflector rules
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
        'goods' => 'goods',
        'people' => 'person',
        'quizzes' => 'quiz',
    ];

    /**
     * The plural inflector rules
     *
     * @var array
     */
    protected $pluralRules = [
        '/(s)tatus$/i' => '\1\2tatuses',
        '/(quiz)$/i' => '\1zes',
        '/^(ox)$/i' => '\1\2en',
        '/([m|l])ouse$/i' => '\1ice',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive|gulf)$/i' => '\1s',
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '\1a',
        '/(c)riterion$/i' => '\1riteria',
        '/(p)erson$/i' => '\1eople',
        '/(m)an$/i' => '\1en',
        '/(c)hild$/i' => '\1hildren',
        '/(f)oot$/i' => '\1eet',
        '/(buffal|her|potat|tomat|volcan)o$/i' => '\1\2oes',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
        '/us$/i' => 'uses',
        '/(alias)$/i' => '\1es',
        '/(analys|ax|cris|test|thes)is$/i' => '\1es',
        '/s$/' => 's',
        '/^$/' => '',
        '/$/' => 's',
    ];

    /**
     * The singular to plural array
     *
     * @var array
     */
    protected $plurals = [
        'deer' => 'deer',
        'echo' => 'echoes',
        'fish' => 'fish',
        'sheep' => 'sheep',
        'money' => 'monies',
        'human' => 'humans',
        'information' => 'information',
        'cafe' => 'cafes',
    ];

    /**
     *  Returns a word in plural form
     *
     * @param string $word
     * @return string
     * @link https://github.com/doctrine/inflector
     * @svc
     */
    protected function pluralize(string $word): string
    {
        if (isset($this->plurals[$word])) {
            return $this->plurals[$word];
        }

        foreach ($this->pluralRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                $this->plurals[$word] = preg_replace($rule, $replacement, $word);
                return $this->plurals[$word];
            }
        }

        $this->plurals[$word] = $word;
        return $word;
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
     * @deprecated
     * @phpstan-ignore-next-line class not found
     */
    public function getInflector(): Inflector
    {
        if (!$this->inflector) {
            /** @phpstan-ignore-next-line class not found */
            $this->inflector = InflectorFactory::create()->build();
        }
        return $this->inflector;
    }
}
