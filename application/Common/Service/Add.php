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
 * @package     Common
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 22:31:44
 */

class Common_Service_Add extends Common_Service
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_option = array(
        'asc' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data'      => array(),
        'display'   => true,
        'url'       => null,
    );

    /**
     * 根据配置,执行插入数据操作
     *
     * @param array $option 配置
     */
    public function process(array $option = null)
    {
        // 初始配置
        $option = array_merge($this->_option, $option);

        /* @var $meta Common_Metadata */
        $meta   = Common_Metadata::getByAsc($option['asc']);
        $id     = $meta['db']['primaryKey'];

        // 从模型获取数据
        $query = $meta->getQueryByAsc($option['asc'], array('db'));

        // 记录已经存在,加载错误视图
        if (isset($data[$id])) {
            $dbData = $query->where($primaryKey . ' = ?', $data[$id])->fetchOne();
            if(false !== $result) {
                $lang = Qwin::call('-lang');
                $result = array(
                    'result' => false,
                    'message' => $lang['MSG_RECORD_EXISTS'],
                );
                if ($option['display']) {
                    return Qwin::call('-view')->redirect($result['message']);
                } else {
                    return $result;
                }
            }
        }

        // 获取改动过的数据
        $data = $this->_filterData($meta, $option['data']);

        // 转换数据
        $data = $meta->sanitise($data, 'db');

        //$data = $metaHelper->setForeignKeyData($meta['model'], $data);

        // 验证数据
        if (!$meta->validate($data)) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $meta->getInvalidMessage($lang),
            );
            if ($option['display']) {
                return Qwin::call('-view')->redirect($result['message']);
            } else {
                return $result;
            }
        }
        d($_POST, 1);
        d($data);

        // 保存关联模型的数据
        //$metaHelper->saveRelatedDbData($meta, $data, $query);

        // 入库
        $result = Common_Model::getByAsc($option['asc']);
        $result->fromArray($data);
        $result->save();


        // 展示视图
        if ($option['display']) {
            if (!$option['url']) {
                $option['url'] = Qwin::call('-url')->url($option['asc'], array('action' => 'Index'));
            }
            return Qwin::call('-view')->redirect('MSG_OPERATE_SUCCESSFULLY', $option['url']);
        }
        return array(
            'result' => true,
            'data' => get_defined_vars(),
        );
    }

    /**
     * 取出添加操作入库的数据
     *
     * @param Qwin_Metadata_Abstract $meta 元数据对象
     * @param array $post 原始数据,一般为$_POST
     * @return array 数据
     */
    protected function _filterData($meta, $post)
    {
        $result = array();
        foreach ($meta['field'] as $name => $field) {
            $result[$name] = isset($post[$name]) ? $post[$name] : null;
        }
        return $result;
    }
}
