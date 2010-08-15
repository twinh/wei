<?php
/**
 * Table
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
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
 * @package     Qwin
 * @subpackage  Trex
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-06 20:40:08
 */

class Qwin_Trex_View_Table extends Qwin_Trex_View
{
    public function display()
    {
        $data = $this->data;
        echo '<style type="text/css">
                table {border-collapse: collapse;border-spacing: 0;}
                td{white-space: nowrap;}
              </style>';
        echo '<table cellpadding="4" cellspacing="4" width="100%" border="1">';
        if(isset($data[0]))
        {
            echo '<tr>';
            foreach($data[0] as $key => $value)
            {
                echo '<th>' . $key . '</th>';
            }
            echo '</tr>';
        } else {
            echo '<tr><td>There is no data to display.</td></tr>';
        }
        foreach($data as $key => $row)
        {
            echo '<tr>';
            foreach($row as $key => $value)
            {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
