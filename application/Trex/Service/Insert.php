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
 * @package     Trex
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-11 22:31:44
 */

class Trex_Service_Insert extends Trex_Service_BasicAction
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
        'trigger' => array(
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
        $primaryKey = $meta['db']['primaryKey'];
        $query = $meta->getDoctrineQuery($this->_set);
        $relatedField = $meta->connectMetadata($this->_meta);
        Qwin::run('Qwin_Class_Extension')
            ->setNamespace('validator')
            ->addClass('Qwin_Validator_JQuery');

        // 设置行为为入库,连接元数据
        $relatedField = $meta->connectMetadata($meta);
        $addDbField = $relatedField->getAttrList('isDbField');

        // 转换,验证和还原
        $data = $this->_meta->convertSingleData($relatedField, $relatedField, 'db', $_POST);
        $validateResult = $meta->validateArray($relatedField, $data + $_POST, $this);
        if(true !== $validateResult)
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
            if($config['view']['display'])
            {
                $this->setRedirectView($message);
            }
            return $return;
        }
        $data = $meta->restoreData($relatedField, $relatedField, $data);
        $data = $meta->setForeignKeyData($meta['model'], $data);

        // 保存关联模型的数据
        $meta->saveRelatedDbData($meta, $data, $query);

        // 入库
        $ini = Qwin::run('-ini');
        $modelName = $ini->getClassName('Model', $this->_set);
        $this->_result = new $modelName;
        $this->_result->fromArray($data);
        $this->_result->save();

        // 在数据库操作之后,执行相应的 on 函数,跳转到原来的页面或列表页
        $config['trigger']['afterDb'][1] = $data;
        $this->executeTrigger('afterDb', $config);
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
