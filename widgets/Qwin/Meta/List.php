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

class Qwin_Meta_List extends Qwin_Meta_Common
{
    /**
     * 域默认选项
     * @var array
     */
    protected $_fieldDefaults = array(
        'enabled' => true,
        'hidden' => false,
        'link' => false,
        //'width' => null,
        //'sanitiser' => array(),
    );
    
    /**
     * 默认选项
     * @var array 
     */
    public $options = array(
        'fields' => array(),
        'layout' => array(),
        'db' => array(
            'order' => array(),
            'limit' => 10,
        ),
    );
    
    /**
     * 关联选项
     * 
     * @var array 
     * @todo 条件查询 criteria
     * @todo 排序查询 order
     * @todo 显示的类型
     *       1. field name
     *       2. format field name
     *       3. callback
     */
    protected $_relationDefaults = array(
        'module'    => null,
        'alias'     => null,
        'db'        => 'db',
        'field'     => 'id',
        'display'   => 'id',
        'criteria'  => array(),
        'order'     => array(),
        'loaded'    => false,
    );
    
    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $options 选项
     * @return Qwin_Meta_List 当前对象
     */
    public function merge($data, array $options = array())
    {
        $data = (array)$data + $this->_defaults;
        !is_array($data['fields']) && (array)$data['fields'];
        
        // 处理通配选项
        if (array_key_exists('*', $data['fields'])) {
            $this->_fieldDefaults = $this->_fieldDefaults + (array)$data['fields']['*'];
            unset($data['fields']['*']);
        }
        
        foreach ($data['fields'] as &$field) {
            $field = (array)$field + $this->_fieldDefaults;
            if (isset($field['_relation'])) {
                $field['_relation'] = (array)$field['_relation'] + $this->_relationDefaults;
            }
        }
        $this->exchangeArray($data);
        return $this;
    }
}
