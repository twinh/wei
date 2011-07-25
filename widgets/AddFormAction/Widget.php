<?php
/**
 * 用于添加页面的表单
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
 * @package     Com
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id: Form.php 556 2011-04-17 13:32:39Z itwinh@gmail.com $
 * @since       2010-10-11 17:14:08
 */

class AddFormAction_Widget extends Qwin_Widget_Abstract
{
    /**
     * 默认选项
     * @var array
     */
    protected $_defaults = array(
        'meta'      => null,
        'form'      => 'form',
        'db'        => 'db',
        'id'        => null,
        'data'      => null,
        'display'   => true,
    );

    public function render($options = null)
    {
        // 初始配置
        $options = (array)$options + $this->_options;

        // 检查元数据是否合法
        /* @var $meta Com_Meta */
        $meta = $options['meta'];
        if (!Qwin_Meta::isValid($meta)) {
            throw new Qwin_Widget_Exception('ERR_META_ILLEGAL');
        }

        // 检查列表元数据是否合法
        /* @var $form Qwin_Meta_Form */
        if (!($form = $meta->offsetLoad($options['form'], 'form'))) {
            throw new Qwin_Widget_Exception('ERR_FROM_META_NOT_FOUND');
        }

        // 检查数据库元数据是否合法
        /* @var $db Qwin_Meta_Db */
        if (!($db = $meta->offsetLoad($options['db'], 'db'))) {
            throw new Qwin_Widget_Exception('ERR_DB_META_NOT_FOUND');
        }
        $id = $db['id'];
        
        // 空值 < 元数据表单初始值 < 根据主键取值 < 配置初始值(一般是从url中获取)
        // 从元数据表单配置取值
        $formInitalData = $form->getValue();

        // 根据主键取值
        $copyRecordData = array();
        if (!is_null($options['id'])) {
            // 从模型获取数据
            $query = Query_Widget::getByMeta($db)
                ->leftJoinByType(array('db', 'view'))
                ->where($id . ' = ?', $options['id']);
            $result = $query->fetchOne();
            if (false !== $result) {
                // 删除null值
                foreach ($result as $name => $value) {
                    !is_null($value) && $copyRecordData[$name] = $value;
                }
            }
        }

        // 合并数据
        $options['data'] = $this->splitToInitalData($options['data']);
        $data = $options['data'] + $copyRecordData + $formInitalData;

        // 处理数据
        //$data = $meta->sanitise($data, 'add', array('view' => false));

        // 返回处理结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }
        
        /* @var $formWidget Form_Widget */
        $formWidget = $this->_Form;
        $formOptions = array(
            'meta'      => $meta,
            'form'      => $options['form'],
            'db'        => $options['db'],
            'action'    => 'add',
            'data'      => $data,
        );

        $view = $this->_view;
        $view->assign(get_defined_vars());
        $view->setElement('content', $this->_widget->getPath() . 'EditFormAction/view/default.php');
        $view['module'] = $meta['module'];
        $view['action'] = 'add';

        $operLinks = Qwin::call('-widget')->get('OperLinks')->render($view);
        $view['operLinks'] = $operLinks;
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
