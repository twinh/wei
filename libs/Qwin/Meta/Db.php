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
 * @subpackage  Meta
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-27 18:13:16
 */

class Qwin_Meta_Db extends Qwin_Meta_Common
{
    /**
     * 查找属性的缓存数组
     * @var array
     */
    protected $_attrCache = array();

    /**
     * @var array $_defaults        默认选项
     *
     *      -- name                 名称
     *
     *      -- title                标题标识, 默认为 FLD_$fieldUppeName
     *
     *      -- description          域描述
     *
     *      -- order                排序
     *
     *      -- dbField              是否为数据库字段
     *
     *      -- dbQuery              是否允许数据库查询
     *
     *      -- urlQuery             是否允许Url查询
     *
     *      -- readonly             是否只读
     */
    protected $_fieldDefaults = array(
        'name'          => null,
        'title'         => null,
        'description'   => array(),
        'dbField'       => true,
        'dbQuery'       => true,
        'urlQuery'      => true,
        'readonly'      => false,
        // TODO 扩展
        //'type'          => 'string',
        //'length'        => null,
        //'default'       => null,
        //'unsigned'      => false,
        //'notnull'       => false,
        //'primary'       => false,
        //'fixed'         => false,
    );
    
    protected $_relationDefaults = array(
        'module'    => null,
        'alias'     => null,
        'meta'      => 'db',
        'relation'  => 'hasOne',
        'local'     => 'id',
        'foreign'   => 'id',
        'type'      => 'db',
        'fieldMap'  => array(), // ?是否仍需要
        'enabled'   => true,
    );
    
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'fields'    => array(),
        //'type'      => 'sql',
        'uid'       => 'db',
        'table'     => null,
        'id'        => 'id',
        'alias'     => null,
        'indexBy'   => null,
        'offset'    => 0,
        'limit'     => 10,
        'order'     => array(),
        'where'     => array(),
        'relations' => array(),
    );
    
    /**
     * 将数据格式化并加入
     *
     * @param array $data 数据
     * @param array $option 选项
     * @return Qwin_Meta_Field 当前对象
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

        foreach ($data['fields'] as $name => &$field) {
            !isset($field['name']) && $field['name'] = $name;
            //!isset($field['title']) && $field['title'] = 'FLD_' . strtoupper($name);
            $field = (array)$field + $this->_fieldDefaults;
        }
        
        foreach ($data['relations'] as &$relation) {
            $relation += $this->_relationDefaults;
        }
        
        $this->exchangeArray($data);
        return $this;
    }
}
