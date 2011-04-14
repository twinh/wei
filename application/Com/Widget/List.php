<?php
/**
 * Index
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
 * @version     $Id$
 * @since       2010-10-10 14:16:45
 */

class Com_Widget_List extends Qwin_Widget_Abstract
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_options = array(
        'module'    => null,
        'list'      => array(),
        'popup'     => false,
        'display'   => true,
        'viewClass' => 'Com_View_List',
    );

    /**
     * 服务处理
     *
     * @param array $options 选项
     * @return array 处理结果
     */
    public function execute(array $options = null)
    {
        // 初始配置
        $options     = $options + $this->_options;
        $meta       = Com_Metadata::getByModule($options['module']);
        $primaryKey = $meta['db']['primaryKey'];

        // 显示哪些域
        $list       = $options['list'];

        // 是否以弹出框形式显示
        $popup      = $options['popup'];

        // 设置返回结果
        $result = array(
            'result' => true,
            'data' => get_defined_vars(),
        );

        // 展示视图
        if ($options['display']) {
            $view = new $options['viewClass'];
            return $view->assign($result['data']);
        }

        return $result;
    }
}
