<?php
/**
 * Update
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
 * @since       2010-10-11 11:55:35
 */

class Common_Service_Update extends Common_Service_BasicAction
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
            'db' => null,
        ),
        'callback' => array(
            'beforeConvert' => array(),
            'afterDb' => array(),
        ),
        'view' => array(
            'class' => 'Common_View_JqGridJson',
            'display' => true,
            'url' => null,
        ),
        'this' => null
    );

    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);
        if(isset($config['data']['meta']))
        {
            $this->_meta = $config['data']['meta'];
        }

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $metaHelper = Qwin::run('Qwin_Application_Metadata');
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = isset($config['data']['db'][$primaryKey]) ? $config['data']['db'][$primaryKey] : null;
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');
        
        // 从模型获取数据
        $query = $metaHelper->getQueryByAsc($this->_asc, 'db');
        $result = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();

        // 记录不存在,加载错误视图
        if(false == $result)
        {
            $return = array(
                'result' => false,
                'message' => $this->_lang->t('MSG_NO_RECORD'),
            );
            if($config['view']['display'])
            {
                $this->view->setRedirectView($return['message']);
            }
            return $return;
        }
        // 原始数据
        $rawData = $result->toArray();

        // 补全数据
        $data = $metaHelper->fillDbData($config['data']['db'], $rawData);

        // TODO 如果值是从数据库来的,没有经过更改,则可以不进行验证转换
        // 转换,验证
        $data = $metaHelper->convertOne($data, 'db', $meta, $meta, array('view' => false));
        $validateResult = $metaHelper->validateArray($data + $_POST, $meta, $meta);
        if(true !== $validateResult)
        {
            $message = $config['this']->showValidateError($validateResult, $meta, $config['view']['display']);
            $return = array(
                'result' => false,
                'message' => $message,
            );
            return $return;
        }

        //$metaHelper->saveRelatedDbData($meta, $data, $result);

        // 删除只读域的值
        $data = $metaHelper->deleteReadonlyValue($data, $meta);

        // 保存到数据库
        $result->fromArray($data);
        $result->save();

        // 入库后,执行绑定事件
        if(!empty($config['callback']['afterDb']))
        {
            $config['callback']['afterDb'][1] = $data;
            $config['callback']['afterDb'][2] = $rawData;
            $this->executeCallback('afterDb', $config);
        }

        // 设置视图数据
        if($config['view']['url'])
        {
            $url = $config['view']['url'];
        } else {
            $url = $this->url->url($this->_asc, array('action' => 'Index'));
        }
        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['display'])
        {
            $this->view->setRedirectView($return['message'], $url);
        }
        return $return;
    }
}
