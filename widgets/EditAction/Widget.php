<?php
/**
 * Update
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
 * @package     Widget
 * @subpackage  EditAction
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: Edit.php 556 2011-04-17 13:32:39Z itwinh@gmail.com $
 * @since       2010-10-11 11:55:35
 */

class EditAction_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'meta'          => null,
        'db'            => 'db',
        'validation'    => 'validation',
        'id'            => null,
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
            // $this->_log('xxx');
            //throw new Qwin_Widget_Exception('ERR_VALIDATION_META_NOT_FOUND');
        }

        // 如果设置了编号值,则从编号值获取记录,否则从设置的数据中获取
        $id = isset($options['id']) ? $options['id'] : (
            isset($options['data'][$db['id']]) ? $options['data'][$db['id']] : null
        );

        // 编号参数为空,无法获取记录的值
        if (empty($id)) {
            return $options['display'] ? $this->_view->alert($this->_lang['MSG_NO_RECORD']) : array(
                'result'    => false,
                'message'   => $lang['MSG_NO_RECORD'],
            );
        }
        
        // 从数据库取出记录
        $dbData = Query_Widget::getByMeta($db)
            //->leftJoinByType(array('db'))
            ->where($db['id'] . ' = ?', $id)
            ->fetchOne();
        
        //  记录不存在,展示错误视图
        if (false === $dbData) {
            return $options['display'] ? $this->_view->alert($lang['MSG_NO_RECORD']) : array(
                'result'    => false,
                'message'   => $lang['MSG_NO_RECORD'],
            );
        }
        //$data = $dbData->toArray();

        // 还原只读项的值
        $data = $options['data'];
        foreach ($db['fields'] as $name => $field) {
            if (!array_key_exists($name, $data) && $field['dbField']) {
                $data[$name] = $dbData[$name];
            } elseif ($field['readonly']) {
                $data[$name] = $dbData[$name];
            }
        }
        
        // 验证数据
        if ($validation && !$this->_validator->valid($validation, $options['data'])) {
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

        // 还原只读值 TODO 重复
        foreach ($db['fields'] as $name => $field) {
            if ($field['readonly']) {
                $data[$name] = $dbData[$name];
            }
        }

        // 填充并保存数据
        $dbData->fromArray($data);
        $dbData->save();
        
        // 展示视图
        $options['url'] ? $options['url'] : $this->_url->url($meta['module']['url'] , 'index');
        return $options['display'] ? $this->_view->success($this->_lang['MSG_SUCCEEDED'], $options['url'])
                : array(
                    'result' => true,
                    'data' => get_defined_vars(),
                );
    }
}
