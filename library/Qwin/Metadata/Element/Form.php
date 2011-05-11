<?php
/**
 * Form
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
 * @since       2011-5-11 0:31:52
 */

class Qwin_Metadata_Element_Form extends Qwin_Metadata_Element_Driver
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'enabled' => true,
        '_type' => 'text',
        '_resource' => null,
        '_value' => '',
        'name' => null,
//        '_resourceGetter' => null,
//        '_resourceFormFile' => null,
//        '_widget' => array(),
//        'id' => null,
//        'class' => null,
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
        if (array_key_exists('*', $data)) {
            $this->_defaults = $this->_defaults + (array)$data['*'];
            unset($data['*']);
        }
        $data = $this->_mergeAsArray($data, $options);
        $this->exchangeArray($data + $this->getArrayCopy());
        return $this;
    }
}