<?php
/**
 * Qwin Framework
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
 */

/**
 * EditFormAction
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-05-16 12:15:56
 */
class Qwin_EditFormAction extends Qwin_Widget
{
    /**
     * @var array           默认选项
     * 
     *      -- form         表单元数据对象
     * 
     *      -- id           主键的值
     * 
     *      -- data         初始值
     * 
     *      -- sanitise     转换配置
     * 
     *      -- display      是否显示视图
     */
    public $options = array(
        'record'    => null,
        'form'      => null,
        'id'        => null,
        'data'      => array(),
        'sanitise'  => array(
            'sanitiser'     => true,
            'sanitise'      => true,
            'action'        => 'edit',
        ),
        'display'   => true,
    );
    
    public function call($options = null)
    {
        $this->option(&$options);
        $record = $options['record'];
        $form = $options['form'];
        
        // 从模型获取数据
        $id = $record->options['id'];
        $query = $this->query
            ->getByRecord($record)
            ->leftJoinByType(array('db', 'view'))
            ->where($id . ' = ?', $options['id']);
        $dbData = $query->fetchOne();

        //throw new Exception('d');
        // 记录不存在,加载错误视图
        if (false === $dbData) {
            return $options['display'] ? $this->view->alert('MSG_NO_RECORD') : array(
                'result'    => false,
                'message'   => $this->lang['MSG_NO_RECORD'],
            );
        }

        $data = $dbData->toArray();

        // 转换数据
//        if ($options['sanitise']) {
//            $data = $this->_sanitiser->sanitise($form, $data, $options['sanitise']);
//        }

        // 返回结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
        
        $lang = $this->lang;
        foreach ($form['elems'] as &$elem) {
            if (!isset($elem['label'])) {
                $elem['label'] = $lang->field($elem['name']);
            }
        }
        foreach ($form['buttons'] as &$button) {
            $button['label'] = $lang[$button['label']];
        }
        
        $form['data'] = $data;
        $form['action'] = 'edit';
        
        // 加载表单视图
        $this->view->assign(get_defined_vars());
    }
}
