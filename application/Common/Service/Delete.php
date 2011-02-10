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
 * @package     Common
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 23:31:25
 */

class Common_Service_Delete extends Common_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_config = array(
        'set' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(
            'primaryKeyValue' => null,
        ),
        'callback' => array(
            'afterDb' => null,
        ),
        'view' => array(
            'class' => null,
            'display' => true,
        ),
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::call('Qwin_Application_Metadata');
        $meta = $this->_meta;
        $primaryKey = explode(',', $config['data']['primaryKeyValue']);

        $query = $metaHelper->getQueryByAsc($this->_asc, 'db');
        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';
        $resultList = $query
            //->select($modelName . '.' . $meta['db']['primaryKey'])
            ->whereIn($alias . $meta['db']['primaryKey'], $primaryKey)
            ->execute();

        // 分两种情况 1使用回收站 2直接删除
        // 使用回收站应符合三个条件 1启用回收站功能 2存在标识域is_deleted
        if ($meta['page']['useRecycleBin'] && isset($meta['field']['is_deleted'])) {
            foreach ($resultList as $key => $result) {
                // 设置删除标志
                $result['is_deleted'] = 1;
                $result->save();

                // 获取记录标题,不存在则用编号代替
                if (!empty($meta['page']['mainField'])) {
                    $name = $result[$meta['page']['mainField']];
                } elseif (method_exists($meta, 'getMainFieldValue')) {
                    $name = $meta->getMainFieldValue($result);
                } else {
                    $name = $result[$meta['db']['primaryKey']];
                }

                // 加入回收站
                $InsertSetting = array(
                    'set' => array(
                        'namespace' => 'Common',
                        'module' => 'RecycleBin',
                        'controller' => 'RecycleBin',
                    ),
                    'data' => array(
                        'db' => array(
                            'name' => $name,
                            'type' => $this->_asc['namespace'] . '.' . $this->_asc['module'] . '.' . $this->_asc['controller'],
                            'type_id' => $result[$meta['db']['primaryKey']],
                        ),
                    ),
                    'view' => array(
                        'display' => false,
                    ),
                    'this' => $config['this'],
                );
                $sevice = new Common_Service_Insert();
                $result = $sevice->process($InsertSetting);
                /*
                 * todo 结果判断
                 * if ($result['result']) {
                    continue;
                } else {
                    $this->view->setRedirectView();
                }*/
            }
        } else {
            // TODO $resultList->delete();
            // TODO 统计删除数
            // TODO 删除数据关联的模块
            foreach ($resultList as $key => $result) {
                foreach ($meta['model'] as $model) {
                    if (isset($resultList[$key][$model['alias']])) {
                        $resultList[$key][$model['alias']]->delete();
                    }
                }
                $result->delete();
            }
        }

        // 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
        $config['callback']['afterDb'][1] = $resultList;
        $this->executeCallback('afterDb', $config);
        
        $url = urldecode($this->request->post('_page'));
        '' == $url && $url = $this->url->url($this->_asc, array('action' => 'Index'));

        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['display'])
        {
            $this->view->setRedirectView($return['message'], $url);
        }
        return $return;
    }
}
