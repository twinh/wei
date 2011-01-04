<?php
/**
 * ActionController
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @subpackage  ActionController
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-26 15:31:26
 */

class Common_ActionController extends Common_Controller
{
    public $pageName = 'page';
    public $limitName = 'rowNum';

    /**
     * 控制器默认首页,Common命名空间的默认首页是数据列表
     *
     * @return array 服务处理结果
     */
    public function actionIndex()
    {
        
        if (null == $this->request->g('json')) {
            /**
             * @see Common_Service_Index $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'list' => $this->metaHelper->getUrlListField(),
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_Index')->process($config);
        } else {
            /**
             * @see Common_Service_List $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'list' => $this->metaHelper->getUrlListField(),
                    'order' => $this->metaHelper->getUrlOrder(),
                    'where' => $this->metaHelper->getUrlWhere(),
                    'offset'=> $this->metaHelper->getUrlOffset($this->pageName, $this->limitName),
                    'limit' => $this->metaHelper->getUrlLimit($this->limitName),
                    'converAsAction'=> $this->request->g('_as'),
                ),
                'callback' => array(
                    'dataConverter' => array(
                        array($this, 'dataConverter'),
                    ),
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_List')->process($config);
        }
    }

    /**
     * 弹出窗口
     *
     * @return array 服务处理结果
     */
    public function actionPopup()
    {
        /**
         * @see Common_Service_Index $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'list' => $this->metaHelper->getUrlListField(),
            ),
            'view' => array(
                'class' => 'Common_View_Popup',
            ),
            'this' => $this,
        );
        return Qwin::run('Common_Service_Index')->process($config);
    }

    /**
     * 查看一条记录
     *
     * @return array 服务处理结果
     */
    public function actionView()
    {
        /**
         * @see Common_Service_View $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
            ),
            'this' => $this,
        );
        return Qwin::run('Common_Service_View')->process($config);
    }

    /**
     * 添加记录
     *
     * @return array 服务处理结果
     */
    public function actionAdd()
    {
        if(empty($_POST))
        {
            /**
             * @see Common_Service_View $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_Form')->process($config);
        } else {
            /**
             * @see Common_Service_Insert $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'db' => $_POST,
                ),
                'callback' => array(
                    'afterDb' => array(
                        array($this, 'onAfterDb'),
                    ),
                ),
                'view' => array(
                    'url' => urldecode($this->request->p('_page')),
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_Insert')->process($config);
        }
    }

    /**
     * 编辑记录
     *
     * @return array 服务处理结果
     */
    public function actionEdit()
    {
        if(empty($_POST))
        {
            /**
             * @see Common_Service_View $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
                    'asAction' => 'edit',
                    'isLink' => false,
                    'isView' => false,
                ),
                'view' => array(
                    'class' => 'Common_View_EditForm',
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_View')->process($config);
        } else {
            /**
             * @see Common_Service_Update $_config
             */
            $config = array(
                'set' => $this->_set,
                'data' => array(
                    'db' => $_POST,
                ),
                'callback' => array(
                    'afterDb' => array(
                        array($this, 'onAfterDb'),
                    ),
                ),
                'view' => array(
                    'url' => urldecode($this->request->p('_page')),
                ),
                'this' => $this,
            );
            return Qwin::run('Common_Service_Update')->process($config);
        }
    }

    /**
     * 删除记录
     *
     * @return array 服务处理结果
     */
    public function actionDelete()
    {
        /**
         * @see Common_Service_Delete $_config
         */
        $config = array(
            'set' => $this->_set,
            'data' => array(
                'primaryKeyValue' => $this->metaHelper->getUrlPrimaryKeyValue($this->_set),
            ),
            'callback' => array(
                'afterDb' => array(
                    array($this, 'onAfterDb'),
                ),
            ),
            'this' => $this,
        );
        return Qwin::run('Common_Service_Delete')->process($config);
    }
}
