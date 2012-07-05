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
            $data = $this->query()
                ->orderBy('order')
                ->fetchArray();

            $response = array();
            $response['page'] = 1;
            $response['total'] = 1;
            $response['records'] = count($data);
            $i = 0;

            foreach ($data as &$menu) {
                if (!$menu['category_id']) {

                    $response['rows'][$i] = array();
                    $response['rows'][$i]['id'] = $menu['id'];
                    $response['rows'][$i]['cell'] = array(
                        $menu['id'],
                        $menu['category_id'],
                        $menu['title'],
                        null,
                        null,
                        $menu['order'],
                        $menu['id'],
                        0,
                        null,
                        false,
                        true,
                        true,
                    );
                    $i++;

                    foreach ($data as &$subMenu) {
                        if ($menu['id'] == $subMenu['category_id']) {

                            $response['rows'][$i] = array();
                            $response['rows'][$i]['id'] = $subMenu['id'];
                            $response['rows'][$i]['cell'] = array(
                                $subMenu['id'],
                                $subMenu['category_id'],
                                $subMenu['title'],
                                $subMenu['url'],
                                $subMenu['target'],
                                $subMenu['order'],
                                $subMenu['id'],
                                1,
                                $subMenu['category_id'],
                                true,
                                false,
                                true,
                            );
                            $i++;

                            unset($subMenu);
                        }
                    }
                    unset($menu);
                }
            }

            return json_encode($response);
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

            !$data['category_id'] && $data['category_id'] = null;

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
            $menu = $this->record();

            $menu->fromArray($this->post->toArray());

            !$menu['category_id'] && $menu['category_id'] = null;

            $menu->save();

            return json_encode(array(
                'code' => 0,
                'message' => '编辑成功',
            ));
        } else {
            $categorys = json_encode($this->record()->getCagetoryOptions());

            $this->view->assign(get_defined_vars());
        }
    }

    public function deleteAction()
    {
        $id = $this->post('id');

        $menu = $this->query()
            ->where('id = ?', $id)
            ->fetchOne();

        if (!$menu) {
            $this->error('Menu is not exists');
        }

        $menu->delete();

        return json_encode(array(
            'code' => 0,
            'message' => 'Menu deleted successfully'
        ));
    }
}
