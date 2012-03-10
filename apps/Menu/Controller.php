<?php
/**
 * AdminMenu
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
 * @package     Member
 * @subpackage  Menu
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-25 07:59:33
 */

class Menu_Controller extends Qwin_Controller
{
    public function indexAction()
    {
        if ($this->isAjax()) {
            $rows = $this->getInt('rows', 1, 500);

            $page = $this->getInt('page', 1);

            $query = $this->query()
                ->leftJoin('Menu_Record.category')
                ->addRawOrder(array($this->get('sidx'), $this->get('sord')))
                ->offset(($page - 1) * $rows)
                ->limit($rows);

            $data = $query->fetchArray();
            foreach ($data as &$row) {
                if ($row['category']) {
                    $row['category_id'] = $row['category']['title'];
                } else {
                    $row['category_id'] = '-';
                }
            }

            $total = $query->count();

            return $this->jQGridJson(array(
                'columns' => array('id', 'category_id', 'title', 'url', 'target', 'order', 'operation'),
                'data' => $data,
                'page' => $page,
                'rows' => $rows,
                'total' => $total,
            ));
        }
    }

    public function editAction()
    {
        if ($this->isPost()) {
            $id = $this->post('id');

            $data = $this->query()
                ->where('id = ?', $id)
                ->fetchOne();

            if (!$data) {
                $this->error('Menu is not exists');
            }

            $data->fromArray($this->post->toArray());

            $data->save();

            return json_encode(array(
                'code' => 0,
                'message' => '编辑成功',
            ));
        } else {
            $id = $this->get('id');

            $data = $this->query()
                ->where('id = ?', $id)
                ->fetchOne();

            if (!$data) {
                $this->error('Menu is not exists');
            }

            $data = json_encode($data->toArray());

            $categorys = json_encode($this->record()->getCagetoryOptions());

            $this->view->assign(get_defined_vars());
        }
    }

    public function addAction()
    {
        if ($this->isPost()) {
            $id = $this->post('id');

            $data = $this->query()
                ->where('id = ?', $id)
                ->fetchOne();

            if (!$data) {
                $this->error('Menu is not exists');
            }

            $data->fromArray($this->post->toArray());

            return json_encode(array(
                'code' => 0,
                'message' => '编辑成功',
            ));
        } else {
            $categorys = json_encode($this->record()->getCagetoryOptions());

            $this->view->assign(get_defined_vars());
        }
    }
}
