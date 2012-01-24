<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * Marker
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-1-12 13:39:05
 * @todo        custom benchmark
 */
class Qwin_Marker extends Qwin_Widget
{
    protected $_index = 0;

    protected $_data = array();

    public $options = array(
        'auto' => true,
        'display' => null,
    );

    public function call($name = null)
    {
        !$name && $name = ++$this->_index;

        $times = explode(" ", microtime());
        $this->_data[$name] = $times[1] . substr($times[0], 1);

        return $this->invoker;
    }

    public function getMarkers()
    {
        return $this->_data;
    }

    public function display($print = true)
    {
        $code = '<table cellpadding="3" cellspacing="1" border="1">'
              . '<tr>'
              . '<th>Marker</th>'
              . '<th>Time</th>'
              . '<th>Elapsed Time</th>'
              . '</tr>';
        foreach ($this->_data as $name => $time) {
            if (isset($preTime)) {
                $elapsedTime = bcsub($time, $preTime, 8);
            } else {
                $elapsedTime = '-';
            }
            $preTime = $time;

            $code .= '<tr>'
                   . '<th>' . $name . '</th>'
                   . '<td>' . $time . '</td>'
                   . '<td>' . $elapsedTime . '</td>'
                   . '</tr>';
        }
        $code .= '</table>';

        if ($print) {
            echo $code;
        } else {
            return $print;
        }
    }
}