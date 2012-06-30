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

/**
 * Flush
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-02-16 15:02:26
 */
class Qwin_Flush extends Qwin_Widget
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // how about other server
        apache_setenv('no-gzip', '1');

        ob_implicit_flush(true);

        // NOTE output here !!!
        echo str_pad('',4096);
    }

    /**
     * flush content to the browser
     *
     * @param string $content the content flushes to the browser
     * @param int $sleep the second to sleep
     * @return Qwin_Flush
     */
    public function __invoke($content, $sleep = 0)
    {
        $level = ob_get_level();
        if (0 == $level) {
            ob_start();
        } else {
            while (1 != ob_get_level()) {
                ob_end_flush();
            }
        }

        echo $content;

        ob_flush();

        $sleep && sleep($sleep);

        return $this;
    }
}
