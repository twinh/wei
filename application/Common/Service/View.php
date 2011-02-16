<?php
/**
 * View
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
 * @since       2010-10-11 10:35:49
 */

class Common_Service_View extends Common_Service
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_option = array(
        'asc'       => array(
            'namespace'     => null,
            'module'        => null,
            'controller'    => null,
            'action'        => null,
        ),
        'value'     => null,
        'asAction'  => 'view',
        'isView'    => true,
        'filter'    => true,
        'display'   => true,
        'viewClass' => 'Common_View_View',
    );

    /**
     * 处理结果的配置
     * @var array
     */
    protected $_result = array(
        'result' => true,
        'message' => null,
        'step' => null,
        'view' => null,
        'data' => null,
    );

    public function process(array $option = null)
    {
        // 初始配置
        $option     = array_merge($this->_option, $option);
        $manager    = Qwin::call('-manager');
        $meta       = $manager->getMetadataByAsc($option['asc']);
        $primaryKey = $meta['db']['primaryKey'];

        // 从模型获取数据
        $query = $meta->getQueryByAsc($option['asc'], array('db', 'view'));
        $dbData = $query->where($primaryKey . ' = ?', $option['value'])->fetchOne();

        // 记录不存在,加载错误视图
        if (false == $dbData) {
            $lang = Qwin::call('-lang');
            $result = array(
                'result' => false,
                'message' => $lang['MSG_NO_RECORD'],
            );
            if ($option['display']) {
                return $this->view->redirect($result['data']);
            } else {
                return $result;
            }
        }

        // 设置钩子:取得数据
        Qwin::hook('viewRecord', array(
            'record' => $dbData,
            'meta' => $meta,
        ));

        // 对数据进行转换
        if ($option['filter']) {
            $data = $meta->filterOne($dbData->toArray(), $option['asAction'], array('view' => $option['isView']));
        }

        // 设置返回结果
        $result = array(
            'result' => true,
            'data' => get_defined_vars(),
        );

        // 展示视图
        if ($option['display']) {
            $view = new $option['viewClass'];
            return $view->assign($result['data']);
        }

        return $result;
    }
}
