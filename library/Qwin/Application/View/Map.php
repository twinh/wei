<?php
/**
 * Map
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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-08 2:19:23
 */

class Qwin_Application_View_Map extends Qwin_Application_View
{
    public function display()
    {
        $data = $this->data;

        echo '<style type="text/css">
                table {border-collapse: collapse;border-spacing: 0;}
                td{white-space: nowrap;}
              </style>';
        echo '<table cellpadding="4" cellspacing="4" width="100%" border="1">';
        foreach($data as $key => $value)
        {
            echo '<tr>',
                 '<td>' . $key . '</td>',
                 '<td>' . $value . '</td>',
                 '</tr>';
        }
        echo '</table>';
    }
}
