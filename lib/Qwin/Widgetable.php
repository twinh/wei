<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * The infterce for all widget
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
interface Widgetable
{
    public function __get($name);

    public function __call($name, $args);

    //public function __invoke();
}
