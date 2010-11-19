<?php
/**
 * Delete
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
 * @package     Trex
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 23:31:25
 */

class Trex_Service_Delete extends Trex_Service_BasicAction
{
    /**
     * 服务的基本配置
     * @var array
     */
    protected $_config = array(
        'set' => array(
            'namespace' => null,
            'module' => null,
            'controller' => null,
            'action' => null,
        ),
        'data' => array(
            'primaryKeyValue' => null,
        ),
        'callback' => array(
            'afterDb' => null,
        ),
        'view' => array(
            'class' => null,
            'display' => true,
        ),
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_Trex_Metadata');
        $meta = $this->_meta;
        $primaryKey = explode(',', $config['data']['primaryKeyValue']);

        $query = $metaHelper->getQueryBySet($this->_set, 'db');

        $alias = $query->getRootAlias();
        '' != $alias && $alias .= '.';

        $object = $query
            //->select($modelName . '.' . $meta['db']['primaryKey'])
            ->whereIn($alias . $meta['db']['primaryKey'], $primaryKey)
            ->execute();

        // TODO $object->delete();
        // TODO 统计删除数
        // TODO 删除数据关联的模块
        foreach($object as $key => $value)
        {
            foreach($meta['model'] as $model)
            {
                $object[$key][$model['alias']]->delete();
            }
            $value->delete();
        }

        // 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
        $config['callback']['afterDb'][1] = $object;
        $this->executeCallback('afterDb', $config);
        
        $url = urldecode($this->request->p('_page'));
        '' == $url && $url = $this->url->createUrl($this->_set, array('action' => 'Index'));

        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['display'])
        {
            $this->setRedirectView($return['message'], $url);
        }
        return $return;
    }
}