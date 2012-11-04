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

namespace Qwin;

/**
 * Marker
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-01-12 13:39:05
 * @todo        add index column
 * @todo        add index total row
 * @todo        add memory column
 */
class Marker extends WidgetProvider
{
    /**
     * Maker index
     *
     * @var int
     */
    protected $_index = 0;

    /**
     * Markers data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Options
     *
     * @var array
     */
    public $options = array(
        'auto' => true,
        'display' => null,
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        if ($this->options['auto']) {
            $this->__invoke('Start');
        }
    }

    /**
     * Set a marker
     *
     * @param  string      $name marker's name
     * @return Qwin_Widget
     */
    public function __invoke($name = null)
    {
        // TODO add memory
        //var_dump(memory_get_usage());
        !$name && $name = ++$this->_index;

        $times = explode(' ', microtime());
        $this->_data[$name] = $times[1] . substr($times[0], 1);

        return $this;
    }

    /**
     * Get markers data
     *
     * @return array
     */
    public function getMarkers()
    {
        return $this->_data;
    }

    /**
     * Display profiling data
     *
     * @param  string $print
     * @return mixed
     */
    public function display($print = true)
    {
        if (is_callable($this->options['display'])) {
            return $this->callback($this->options['display'], array(
                $this->_data, $print, $this->_index
            ));
        }

        reset($this->_data);
        $start = current($this->_data);
        $total = bcsub(end($this->_data), $start, 8);

        $code = '<table cellpadding="3" cellspacing="1" border="1">'
              . '<tr>'
              . '<th>Marker</th>'
              . '<th>Time</th>'
              . '<th>Elapsed Time</th>'
              . '<th>%</th>'
              . '</tr>';
        foreach ($this->_data as $name => $time) {
            if (isset($preTime)) {
                $elapsedTime = bcsub($time, $preTime, 8);
                $percentage = bcmul(bcdiv($elapsedTime, $total, 4), 100, 2) . '%';
            } else {
                $elapsedTime = '-';
                $percentage = '-';
            }
            $preTime = $time;

            $code .= '<tr>'
                   . '<th>' . $name . '</th>'
                   . '<td>' . $time . '</td>'
                   . '<td>' . $elapsedTime . '</td>'
                   . '<td>' . $percentage . '</td>'
                   . '</tr>';
        }
        $code .= '</table>';

        if ($print) {
            echo $code;

            return $this;
        } else {
            return $code;
        }
    }
}
