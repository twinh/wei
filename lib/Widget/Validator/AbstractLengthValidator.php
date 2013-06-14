<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

abstract class AbstractLengthValidator extends AbstractValidator
{
    protected $notDetectedMessage = '%name%\'s length could not be detected';

    /**
     * Return the input's length or false when could not detected
     *
     * @param string|array|\Countable $input
     * @return int|false
     */
    public function getLength($input)
    {
        if (is_scalar($input)) {
            $fn = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
            return $fn($input);
        } elseif (is_array($input) || $input instanceof \Countable) {
            return count($input);
        } else {
            return false;
        }
    }
}
