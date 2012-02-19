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
 * ReplaceFirst
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-02-15 15:35:48
 */
class Qwin_ReplaceFirst extends Qwin_Widget
{
    /**
     * Replace the first string
     *
     * @param string $source the input string
     * @param string $search the string to search
     * @param string $replace the string to replace
     * @return string
     */
    public function __invoke($source, $search, $replace)
    {
        $pos = strpos($source, $search);
        if ($pos === false) {
            return $source;
        }
        return substr_replace($source, $replace, $pos, strlen($search));
    }
}
