<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\View;

use Widget\AbstractWidget;

/**
 * The base class for view widget
 *
 * @author      Twin Huang <twinh@yahoo.cn>
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
