<?php
/**
 * List
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
 * @since       2011-05-07 09:00:45
 */

class Qwin_Metadata_Element_List extends Qwin_Metadata_Element_Driver
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'enabled' => true,
        'hidden' => false,
        'link' => false,
        //'width' => null,
        //'sanitiser' => array(),
    );

    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Metadata_Element_Model 当前对象
     */
    public function merge($data, array $options = array())
    {
        // 处理默认选项
        if (array_key_exists('*', $data['fields'])) {
            $this->_defaults = $this->_defaults + (array)$data['fields']['*'];
            unset($data['fields']['*']);
        }
        foreach ($data['fields'] as &$field) {
            $field = $field + $this->_defaults;
        }
        $this->exchangeArray($data);
        return $this;
    }
    
    public function setLayout()
    {
        
    }
}