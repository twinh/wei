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

class AddForm_Widget extends Qwin_Widget_Abstract
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_defaults = array(
        'module'    => null,
        'id'        => null,
        'data'      => array(),
        'display'   => true,
    );

    public function execute(array $options = null)
    {
        // 初始配置
        $options    = $options + $this->_options;
        $module     = $options['module'];

        /* @var $meta Com_Meta */
        $meta       = Com_Meta::getByModule($module);
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = $options['id'];

        /**
         * 空值 < 元数据表单初始值 < 根据主键取值 < 配置初始值(一般是从url中获取)
         */
        // 从元数据表单配置取值
        $formInitalData = $meta['field']->getFormValue();

        // 根据主键取值
        $copyRecordData = array();
        if (null != $primaryKeyValue) {
            // 从模型获取数据
            $query = $meta->getQuery();
            $result = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();
            if (false !== $result) {
                // 删除null值
                foreach ($result as $name => $value) {
                    null !== $value && $copyRecordData[$name] = $value;
                }
            }
        }

        // 合并数据
        $options['data'] = $this->splitToInitalData($options['data']);
        $data = array_merge($formInitalData, $copyRecordData, $options['data']);

        // 处理数据
        $data = $meta->sanitise($data, 'add', array('view' => false));

        // 返回处理结果
        if (!$options['display']) {
            return array(
                'result' => true,
                'data' => get_defined_vars(),
            );
        }

        // 展示视图 TODO
        $view = Qwin::call('-view');
        $view->setElement('content', '<root>com/basic/form<suffix>');
        $view['module'] = $module;
        $view['action'] = 'add';
        $view['data'] = $data;
        $view['primaryKey'] = $primaryKey;

        /* @var $formWidget Form_Widget */
        $formWidget = Qwin::call('-widget')->get('Form');
        $formOptions = array(
            'meta'  => $meta,
            'action' => 'add',
            'data'  => $data,
        );

        
        $operLinks = Qwin::call('-widget')->get('OperLinks')->render($view);
        $view->assign(get_defined_vars());
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
