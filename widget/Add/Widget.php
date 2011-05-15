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
 * @package     Com
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: Add.php 556 2011-04-17 13:32:39Z itwinh@gmail.com $
 * @since       2010-10-11 22:31:44
 */

class Add_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'data'      => array(),
        'display'   => true,
        'url'       => null,
    );

    /**
     * 根据配置,执行插入数据操作
     *
     * @param array $options 配置
     */
    public function execute(array $options = null)
    {
        // 初始配置
        $options = $options + $this->_options;

        /* @var $meta Com_Meta */
        $meta   = Com_Meta::getByModule($options['module']);
        $id     = $meta['db']['primaryKey'];

        // 记录已经存在,加载错误视图
        if (isset($data[$id])) {
            $query = $meta->getQuery(null, array('type' => 'db'));
            $dbData = $query->where($primaryKey . ' = ?', $data[$id])->fetchOne();
            if(false !== $result) {
                $lang = Qwin::call('-lang');
                $result = array(
                    'result' => false,
                    'message' => $lang['MSG_RECORD_EXISTS'],
                );
                if ($options['display']) {
                    return Qwin::call('-view')->alert($result['message']);
                } else {
                    return $result;
                }
            }
        }

        // 获取改动过的数据
        $data = $this->_filterData($meta, $options['data']);

        // 转换数据
        $data = $meta->sanitise($data, 'db');

        //$data = $metaHelper->setForeignKeyData($meta['model'], $data);

        // 加载验证微件,验证数据
        $validator = Qwin::call('-widget')->get('Validator');
        if (!$validator->valid($data, $meta)) {
            $result = array(
                'result' => false,
                'message' => $validator->getInvalidMessage(),
            );
            if ($options['display']) {
                Qwin::call('-view')->alert($result['message']['title'], null, $result['message']['content']);
            } else {
                return $result;
            }
        }

        // 保存关联模型的数据
        //$metaHelper->saveRelatedDbData($meta, $data, $query);

        // 保存到数据库
        $record = $meta->getRecord();
        $record->fromArray($data);
        $record->save();

        // 展示视图
        if ($options['display']) {
            if (!$options['url']) {
                $options['url'] = Qwin::call('-url')->url($options['module'], array('action' => 'index'));
            }
            return Qwin::call('-view')->success(Qwin::call('-lang')->t('MSG_SUCCEEDED'), $options['url']);
        }
        return array(
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
    protected function _filterData($meta, $post)
    {
        $result = array();
        foreach ($meta['field'] as $name => $field) {
            $result[$name] = isset($post[$name]) ? $post[$name] : null;
        }
        if (isset($result[$meta['db']['primaryKey']])) {
            $result[$meta['db']['primaryKey']] = null;
        }
        return $result;
    }
}
