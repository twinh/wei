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

class Qwin_Meta_Form extends Qwin_Meta_Common
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'form' => array(
            '_type' => 'text',
            '_resource' => null,
            '_value' => '',
            'name' => null,
//            '_resourceGetter' => null,
//            '_resourceFormFile' => null,
//            '_widget' => array(),
//            'id' => null,
//            'class' => null,
        ),
//        'sanitiser' => array(
//            
//        ),
//        'validator' => array(
//            'rules' => array(
//                
//            ),
//            'messages' => array(
//                
//            ),
//        ),
    );
    
    /**
     * 表单元数据整体结构
     * @var type 
     */
    protected $_struct = array(
        'fields' => array(),
        'layout' => array(),
    );

    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Meta_Model 当前对象
     */
    public function merge($data, array $options = array())
    {
        // 初始化结构,保证数据完整性
        $data = (array)$data + $this->_struct;
        !is_array($data['fields']) && $data['fields'] = (array)$data['fields'];
        !is_array($data['layout']) && $data['layout'] = (array)$data['layout'];
        
        // 处理通配选项
        if (array_key_exists('*', $data['fields'])) {
            $this->_defaults = $this->_defaults + (array)$data['fields']['*'];
            unset($data['fields']['*']);
        }
        foreach ($data['fields'] as &$field) {
            $field = (array)$field + $this->_defaults;
        }
        $this->exchangeArray($data);
        return $this;

//        // 初始验证器和补全验证信息
//        if(isset($data['validator']) && !empty($data['validator']['rule'])) {
//            foreach ($data['validator']['rule'] as $key => $rule) {
//                if (!isset($data['validator']['message'][$key])) {
//                    $data['validator']['message'][$key] = 'VLD_' . strtoupper($key);
//                }
//            }
//        }
    }
}
