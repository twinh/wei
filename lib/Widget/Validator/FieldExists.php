<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the validate fields data is exists
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class FieldExists extends AbstractValidator
{
    protected $tooFewMessage = '%name% must contain at least %min% item(s)';

    protected $tooManyMessage = '%name% must contain no more than %max% items';

    protected $fields = array();

    /**
     * How many field should exist at least
     *
     * @var int
     */
    protected $min;

    /**
     * How many field should exist at most
     *
     * @var int
     */
    protected $max;

    protected $count;

    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        $this->count = 0;
        foreach ($this->fields as $field) {
            if ($this->validator->getFieldData($field)) {
                $this->count++;
            }
        }

        if (!is_null($this->min) && $this->count < $this->min) {
            $this->addError('tooFew');
            return false;
        }

        if (!is_null($this->max) && $this->count > $this->max) {
            $this->addError('tooMany');
            return false;
        }

        return true;
    }
}