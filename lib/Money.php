<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that handles money, following the implement of `currency.js`
 *
 * @link https://github.com/scurker/currency.js
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Money extends Base implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $intValue;

    /**
     * @var int
     */
    protected $precision = 2;

    /**
     * @var int
     * @internal
     */
    protected $precisionValue;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->precisionValue = 10 ** $this->precision;
    }

    /**
     * Create a new money instance
     *
     * @param string|int|float|static $value
     * @param array $options
     * @return static
     */
    public static function new($value, array $options = []): self
    {
        if ($value instanceof static) {
            // TODO 如果配置一样，可以直接使用
            $value = $value->getValue();
        }

        $money = new static($options);
        $money->intValue = $money->parse($value);
        return $money;
    }

    /**
     * @param string|int|float|static $value
     * @return $this
     */
    public function add($value): self
    {
        return static::new(($this->intValue + $this->parse($value)) / $this->precisionValue, $this->getOptions());
    }

    /**
     * @param string|int|float|static $value
     * @return $this
     */
    public function sub($value): self
    {
        return static::new(($this->intValue - $this->parse($value)) / $this->precisionValue, $this->getOptions());
    }

    /**
     * @param string|int|float|static $value
     * @return $this
     */
    public function mul($value): self
    {
        $value = $this->parseValue($value);
        return static::new($this->intValue * $value / $this->precisionValue, $this->getOptions());
    }

    /**
     * @param string|int|float|static $value
     * @return $this
     */
    public function div($value): self
    {
        $value = $this->parseValue($value);
        return static::new($this->intValue / ($value * $this->precisionValue), $this->getOptions());
    }

    /**
     * Returns the parsed value, may be a float or int
     *
     * @return float|int
     */
    public function getValue()
    {
        return $this->intValue / $this->precisionValue;
    }

    /**
     * @return int
     */
    public function getIntValue(): int
    {
        return $this->intValue;
    }

    /**
     * @return int|float
     */
    public function toNumber()
    {
        $value = $this->getValue();
        return (int) $value == $value ? (int) $value : $value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return (int) $this->getValue();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    /**
     * @return int
     */
    public function getCents(): int
    {
        return $this->intValue % $this->precisionValue;
    }

    /**
     * Convert value to become negative
     *
     * @return $this
     */
    public function negative(): self
    {
        if ($this->intValue > 0) {
            $this->intValue = -$this->intValue;
        }
        return $this;
    }

    /**
     * Check if the value is zero
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return 0.0 === (float) $this->intValue;
    }

    /**
     * Check if the value is greater than zero
     *
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->intValue > 0;
    }

    /**
     * Check if the value is lesser than zero
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->intValue < 0;
    }

    protected function parse($value, $options = [], $round = true)
    {
        $value = $this->parseValue($value);

        if (!$options) {
            $options = $this->getOptions();
        }

        $value *= $options['precisionValue'];

        if ($round) {
            // Act like JS Math.round
            $value = round($value, 0, $value > 0 ? \PHP_ROUND_HALF_UP : \PHP_ROUND_HALF_DOWN);
        }

        return $value;
    }

    /**
     * @param string|int|float|static $value
     * @return float|int
     */
    protected function parseValue($value)
    {
        return $value instanceof static ? $value->getValue() : (float) $value;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'precision' => $this->precision,
            'precisionValue' => $this->precisionValue,
        ];
    }
}
