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
 * @since       2010-10-11 23:31:25
 * @todo        各类检查
 */

class Qwin_DeleteAction extends Qwin_Widget
{
    public $options = array(
        'meta'      => null,
        'db'        => 'db',
        'id'        => null,
        'display'   => true,
        'url'       => null,
    );

    public function call($options = null)
    {
        $this->option(&$options);
        $record = $options['record'];
        $pk = $record->options['id'];
        
        $this->query
            ->getByRecord($record)
            ->where($pk . ' = ?', $options['id'])
            ->execute()
            ->delete();
        
        // 展示视图
        $options['url'] ? $options['url'] : $this->url->url($this->module() , 'index');
        return $options['display'] ? $this->view->success($this->lang['MSG_SUCCEEDED'], $options['url'])
                : array(
                    'result' => true,
                    'data' => get_defined_vars(),
                );
        
        // 从数据库取出记录
        $idValue = explode(',', $options['id']);
        $dbData = Query_Widget::getByMeta($db)
            ->whereIn($db['id'], $idValue)
            ->execute();
        $data = $dbData->toArray();
        
        // 数据为空则展示错误视图
        if (empty($data)) {
            return $options['display'] ? $this->_view->alert($this->_lang['MSG_NO_RECORD']) : array(
                'result'    => false,
                'message'   => $lang['MSG_NO_RECORD'],
            );
        }

        // 删除记录 TODO 提供更详细的信息
        $dbData->delete();
        
        // 展示视图
        $options['url'] ? $options['url'] : $this->_url->url($meta['module']['url'] , 'index');
        return $options['display'] ? $this->_view->success($this->_lang['MSG_SUCCEEDED'], $options['url'])
                : array(
                    'result' => true,
                    'data' => get_defined_vars(),
                );

//        $alias = $query->getRootAlias();
//        '' != $alias && $alias .= '.';
//        $results = $query
//            //->select($modelName . '.' . $meta['db']['primaryKey'])
//            ->whereIn($alias . $meta['db']['primaryKey'], $idValue)
//            ->execute();
//        
        // 分两种情况 1使用回收站 2直接删除
        // 使用回收站应符合三个条件 1启用回收站功能 2存在标识域is_deleted
//        if (isset($meta['page']['useTrash']) && true == $meta['page']['useTrash'] && isset($meta['field']['is_deleted'])) {
//            foreach ($results as $key => $result) {
//                // 设置删除标志
//                $result['is_deleted'] = 1;
//                $result->save();
//
//                // 获取记录标题,不存在则用编号代替
//                if (!empty($meta['page']['mainField'])) {
//                    $name = $result[$meta['page']['mainField']];
//                } elseif (method_exists($meta, 'getMainFieldValue')) {
//                    $name = $meta->getMainFieldValue($result);
//                } else {
//                    $name = $result[$meta['db']['primaryKey']];
//                }
//
//                $result = Com_Widget::getByModule('Com', 'Add')->execute(array(
//                    'module'    => 'com/trash',
//                    'display' => false,
//                    'data'      => array(
//                        'name' => $name,
//                        'module' => $options['module']->toString(),
//                        'module_id' => $result[$meta['db']['primaryKey']],
//                    ),
//                ));
//
//                /*
//                 * todo 结果判断
//                 * if ($result['result']) {
//                    continue;
//                } else {
//                    $this->view->redirect();
//                }*/
//            }
//        } else {
//            // TODO 统计删除数
//            // TODO 删除数据关联的模块
//            foreach ($results as $key => $result) {
//                foreach ($meta['model'] as $model) {
//                    if (isset($results[$key][$model['alias']])) {
//                        $results[$key][$model['alias']]->delete();
//                    }
//                }
//                $result->delete();
//            }
//        }
    }
}
