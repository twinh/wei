<?php
/**
 * EditPassword
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
 * @since       2011-04-20 03:17:06
 */

class Com_Member_Widget_EditPassword extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'display'   => true,
        'data'      => array(),
        'url'       => null,
    );

    public function execute($options)
    {
        $options = $options + $this->_options;
        $meta = Qwin_Metadata::getInstance()->get('Com_Member_PasswordMetadata');
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = isset($options['data'][$primaryKey]) ? $options['data'][$primaryKey] : null;

        // 从模型获取数据
        $query = $meta->getQuery(null, array('type' => array('db', 'view')));
        $dbData = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();

        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($options['display']) {
                return Qwin::call('-view')->alert($result['message']);
            } else {
                return $result;
            }
        }

        $data = $options['data'];
        foreach ($meta['field'] as $name => $field) {
            if (!isset($data[$name])) {
                $data[$name] = null;
            }
        }

        // 加载验证微件,验证数据
        $validator = Qwin::call('-widget')->get('Validator');
        if (!$validator->valid($data, $meta)) {
            $result = array(
                'result' => false,
                'message' => $validator->getInvalidMessage(),
            );
            if ($options['display']) {
                return Qwin::call('-view')->alert($result['message']['title'], null, $result['message']['content']);
            } else {
                return $result;
            }
        }

        $dbData['password'] = md5($data['password']);
        $dbData->save();

        // 展示视图
        if ($options['display']) {
            if (!$options['url']) {
                $options['url'] = Qwin::call('-url')->url('com/member' , 'index');
            }
            return Qwin::call('-view')->success(Qwin::call('-lang')->t('MSG_SUCCEEDED'), $options['url']);
        }
        return array(
            'result' => true,
            'data' => get_defined_vars(),
        );
    }
}