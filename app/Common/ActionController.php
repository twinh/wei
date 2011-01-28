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
    /**
     * 控制器默认首页,Common命名空间的默认首页是数据列表
     *
     * @return array 服务处理结果
     */
    public function actionIndex()
    {
        $request = $this->request;
        if ($request->g('json')) {
            $service = new Common_Service_List();
            return $service->process(array(
                'asc' => $this->_asc,
                'data' => array(
                    'list' => $request->getListField(),
                    'order' => $request->getOrder(),
                    'where' => $request->getWhere(),
                    'offset'=> $request->getOffset(),
                    'limit' => $request->getLimit(),
                    'converAsAction'=> $this->request->g('_as'),
                ),
                'callback' => array(
                    'dataConverter' => array(
                        array($this, 'dataConverter'),
                    ),
                ),
            ));
        } else {
            $service = new Common_Service_Index();
            return $service->process(array(
                'asc' => $this->_asc,
                'data' => array(
                    'list' => $request->getListField(),
                    'isPopup' => $request->g('qw-popup'),
                ),
            ));
        }
    }

    /**
     * 查看一条记录
     *
     * @return array 服务处理结果
     */
    public function actionView()
    {
        $service = new Common_Service_View();
        return $service->process(array(
            'asc' => $this->_asc,
            'data' => array(
                'primaryKeyValue' => $this->request->getPrimaryKeyValue($this->_asc),
            ),
        ));
    }

    /**
     * 添加记录
     *
     * @return array 服务处理结果
     */
    public function actionAdd()
    {
        if (empty($_POST)) {
            $service = new Common_Service_Form();
            return $service->process(array(
                'asc' => $this->_asc,
                'data' => array(
                    'primaryKeyValue' => $this->request->getPrimaryKeyValue($this->_asc),
                    'initalData' => $this->request->getInitialData(),
                )
            ));
        } else {
            $service = new Common_Service_Insert();
            return $service->process(array(
                'asc' => $this->_asc,
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
            ));
        }
    }

    /**
     * 编辑记录
     *
     * @return array 服务处理结果
     */
    public function actionEdit()
    {
        if (empty($_POST)) {
            $service = new Common_Service_View();
            return $service->process(array(
                'asc' => $this->_asc,
                'data' => array(
                    'primaryKeyValue' => $this->request->getPrimaryKeyValue($this->_asc),
                    'asAction' => 'edit',
                    'isLink' => false,
                    'isView' => false,
                ),
                'view' => array(
                    'class' => 'Common_View_EditForm',
                ),
            ));
        } else {
            $service = new Common_Service_Update();
            return $service->process(array(
                'asc' => $this->_asc,
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
            ));
        }
    }

    /**
     * 删除记录
     *
     * @return array 服务处理结果
     */
    public function actionDelete()
    {
        $service = new Common_Service_Delete();
        return $service->process(array(
            'asc' => $this->_asc,
            'data' => array(
                'primaryKeyValue' => $this->request->getPrimaryKeyValue($this->_asc),
            ),
            'callback' => array(
                'afterDb' => array(
                    array($this, 'onAfterDb'),
                ),
            ),
        ));
    }
}
