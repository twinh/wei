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
 * @version     $Id$
 * @since       2010-10-11 11:55:35
 */

class Qwin_EditAction extends Qwin_Widget
{
    /**
     * 默认选项
     * @var array
     */
    public $options = array(
        'record'        => null,
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
    
    public function call($options = null)
    {
        $this->option(&$options);
        $record = $options['record'];
        $pk = $record->options['id'];
        $data = $options['data'];

        // 如果设置了编号值,则从编号值获取记录,否则从设置的数据中获取
        $id = isset($options['id']) ? $options['id'] : (
            isset($options['data'][$pk]) ? $options['data'][$pk] : null
        );

        // 编号参数为空,无法获取记录的值
        if (empty($id)) {
            return $options['display'] ? $this->view->alert($this->lang['MSG_NO_RECORD']) : array(
                'result'    => false,
                'message'   => $this->lang['MSG_NO_RECORD'],
            );
        }
        
        // 从数据库取出记录
        $dbData = $this->query
            ->getByRecord($record)
            //->leftJoinByType(array('db'))
            ->where($pk . ' = ?', $id)
            ->fetchOne();
        
        //  记录不存在,展示错误视图
        if (false === $dbData) {
            return $options['display'] ? $this->view->alert($this->lang['MSG_NO_RECORD']) : array(
                'result'    => false,
                'message'   => $this->lang['MSG_NO_RECORD'],
            );
        }
        //$data = $dbData->toArray();

        // 还原只读项的值
//        $data = $options['data'];
//        foreach ($db['fields'] as $name => $field) {
//            if (!array_key_exists($name, $data) && $field['dbField']) {
//                $data[$name] = $dbData[$name];
//            } elseif ($field['readonly']) {
//                $data[$name] = $dbData[$name];
//            }
//        }
        
        // 验证数据
//        if ($validation && !$this->_validator->valid($validation, $options['data'])) {
//            $message = $this->_validator->getInvalidMessage();
//            return $options['display'] ? $this->_view->alert($message['title'], null, $message['content']) : array(
//                'result'    => false,
//                'message'   => $message,
//            );
//        }

        // 转换数据
//        if ($options['sanitise']) {
//            $data = $this->_sanitiser->sanitise($db, $data, $options['sanitise']);
//        }

        // 还原只读值 TODO 重复
//        foreach ($db['fields'] as $name => $field) {
//            if ($field['readonly']) {
//                $data[$name] = $dbData[$name];
//            }
//        }

        // 填充并保存数据
        $dbData->fromArray($data);
        $dbData->save();
        
        // 展示视图
        $options['url'] ? $options['url'] : $this->url->url($this->module() , 'index');
        return $options['display'] ? $this->view->success($this->lang['MSG_SUCCEEDED'], $options['url'])
                : array(
                    'result' => true,
                    'data' => get_defined_vars(),
                );
    }
}
