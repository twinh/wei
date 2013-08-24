<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is existing directory
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Dir extends AbstractValidator
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

        $dir = stream_resolve_include_path($input);
        if (!$dir || !is_dir($dir)) {
            $this->addError('notFound');
            return false;
        }

        return true;
    }
}
