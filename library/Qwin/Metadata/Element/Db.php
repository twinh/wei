<?php
/**
 * Db
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-27 18:13:16
 */

class Qwin_Metadata_Element_Db extends Qwin_Metadata_Element_Driver
{
    protected $_defaults = array(
        'type' => 'sql',
        'table' => null,
        'id' => 'id',
        'offset' => 0,
        'limit' => 10,
        'order' => array(),
        'where' => array(),
    );
 
    /**
     * 获取数据表名称,不包含前缀
     *
     * @return string 数据表名称
     */
    public function getTable()
    {
        return $this->_data['table'];
    }
}
