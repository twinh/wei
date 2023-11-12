<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2022 Twin Huang Copyright (c) 2008-2022 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Check if the input is existing directory
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class IsDir extends BaseValidator
{
    public const VALID_TYPE = 'string';

    protected $notFoundMessage = '%name% must be an existing directory';

    protected $negativeMessage = '%name% must be a non-existing directory';

    /**
     * {@inheritdoc}
     */
    protected function doValidate($input)
    {
        $input = $this->toString($input);
        if (null === $input) {
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
