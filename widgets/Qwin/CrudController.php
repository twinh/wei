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

class Qwin_CrudController extends Qwin_Controller
{
    public function indexAction()
    {
        return $this->listAction();
    }
    
    /**
     * 显示列表记录
     */
    public function listAction()
    {
        if ($this->request->isJson()) {
            return $this->widget->jsonListAction(array(
                'grid'      => $this->grid(),
                'record'    => $this->record(),
                'layout'    => $this->get('layout'),
                'search'    => $this->get('search'),
                'page'      => $this->get('page'),
                'row'       => $this->get('row'),
                'order'     => array(
                    $this->get('orderField'),
                    $this->get('orderType')
                ),
            ));
        } else {
            return $this->widget->listAction(array(
                'grid'      => $this->grid(),
                'layout'    => $this->get('layout'),
                'row'       => $this->get('row'),
            ));
        }
    }

    /**
     * 查看一条记录    */
    public function viewAction()    {
        /*if ($this->_request->get('forward')) {
            return Qwin::call('-widget')->get('Forward')->render(array(
                'module'    => $this->_module,
                'action'    => $this->_action,
                'id'        => $this->_request->get('id'),
                'forward'   => $this->_request->get('forward'),
            ));
        }*/
        return $this->widget->viewAction(array(
            
        ));
        $this->dump($this->widget);
        return Qwin::call('-widget')->get('ViewAction')->render(array(
            'meta'      => $this->getMeta(),
            'id'        => $this->get('id'),
        ));
    }

    /**
     * 添加记录
     */
    public function addAction()
    {
        if (!$this->request->isPost()) {
            return $this->widget->addFormAction(array(
                'form'      => $this->form(),
                //'record'    => $this->record(),
                'id'        => $this->get('id'),
            ));
        } else {
            return $this->widget->addAcion(array(
                'form'      => $this->form(),
                'record'    => $this->record(),
                'data'      => $_POST,
                'url'       => urldecode($this->post('_page')),
            ));
        }
    }

    /**
     * 编辑记录
     */
    public function editAction()
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
     */
    public function deleteAction()
    {
        return Qwin::call('-widget')->get('DeleteAction')->render(array(
            'meta'  => $this->getMeta(),
            'id'    => $this->_request->get('id'),
        ));
    }
}