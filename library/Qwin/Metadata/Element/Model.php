<?php
/**
 * Model
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
 * @since       2010-7-27 12:37:11
 */

class Qwin_Metadata_Element_Model extends Qwin_Metadata_Element_Abstract
{
    protected $_sampleData = array(
        'alias' => null,
        'metadata' => null,
        'relation' => 'hasOne',
        'local' => 'id',
        'foreign' => 'id',
        'type' => 'db',
        'enabled' => true,
        'fieldMap' => array(),
        'list'  => array(),
        'asc' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
        ),
    );
    
    public function format()
    {
        foreach ($this->_data as $key => $row) {
            $this->_data[$key] = $this->_format($row, $key);
        }
        return $this;
    }
}
