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
 * JQGridJson
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-02-26 00:17:06
 */
class Qwin_JqGridJson extends Qwin_Widget
{
    public $options = array(
        'page' => 1,
        'rows' => 1,
        'total' => 1,
        'data' => array(),
        'columns' => array(),
    );

    public function __invoke(array $options = array())
    {
        $options = $options + $this->options;

        extract($options);

        $json = array();
        foreach ($data as $row) {
            $cell = array();
            foreach ($columns as $column) {
                $cell[] = isset($row[$column]) ? $row[$column] : null;
            }
            $json[] = array(
                'id' => $row['id'],
                'cell' => $cell,
            );
        }

        return json_encode(array(
            'page'      => $page,
            'total'     => ceil($total / $rows),
            'records'   => $total,
            'rows'      => $json,
        ));
    }
}