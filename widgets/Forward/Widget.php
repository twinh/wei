<?php
/**
 * Widget
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
 * @since       2011-04-12 12:04:35 v0.7.9
 */

class Forward_Widget extends Qwin_Widget_Abstract
{   
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'lang'      => true,
        'module'    => null,
        'action'    => 'view',
        'id'        => null,
        'forward'   => 'next',
        'key'       => 'date_modified',
        'display'   => true,
    );

    protected $_forwards = array(
        'next', 'prev',
    );

    public function execute($options)
    {
        $options = $this->_options = $options + $this->_defaults;

        $module = $options['module'];
        $meta = Com_Meta::getByModule($module);
        $query = Com_Meta::getQueryByModule($module);

        // 当比较键名不存在时，使用主键做比较
        $key = isset($meta['field'][$options['key']]) ? $options['key'] : $meta['db']['primaryKey'];

        $dbData = $query
            ->select($key)
            ->where($meta['db']['primaryKey'] . ' = ?', $options['id'])
            ->fetchOne();

        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($options['display']) {
                return $this->_View->alert($result['message']);
            } else {
                return $result;
            }
        }

        // 根据上下指向设置不同的参数
        if ('next' == $options['forward']) {
            $message = 'MSG_LAST_RECORD';
            $oper = '>';
            $order = 'ASC';
        } else {
            $message = 'MSG_FIRST_RECORD';
            $oper = '<';
            $order = 'DESC';
        }

        // 获取上一条或下一条记录的信息
        $dbData = $query
            ->select('id, ' . $key)
            ->where($key . ' ' . $oper . ' ?', $dbData[$key])
            ->orderBy($key . ' ASC')
            ->limit(1)
            ->fetchOne();

        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang[$message],
            );
            if ($options['display']) {
                return $this->_View->alert($result['message']);
            } else {
                return $result;
            }
        }

        $url = Qwin::call('-url')->url($module->toUrl(), $options['action'], array('id' => $dbData['id']));
        // 跳转到新的记录
        if ($options['display']) {
            return $this->_View->jump($url);
        } else {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
    }
}
