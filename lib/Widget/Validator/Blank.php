<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is blank
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Blank extends AbstractValidator
{
    protected $blankMessage = '%name% must be blank';

    protected $negativeMessage = '%name% must not be blank';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if ($this->isString($input)) {
            $result = '' === trim($input);
        } else {
            $result = !empty($input);
        }

        if (!$result) {
            $this->addError('blank');
        }

        return $result;
    }
}
