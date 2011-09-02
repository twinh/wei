<?php
/**
 * Restore
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
 * @since       2011-01-10 15:40:44
 */

class Com_Trash_Widget_Restore extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'id'        => null,
        'display'   => true,
        'url'       => null,
    );

    public function execute($options)
    {
        $options = $this->_options = (array)$options + $this->_options;

        /* @var $meta Com_Meta */
        $meta = Com_Meta::getByModule($options['module']);
        $idValue = explode(',', $options['id']);
        $query = $meta->getQuery(null, array(
            'type' => 'db',
        ));

        // 获取指定的回收站对象
        $records = $query
            ->whereIn($meta['db']['primaryKey'], $idValue)
            ->execute();

        // 记录不存在
        if (0 == $records->count()) {
            $result = array(
                'result' => false,
                'message' => $this->_lang->t('MSG_NO_RECORD'),
            );
            if ($options['display']) {
                return $this->_View->alert($result['message']);
            } else {
                return $result;
            }
        }

        // 统计操作结果
        /*$count = array(
            0 => 0,
            1 => 0,
            2 => 0,
        );*/
        // 循环还原各记录
        foreach ($records as $record) {
            // 获取源记录
            /* @var $moduleMeta Com_Meta */
            $moduleMeta = Com_Meta::getByModule($record['module']);
            $moduleQuery = $moduleMeta->getQuery();
            $result = $moduleQuery
                ->select('is_deleted')
                ->where($meta['db']['primaryKey'] . ' = ?', $record['module_id'])
                ->fetchOne();
            
            // 源记录不存在
            if (false == $result) {
                // 删除当前记录
                $record->delete();
                continue;
            }

            // 更改删除状态并保存
            $result['is_deleted'] = 0;
            $result->save();

            // 删除当前记录
            $record->delete();
        }
        // TODO log 总共查询 11 条记录,找到10 条记录,其中找到 8 条源纪录,7 条删除状态正常,1条状态异常,已还原7条

        // 展示视图
        if ($options['display']) {
            !$options['url'] && $options['url'] = Qwin::call('-url')->url('com/trash');
            return $this->_View->success(Qwin::call('-lang')->t('MSG_SUCCEEDED'), $options['url']);
        }
        return array(
            'result' => true,
            'data' => get_defined_vars(),
        );
    }
}
