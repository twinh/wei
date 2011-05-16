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
 * @since       2011-5-16 12:15:56
 */

class EditFormAction_Widget extends Qwin_Widget_Abstract
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
    protected $_defaults = array(
        'form'      => null,
        'id'        => null,
        'data'      => array(),
        'asAction'  => 'view',
        'isView'    => true,
        'sanitise'  => array(
            
        ),
        'display'   => true,
    );
    
    public function render($options = null)
    {
        // 初始配置
        $options    = (array)$options + $this->_options;
        
        /* @var $listMeta Qwin_Meta_Form */
        $form = $options['form'];
        
        // 检查表单元数据是否合法
        if (!is_object($form) || !$form instanceof Qwin_Meta_Form) {
            $this->e('ERR_META_ILLEGAL');
        }
        $meta = $form->getParent();
        $id = $meta['db']['id'];
        
        // 从模型获取数据
        $query = $meta->getQuery(null, array('type' => array('db', 'view')));
        $dbData = $query
            ->where($id . ' = ?', $options['id'])
            ->fetchOne();
        
        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($options['display']) {
                return Qwin::call('-view')->alert($result['message']);
            } else {
                return $result;
            }
        }
        $data = $dbData->toArray();
        
        // 转换数据
        if ($options['sanitise']) {
            $data = $meta->sanitise($data, $options['sanitise']);
        }
        
        // 返回结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
        
        $view = Qwin::call('-view');
        $view->setElement('content', '<root>com/basic/form<suffix>');
        $view['module'] = $meta->getModule();
        $view['action'] = 'edit';

        /* @var $formWidget Form_Widget */
        $formWidget = Qwin::call('-widget')->get('Form');
        $formOptions = array(
            'form'  => $form,
            'action' => 'edit',
            'data'  => $data,
        );

        $operLinks = Qwin::call('-widget')->get('OperLinks')->render($view);

        $view->assign(get_defined_vars());
    }
}