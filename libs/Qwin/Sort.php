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
 * Sort
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-02-17 23:48:41
 */
class Qwin_Sort extends Qwin_Widget
{
    /**
     * Sort two-dimensional array like SQL ORDER BY clause
     *
     * @param array $array the two-dimensional array
     * @param string $key the array to be sort
     * @param int $type sort in ascending or descending order
     * @return array
     */
    public function __invoke(array $array, $key = 'order', $type = SORT_ASC)
    {
        if (!$array) {
            return $array;
        }
        $array2 = array();
        foreach ($array as $k => $v) {
            $array2[$k] = $v[$key];
        }
        array_multisort($array2, $type, $array);
        return $array;
    }
}
