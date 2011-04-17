<?php
/**
 * Delete
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-10 16:44:37
 */

class Com_Trash_Service_Delete extends Com_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_setting = array(
        'asc' => array(
            'package' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(

        ),
        'view' => array(
            'class' => '',
            'display' => true,
        ),
        'this' => null,
    );
    
    public function process(array $setting = null)
    {
        // 初始配置
        $setting = $this->_multiArrayMerge($this->_setting, $setting);

        // 通过父类,加载语言,元数据,模型等
        parent::process($setting['asc']);

        $meta = $this->_meta;
        $metaHelper = $this->metaHelper;
        $lang = $this->_lang;

        // 获取指定的回收站对象
        $query = $metaHelper->getQueryByAsc($this->_asc, 'db');

        if ('all' != $setting['data']['type']) {
            $primaryKey = explode(',', $setting['data']['primaryKeyValue']);
            $query->whereIn($meta['db']['primaryKey'], $primaryKey);
        }
        $objectList = $query->execute();

        // 循环删除各记录 TODO使用应该使用删除服务
        foreach ($objectList as $obejct) {
            // 获取源记录
            $type = explode('.', $obejct['type']);
            $asc = array(
                'package' => $type[0],
                'module' => $type[1],
                'controller' => $type[2],
            );
            $meta = $metaHelper->getMetadataByAsc($asc);
            $query = $metaHelper->getQueryByAsc($asc);
            $result = $query
                ->where($meta['db']['primaryKey'] . ' = ?', $obejct['type_id'])
                ->fetchOne();

            // 源记录不存在
            if (false == $result) {
                $obejct->delete();
                continue;
            }

            // 删除源记录和记录
            $result->delete();
            $obejct->delete();
        }

        $url = $this->url->url($this->_asc, array('action' => 'Index'));
        $return = array(
            'result' => true,
            'message' => $lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if ($setting['view']['display']) {
            return $this->view->success($return['message'], $url);
        } else {
            return $return;
        }
    }
}
