<?php
/**
 * Insert
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
 * @since       2010-10-11 22:31:44
 */

class Common_Service_Insert extends Common_Service_BasicAction
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
        ),
        'view' => array(
            'class' => null,
            'display' => true,
            'url' => null,
        ),
        'this' => null
    );

    /**
     * 根据配置,执行插入数据操作
     *
     * @param array $config 配置
     * @todo 检查是否存在数据
     */
    public function process(array $config = null)
    {
        // 初始配置
        $config = $this->_multiArrayMerge($this->_config, $config);
        $metaHelper = Qwin::run('Qwin_Application_Metadata');
        if(null == $config['this'])
        {
            $config['this'] = Qwin::run($metaHelper->getClassName('Controller', $config['set']));
        }

        // 通过父类,加载语言,元数据,模型等
        parent::process($config['set']);

        // 初始化常用的变量
        $meta = $this->_meta;
        $primaryKey = $meta['db']['primaryKey'];
        $query = $metaHelper->getQueryBySet($this->_set, 'db');
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');

        // 转换,验证
        $data = $metaHelper->unsetPrimaryKeyValue($config['data']['db'], $meta);

        // 检查记录是否存在,存在则提示
        if(isset($data[$primaryKey]))
        {
            $result = $query->where($primaryKey . ' = ?', $data[$primaryKey])->fetchOne();
            if(false != $result)
            {
                $return = array(
                    'result' => false,
                    'message' => $this->_lang->t('MSG_RECORD_EXISTS'),
                );
                if($config['view']['display'])
                {
                    $config['this']->setRedirectView($return['message']);
                }
                return $return;
            }
        }

        $data = $metaHelper->convertOne($data, 'db', $meta, $meta, array('view' => false));
        $data = $metaHelper->setForeignKeyData($meta['model'], $data);
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

        // 保存关联模型的数据
        //$metaHelper->saveRelatedDbData($meta, $data, $query);

        // 入库
        $modelName = $metaHelper->getClassName('Model', $this->_set);
        $this->_result = new $modelName;
        $this->_result->fromArray($data);
        $this->_result->save();

        // 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
        if(isset($config['callback']['afterDb']))
        {
            $config['callback']['afterDb'][1] = $data;
            $config['callback']['afterDb'][2] = null;
            $this->executeCallback('afterDb', $config);
        }

        // 设置视图数据
        if($config['view']['url'])
        {
            $url = $config['view']['url'];
        } else {
            $url = $this->url->createUrl($this->_set, array('action' => 'Index'));
        }
        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['display'])
        {
            $config['this']->setRedirectView($return['message'], $url);
        }
        return $return;
    }
}
