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
 * @since       2010-07-27 12:37:11
 * @todo        调整为关联元数据,而非模型,元数据
 * @todo        模型,元数据可自定义,而不通过应用目录配置获得
 */

class Qwin_Metadata_Element_Model extends Qwin_Metadata_Element_Driver
{
    protected $_default = array(
        'alias' => null,
        'metadata' => null,
        'relation' => 'hasOne',
        'local' => 'id',
        'foreign' => 'id',
        'type' => 'db',
        'enabled' => true,
        'fieldMap' => array(),
        'list'  => array(),
        'module' => null,
    );
    
    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $option 配置选项
     * @return Qwin_Metadata_Element_Model 当前对象
     */
    public function merge($data, array $option = array())
    {
        $data = $this->_mergeAsArray($data, $option);
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }
}
