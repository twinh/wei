<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

namespace Qwin;

/**
 * Dump
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-10-05 12:04:07
 */
class Dump extends WidgetProvider
{
    public $options = array(
        'exit' => true,
    );

    /**
     * Dumps information about a variable, by default, dump the object invoker
     *
     * @return Qwin_Dump
     */
    public function __invoke()
    {
        $args = func_get_args();
        if (empty($args)) {
            var_dump($this->__invoker);
        } else {
            call_user_func_array('var_dump', $args);
        }

        if ($this->options['exit']) {
            // @codeCoverageIgnoreStart
            exit();
            // @codeCoverageIgnoreEnd
        }

        return $this;
    }
}
