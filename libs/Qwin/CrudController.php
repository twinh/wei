<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 */

/**
 * CrudController
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-06-03 14:45:19
 */
class Qwin_CrudController extends Qwin_Controller
{
    /**
     * 首页,默认显示列表记录
     */
    public function indexAction()
    {
        return $this->listAction();
    }

    /**
     * 显示列表记录
     */
    public function listAction()
    {
        if ($this->isAjax()) {
            return $this->widget->jsonListAction(array(
                'jqGrid'    => $this->options['jqGrid'],
                'record'    => $this->record(),
                'layout'    => $this->get('layout'),
                'search'    => $this->get('search'),
                'page'      => $this->get('page'),
                'row'       => $this->get('rows'),
                'order'     => array(
                    $this->get('sidx'),
                    $this->get('sord')
                ),
            ));
        }
    }

    /**
     * 查看一条记录    
     */
    public function viewAction()
    {
        return $this->widget->viewAction(array(
            'form'      => $this->options['form'],
            'record'    => $this->record(),
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
                'form'      => $this->options['form'],
                'record'    => $this->record(),
                'id'        => $this->get('id'),
            ));
        } else {
            return $this->widget->addAction(array(
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
        if (!$this->request->isPost()) {
            return $this->widget->editFormAction(array(
                'form'      => $this->options['form'],
                'record'    => $this->record(),
                'id'        => $this->get('id'),
            ));
        } else {
            return $this->widget->editAction(array(
                'record'    => $this->record(),
                'data'      => $_POST,
                'url'       => urldecode($this->post('_page')),
            ));
        }
    }

    /**
     * 删除记录
     */
    public function deleteAction()
    {
        return $this->widget->deleteAction(array(
            'record'    => $this->record(),
            'id'        => $this->get('id'),
        ));
    }
}
