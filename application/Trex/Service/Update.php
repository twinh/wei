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
 * @package     Trex
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 11:55:35
 */

class Trex_Service_Update extends Trex_Service_BasicAction
{
    /**
     * 该服务的基本配置
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
        'trigger' => array(
            'beforeConvert' => array(),
            'afterDb' => array(),
        ),
        'view' => array(
            'isLoad' => true,
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
        $primaryKey = $meta['db']['primaryKey'];
        $primaryKeyValue = isset($config['data']['db'][$primaryKey]) ? $config['data']['db'][$primaryKey] : null;
        
        // 从模型获取数据
        $query = $meta->getDoctrineQuery($this->_set);
        $result = $query->where($primaryKey . ' = ?', $primaryKeyValue)->fetchOne();

        // 记录不存在,加载错误视图
        if(false == $result)
        {
            $return = array(
                'result' => false,
                'message' => $this->_lang->t('MSG_NO_RECORD'),
            );
            if($config['view']['isLoad'])
            {
                $this->setRedirectView($result['message'])
                    ->loadView()
                    ->display();
            }
            return $return;
        }
        
        // 设置行为为入库,连接元数据
        //$this->setAction('db');
        $relatedField = $meta->connectMetadata($meta);
        $editDbField = $relatedField->getAttrList('isDbField', 'isReadonly');

        // 转换,验证和还原
        $data = $meta->convertSingleData($relatedField, $relatedField, 'db', $_POST);
        $validateResult = $meta->validateArray($relatedField, $data + $_POST, $this);
        // TODO 转变为一个方法
        if(false === $validateResult)
        {
            $message = $this->_lang->t('MSG_ERROR_FIELD')
                . $this->_lang->t($relatedField[$validateResult->field]['basic']['title'])
                . '<br />'
                . $this->_lang->t('MSG_ERROR_MSG')
                . $meta->format($this->_lang->t($validateResult->message), $validateResult->param);
            $return = array(
                'result' => false,
                'message' => $message,
            );
            if($config['view']['isLoad'])
            {
                $this->setRedirectView($message)
                    ->loadView()
                    ->display();
            }
            return $return;
        }
        $data = $meta->restoreData($editDbField, $relatedField, $data);

        // 保存关联模型的数据
        $meta->saveRelatedDbData($meta, $data, $result);

        /**
         * 入库
         * @todo 设置 null 值
         */
        $result->fromArray($data);
        $result->save();

        // 入库后,执行绑定事件
        $config['trigger']['afterDb'][1] = $data;
        $this->executeTrigger('afterDb', $config);
        $url = urldecode($this->_request->p('_page'));
        '' == $url && $url = Qwin::run('-url')->createUrl($this->_set, array('action' => 'Index'));

        $return = array(
            'result' => true,
            'message' => $this->_lang->t('MSG_OPERATE_SUCCESSFULLY'),
            'url' => $url,
        );
        if($config['view']['isLoad'])
        {
            $this->setRedirectView($return['message'], $url)
                ->loadView()
                ->display();
        }
        return $return;
    }
}
