<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Validator\Validator;
use InvalidArgumentException;
use Closure;

/**
 * Is
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Is extends WidgetProvider
{
    /**
     * The last validator object
     *
     * @var \Widget\Validator\Validator
     */
    public $validator;

    /**
     * @param string $rule
     * @param array|null $data
     */
    public function validateOne($rule, $data, $options = array())
    {
        if (!$class = $this->hasRule($rule)) {
            throw new Exception(sprintf('Rule "%s" not defined', $rule));
        }

        $rule = new $class(array(
            'widget' => $this->widget
        ));

        switch (count($options)) {
            case 0:
                return $rule($data);

            case 1:
                return $rule($data, $options[0]);

            case 2:
                return $rule($data, $options[0], $options[1]);

            default:
                array_unshift($data, $options);
                return call_user_func_array($rule, $options);
        }
    }

    /**
     * Validate data
     *
     * @param array $options
     * @param array|null $data
     * @return mixed
     * @throws \InvalidArgumentException
     * @example $this->is('rule', $data) Validate by one rule
     */
    public function __invoke($options = array(), $data = null)
    {
        switch (true) {
            case $options instanceof Closure :
                return $this->validateOne('callback', $data, array($options));

            case is_string($options) :
                $args = func_get_args();
                array_shift($args);
                return $this->validateOne($options, $data, $args);

            case is_array($options):
                $this->validator = new Validator;
                return $this->validator->__invoke($options);

            default:
                throw new InvalidArgumentException('Parameter 1 shoud be string, array or closure');
        }
    }

    /**
     * Check if the validate rule exists
     *
     * @param string $rule
     * @return string|false
     */
    public function hasRule($rule)
    {
        if (class_exists($class = '\Widget\Validator\Rule\\' . ucfirst($rule))) {
            return $class;
        } else {
            return false;
        }
    }
}
