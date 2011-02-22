<?php
/**
 * Update
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
 * @since       2010-10-11 11:55:35
 */

class Common_Service_Edit extends Common_Service
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_option = array(
        'asc'       => array(
            'namespace'     => null,
            'module'        => null,
            'controller'    => null,
            'action'        => null,
        ),
        'data'      => array(),
        'display'   => true,
        'url'       => null,
    );

    public function process(array $option = null)
    {
        // 初始配置
        $option     = array_merge($this->_option, $option);
        
        /* @var $app Qwin_Application */
        $app        = Qwin::call('-app');
        
        /* @var $meta Qwin_Application_Metadata */
        $meta       = $app->getMetadataByAsc($option['asc']);
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = isset($option['data'][$primaryKey]) ? $option['data'][$primaryKey] : null;

        // 从模型获取数据
        $query = $meta->getQueryByAsc($option['asc'], array('db', 'view'));
        $dbData = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();

        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($option['display']) {
                return Qwin::call('-view')->redirect($result['message']);
            } else {
                return $result;
            }
        }

        // 获取改动过的数据
        $data = $meta->filterEditData($dbData, $option['data']);

        // 转换数据
        $data = $meta->sanitise($data, 'db');

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

        // 填充并保存数据
        $dbData->fromArray($data);
        $dbData->save();

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
}
