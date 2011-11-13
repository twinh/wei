<?php
/**
 * 用于添加页面的表单
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
 * @version     $Id: Form.php 556 2011-04-17 13:32:39Z itwinh@gmail.com $
 * @since       2010-10-11 17:14:08
 */

class Qwin_AddFormAction extends Qwin_Widget
{
    /**
     * 选项
     * @var array
     */
    public $options = array(
        'form'      => null,
        'record'    => null,
        'id'        => null,
        'data'      => null,
        'display'   => true,
    );

    public function call(array $options = array())
    {
        $this->option(&$options);
        
        $form = $options['form'];
        
//        if (!$options['record'] instanceof Qwin_Record) {
//            $this->exception('Option "%s" must be an instance of %s', 'record', 'Qwin_Record');
//        }
//        $record = $options['record'];
//        $id = $record->options['id'];
        
        // 空值 < 元数据表单初始值 < 根据主键取值 < 配置初始值(一般是从url中获取)
        // 从元数据表单配置取值
//        $formInitalData = $form->getValue();
//
//        // 根据主键取值
//        $copyRecordData = array();
//        if (!is_null($options['id'])) {
//            // 从模型获取数据
//            $query = Query_Widget::getByMeta($db)
//                ->leftJoinByType(array('db', 'view'))
//                ->where($id . ' = ?', $options['id']);
//            $result = $query->fetchOne();
//            if (false !== $result) {
//                // 删除null值
//                foreach ($result as $name => $value) {
//                    !is_null($value) && $copyRecordData[$name] = $value;
//                }
//            }
//        }
//
//        // 合并数据
//        $options['data'] = $this->splitToInitalData($options['data']);
//        $data = $options['data'] + $copyRecordData + $formInitalData;

        $data = $options['data'];
        // 处理数据
        //$data = $meta->sanitise($data, 'add', array('view' => false));

        // 返回处理结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
        
        $form['data'] = $data;
        $form['action'] = 'add';
        
        // 加载表单视图
        $this->view->assign(get_defined_vars());
    }

    public function splitToInitalData($data)
    {
        $result = array();
        $data = preg_split('/(?<!\\\\)\,/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($data as &$row) {
            $row = strtr($row, array('\,' => ','));
            $row = preg_split('/(?<!\\\\)\:/', $row, -1, PREG_SPLIT_DELIM_CAPTURE);
            if (isset($row[0]) && isset($row[1])) {
                $result[$row[0]] = strtr($row[1], array('\:' => ':'));
            }
        }
        return $result;
    }
}
