<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Validator;

/**
 * Check if the input is valid Chinese postcode
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class PostcodeCn extends Regex
{
    protected $patternMessage = '%name% must be six length of digit';

    protected $negativeMessage = '%name% must not be postcode';

    protected $pattern = '/^[1-9][\d]{5}$/';
}
