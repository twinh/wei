<?php
/**
 * Widgetable
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2012-01-11
 */

namespace Qwin;

interface Widgetable
{
    public function __get($name);

    public function __call($name, $args);

    //public function __invoke();
}