<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A wrapper for PHP Error
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        http://phperror.net/
 */
class PhpError extends Base
{
    /**
     * @var \php_error\ErrorHandler
     */
    protected $errorHandler;

    /**
     * Constructor
     *
     * @param array $options
     * @link https://github.com/JosephLenton/PHP-Error/wiki/Options
     */
    public function __construct(array $options = array())
    {
        parent::__construct(array(
            'wei' => isset($options['wei']) ? $options['wei'] : null
        ));

        // Avoid exception "Unknown option given XXX"
        unset($options['wei'], $options['namespace']);

        $this->errorHandler = \php_error\reportErrors($options);
    }

    /**
     * Returns PHP Error ErrorHandler object
     *
     * @return \php_error\ErrorHandler
     */
    public function __invoke()
    {
        return $this->errorHandler;
    }
}
