<?php
/**
 * Delete
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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

class Com_Trash_Widget_Delete extends Qwin_Widget
{
    /**
     * 默认选项
     * @var array
     */
    public $options = array(
        'module'    => null,
        'id'        => null,
        'type'      => null,
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

        if ('all' != $options['type']) {
            $idValue = explode(',', $options['id']);
            $query->whereIn($meta['db']['primaryKey'], $idValue);
        }
        $records = $query->execute();

        // 循环删除各记录 TODO是否应该使用删除微件
        foreach ($records as $record) {
            // 获取源记录
            /* @var $sourceMeta Com_Meta */
            $sourceMeta = Com_Meta::getByModule($record['module']);
            $sourceQuery = $sourceMeta->getQuery();
            $result = $sourceQuery
                ->select('is_deleted')
                ->where($meta['db']['primaryKey'] . ' = ?', $record['module_id'])
                ->fetchOne();

            // 源记录不存在
            if (false == $result) {
                $record->delete();
                continue;
            }

            // 删除源记录和记录
            $result->delete();
            $record->delete();
        }

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
