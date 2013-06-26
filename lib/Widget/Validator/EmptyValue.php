<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is empty
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class EmptyValue extends AbstractValidator
{
    protected $emptyMessage = '%name% must be empty';

    protected $negativeMessage = '%name% must not be empty';

    /**
     * {@inheritdoc}
     */
    protected function validate($input)
    {
        if (!empty($input)) {
            $this->addError('empty');
            return false;
        }

        return true;
    }
}
