<?php
/**
 * Insert
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
 * @package     Widget
 * @subpackage  AddAction
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 22:31:44
 */

class AddAction_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'meta'          => null,
        'db'            => 'db',
        'validation'    => 'validation',
        'data'          => array(),
        'sanitise'  => array(
            'sanitiser'     => true,
            'sanitise'      => true,
            'action'        => 'db',
            'null'          => true,
        ),
        'display'       => true,
        'url'           => null,
    );

    public function render($options = null)
    {
        // 初始配置
        $options = (array)$options + $this->_options;
        
        // 检查元数据是否合法
        /* @var $meta Meta_Widget */
        $meta = $options['meta'];
        if (!Qwin_Meta::isValid($meta)) {
            throw new Qwin_Widget_Exception('ERR_META_ILLEGAL');
        }
        
        // 检查数据库元数据是否合法
        /* @var $db Qwin_Meta_Db */
        if (!($db = $meta->offsetLoad($options['db'], 'db'))) {
            throw new Qwin_Widget_Exception('ERR_DB_META_NOT_FOUND');
        }

        // 检查验证元数据是否合法
        /* @var $validation Qwin_Meta_Validation */
        if (!($validation = $meta->offsetLoad($options['validation'], 'validation'))) {
            throw new Qwin_Widget_Exception('ERR_VALIDATION_META_NOT_FOUND');
        }
        
        $data = &$options['data'];
        if (isset($data[$db['id']]) && !empty($data[$db['id']])) {
            // 从数据库取出记录
            $dbData = Query_Widget::getByMeta($db)
                    ->select($db['id'])
                    //->leftJoinByType(array('db'))
                    ->where($db['id'] . ' = ?', $data[$db['id']])
                    ->fetchOne();
        
            // 记录已经存在,加载错误视图
            if (false !== $dbData) {
                return $options['display'] ? $this->_view->alert($lang['MSG_RECORD_EXISTS']) : array(
                    'result'    => false,
                    'message'   => $lang['MSG_RECORD_EXISTS'],
                );
            }
        }
        
        // 验证数据
        if (!$this->_validator->valid($validation, $data)) {
            $message = $this->_validator->getInvalidMessage();
            return $options['display'] ? $this->_view->alert($message['title'], null, $message['content']) : array(
                'result'    => false,
                'message'   => $message,
            );
        }

        // 转换数据
        if ($options['sanitise']) {
            $data = $this->_sanitiser->sanitise($db, $data, $options['sanitise']);
        }

        // 保存关联模型的数据
        //$metaHelper->saveRelatedDbData($meta, $data, $query);

        $record = Record_Widget::getByModule($meta['module'], $options['db']);
        $record->fromArray($data);
        $record->save();
        
        // 展示视图
        $options['url'] ? $options['url'] : $this->_url->url($meta['module']['url'] , 'index');
        return $options['display'] ? $this->_view->success($this->_lang['MSG_SUCCEEDED'], $options['url'])
                : array(
                    'result' => true,
                    'data' => get_defined_vars(),
                );
    }

    /**
     * 取出添加操作入库的数据
     *
     * @param Qwin_Meta_Abstract $meta 元数据对象
     * @param array $post 原始数据,一般为$_POST
     * @return array 数据
     */
    /*protected function _filterData($meta, $post)
    {
        $result = array();
        foreach ($meta['field'] as $name => $field) {
            $result[$name] = isset($post[$name]) ? $post[$name] : null;
        }
        if (isset($result[$meta['db']['primaryKey']])) {
            $result[$meta['db']['primaryKey']] = null;
        }
        return $result;
    }*/
}
