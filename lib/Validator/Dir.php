<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei\Validator;

/**
 * Check if the input is existing directory
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Dir extends BaseValidator
{
    protected $notFoundMessage = '%name% must be an existing directory';

    protected $negativeMessage = '%name% must be a non-existing directory';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        if (!$this->isString($input)) {
            $this->addError('notString');
            return false;
        }

        if (!is_dir($input) && !stream_resolve_include_path($input)) {
            $this->addError('notFound');
            return false;
        }

        return true;
    }
}
