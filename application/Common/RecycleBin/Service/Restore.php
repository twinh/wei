<?php
/**
 * Restore
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
 * @since       2011-01-10 15:40:44
 */

class Common_RecycleBin_Service_Restore extends Common_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_setting = array(
        'asc' => array(
            'namespace' => null,
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
        $primaryKey = explode(',', $setting['data']['primaryKeyValue']);

        // 获取指定的回收站对象
        $query = $metaHelper->getQueryByAsc($this->_asc, 'db');
        $objectList = $query
            ->whereIn($meta['db']['primaryKey'], $primaryKey)
            ->execute();

        // 统计操作结果
        /*$count = array(
            0 => 0,
            1 => 0,
            2 => 0,
        );*/
        // 循环还原各记录
        foreach ($objectList as $obejct) {
            // 获取源记录
            $type = explode('.', $obejct['type']);
            $asc = array(
                'namespace' => $type[0],
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
                // 删除当前记录
                $obejct->delete();
                continue;
            }

            // 更改删除状态并保存
            $result['is_deleted'] = 0;
            $result->save();

            // 删除当前记录
            $obejct->delete();
        }
        // TODO log 总共查询 11 条记录,找到10 条记录,其中找到 8 条源纪录,7 条删除状态正常,1条状态异常,已还原7条

        $url = $this->url->url($this->_asc, array('action' => 'Index'));
        $return = array(
            'result' => true,
            'message' => $lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if ($setting['view']['display']) {
            return $this->view->setRedirectView($return['message'], $url);
        } else {
            return $return;
        }
    }
}
