<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is in specified array
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class In extends BaseValidator
{
    protected $notInMessage = '%name% must be in %array%';

    protected $negativeMessage = '%name% must not be in %array%';

    protected $strict = false;

    protected $array = array();

    /**
     * {@inheritdoc}
     */
    public function __invoke($input, $array = array(), $strict = null)
    {
        if ($array) {
            if ($array instanceof \ArrayObject) {
                $array = $array->getArrayCopy();
            } elseif (!is_array($array)) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected argument of type array or \ArrayObject, "%s" given',
                    is_object($array) ? get_class($array) : gettype($array)
                ));
            }
            $this->storeOption('array', $array);
        }

        is_bool($strict) && $this->storeOption('strict', $strict);

        return $this->isValid($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!in_array($input, $this->array, $this->strict)) {
            $this->addError('notIn');
            return false;
        }

        return true;
    }
}
