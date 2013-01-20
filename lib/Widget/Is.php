<?php

/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Validator\Validator;

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
        
        return $rule($data, $options);
    }

    /**
     * Validate data by given rules
     *
     * @param array $options
     * @param array|null $data
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __invoke($rule = null, $data = null, $options = array())
    {
        switch (true) {
            // (function(){}, $data)
            case $rule instanceof \Closure :
                return $this->validateOne('callback', $data, $rule);
            // ($rule, $data, $options)
            case is_string($rule) :
                return $this->validateOne($rule, $data, $options);
            // ($rule, $data)
            case is_array($rule):
                $this->validator = new Validator;
                return $this->validator->__invoke(array(
                    'rules' => array(
                        'one' => $rule,
                    ),
                    'data' => array(
                        'one' => $data,
                    )
                ));

            default:
                throw new \InvalidArgumentException('Parameter 1 shoud be string, array or closure');
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
