<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Ignore the remaining rules of current field if input value is empty string or null
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @internal    This is not a really validator, may be change in the future
 */
final class IsAllowEmpty extends BaseValidator
{
    protected $defaultEmptyValues = ['', null];

    protected $typeEmptyValues = [
        'string' => [false],
        'float' => [false],
        'int' => [false],
        'bool' => [],
        'array' => [[]],
    ];

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (in_array($input, $this->getEmptyValues(), true)) {
            $this->validator->skipNextRules();
        }
        return true;
    }

    /**
     * @experimental
     */
    protected function getEmptyValues()
    {
        if (!$this->validator) {
            return $this->defaultEmptyValues;
        }

        // Get all types defined in rules
        $types = [];
        foreach ($this->validator->getFieldRules($this->validator->getCurrentField()) as $rule => $options) {
            if (0 === stripos($rule, 'not')) {
                $rule = substr($rule, 3);
            }

            $class = $this->wei->getClass('is' . ucfirst($rule));
            $type = $class::VALID_TYPE;
            foreach ((array) $type as $name) {
                $types[$name] = true;
            }
        }

        $types = array_filter(array_keys($types));
        if (!$types) {
            return $this->defaultEmptyValues;
        }

        // Get common empty values in types
        $values = [];
        foreach ($types as $type) {
            if (!$values) {
                $values = $this->typeEmptyValues[$type];
            } else {
                $values = array_intersect($values, $this->typeEmptyValues[$type]);
            }
        }
        return array_merge($values, $this->defaultEmptyValues);
    }
}
