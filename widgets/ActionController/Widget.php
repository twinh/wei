<?php
/**
 * Widget
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-06-03 14:45:19
 */

class ActionController_Widget extends Controller_Widget
{
    /**
     * 控制器默认首页,Common命名空间的默认首页是数据列表
     *
     * @return array 执行结果
     */
    public function actionIndex()
    {
        $request = $this->getRequest();
        if ($request->isJson()) {
            return Qwin::call('-widget')->get('JsonListAction')->render(array(
                'meta'   => $this->getMeta(),
                'layout' => $request->get('layout'),
                'search' => $request->get('search'),
                'page'   => $request->get('page'),
                'row'    => $request->get('row'),
                'order'  => array(
                    $request->get('orderField'),
                    $request->get('orderType')
                ),
            ));
        } else {
            return Qwin::call('-widget')->get('ListAction')->render(array(
                'meta'   => $this->getMeta(),
                'layout' => $request->get('layout'),
                'row'    => $request->get('row'),
                'popup'  => $request->get('popup'),
            ));
        }
    }

    /**
     * 查看一条记录
     *
     * @return array 执行结果
     */
    public function actionView()
    {
        /*if ($this->_request->get('forward')) {
            return Qwin::call('-widget')->get('Forward')->render(array(
                'module'    => $this->_module,
                'action'    => $this->_action,
                'id'        => $this->_request->get('id'),
                'forward'   => $this->_request->get('forward'),
            ));
        }*/
        return Qwin::call('-widget')->get('ViewAction')->render(array(
            'meta'      => $this->getMeta(),
            'id'        => $this->_request->get('id'),
        ));
    }

    /**
     * 添加记录
     *
     * @return array 执行结果
     */
    public function actionAdd()
    {
        if (!$this->_request->isPost()) {
            return $this->getWidget()->get('AddFormAction')->render(array(
                'meta'      => $this->getMeta(),
                'id'        => $this->_request->get('id'),
                'search'    => $this->_request->get('search'),
            ));
        } else {
            return Qwin::call('-widget')->get('AddAction')->render(array(
                'meta'      => $this->getMeta(),
                'data'      => $_POST,
                'url'       => urldecode($this->_request->post('_page')),
            ));
        }
    }

    /**
     * 编辑记录
     *
     * @return array 执行结果
     */
    public function actionEdit()
    {
        /*if ($this->_request->get('forward')) {
            return Qwin::call('-widget')->get('Forward')->render(array(
                'module'    => $this->_module,
                'action'    => $this->_action,
                'id'        => $this->_request->get('id'),
                'forward'   => $this->_request->get('forward'),
            ));
        }*/
        if (!$this->_request->isPost()) {
            return Qwin::call('-widget')->get('EditFormAction')->render(array(
                'meta'      => $this->getMeta(),
                'id'        => $this->_request->get('id'),
            ));
        } else {
            return Qwin::call('-widget')->get('EditAction')->render(array(
                'meta'      => $this->getMeta(),
                'data'      => $_POST,
                'url'       => urldecode($this->_request->post('_page')),
            ));
        }
    }

    /**
     * 删除记录
     *
     * @return array 执行结果
     */
    public function actionDelete()
    {
        return Qwin::call('-widget')->get('DeleteAction')->render(array(
            'meta'  => $this->getMeta(),
            'id'    => $this->_request->get('id'),
        ));
    }
}