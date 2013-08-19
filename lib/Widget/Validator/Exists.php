<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is existing file or directory
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Exists extends AbstractValidator
{
    protected $notFoundMessage = '%name% must be an existing file or directory';

    protected $negativeMessage = '%name% must be a non-existing file or directory';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        $file = stream_resolve_include_path($input);
        if (!$file) {
            $this->addError('notFound');
            return false;
        }

        return true;
    }
}
