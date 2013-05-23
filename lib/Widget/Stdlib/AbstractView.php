<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Stdlib;

use Widget\AbstractWidget;

/**
 * The base class for view widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
abstract class AbstractView extends AbstractWidget implements ViewInterface
{
    /**
     * Default template file extension
     *
     * @var string
     */
    protected $extension = '.php';

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
