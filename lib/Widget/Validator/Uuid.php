<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid UUID(v4)
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Uuid extends Regex
{
    protected $patternMessage = '%name% must be valid UUID';

    protected $negativeMessage = '%name% must not be valid UUID';

    protected $pattern = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';
}
