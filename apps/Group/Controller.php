<?php
/**
 * Group
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
 * @package     Com
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-15 14:43:28
 */

class Group_Controller extends Qwin_Controller
{
    public function indexAction()
    {
        if ($this->isAjax()) {
            $rows = $this->getInt('rows', 1, 500);

            $page = $this->getInt('page', 1);

            $level = $this->getInt('level', 0);

            $query = $this->query()
                ->select('g.*, c.username, m.username')
                ->from('Group_Record g')
                ->leftJoin('g.creator c')
                ->leftJoin('g.modifier m')
                ->addRawOrder(array($this->get('sidx'), $this->get('sord')))
                //->where('level = ?', $level)
                ->offset(($page - 1) * $rows)
                ->limit($rows);

            $data = $query->fetchArray();
            foreach ($data as &$row) {
                if ($row['creator']) {
                    $row['created_by'] = $row['creator']['username'];
                }
                if ($row['modifier']) {
                    $row['modified_by'] = $row['modifier']['username'];
                }
            }

            $total = $query->count();

            return $this->jQGridJson(array(
                'columns' => array('id', 'name', 'created_by', 'modified_by', 'date_created', 'date_modified', 'operation'),
                'data' => $data,
                'page' => $page,
                'rows' => $rows,
                'total' => $total,
            ));
        }
    }

    public function addAction()
    {
        if ($this->isPost()) {
            $parentId = $this->post('parent_id');
            $name = $this->post('name');
            $description = $this->post('description');

            if (!$parentId) {
                /* @var $group Group_Record */
                $group = $this->record();
                $group['name'] = $name;
                $group['description'] = $description;

                $tree = $group->getTable()->getTree();
                $tree->createRoot($group);

                return json_encode(array(
                    'code' => 0,
                    'message' => '添加成功',
                ));
            } else {
                $parent = $this->query()
                    ->where('id = ?', $parentId)
                    ->fetchOne();

                $group = new Group_Record();
                $group['name'] = $name;

                $group->getNode()->insertAsLastChildOf($parent);

                return json_encode(array(
                    'code' => 0,
                    'message' => '添加成功',
                ));
            }
        } else {
            // 在某一分组下增加子分组
            $parentId = $this->get('parentId');
            $data['parent_id'] = $parentId;
            $data = json_encode($data);


            // 分组选项
            $options = $this->record()->getParentOptions();
            $options = array(0 => '(' . $this->lang['Root group'] . ')') + $options;
            $options = json_encode($options);


            $this->view->assign(get_defined_vars());
        }
    }

    public function editAction()
    {
        $id = $this->get('id');
        $group = $this->query()->where('id = ?', $id)->fetchOne();

        if ($this->isPost()) {
            if (!$group) {
                return array(
                    'code' => -1,
                    'message' => 'Group is not exists.',
                );
            }

            $name = $this->post('name');
            $description = $this->post('description');
            $parentId = $this->post('parent_id');

            $group['name'] = $name;
            $group['description'] = $description;

            $node = $group->getNode();

            // 根分组
            if ($node->isRoot()) {
                // 不移动
                if (!$parentId) {
                    $group->save();

                // 移动到其他分组之下
                } else {
                    $newParent = $this->query()
                        ->where('id = ?', $parentId)
                        ->fetchOne();

                    // 不存在的父分组
                    if (!$newParent) {
                        $group->save();
                    } else {
                        $node->moveAsLastChildOf($newParent);
                    }
                }
            // 非根分组
            } else {
                $parent = $node->getParent();

                // 父分组不改变,直接保存
                if ($parentId == $parent['id']) {
                    $group->save();

                // 转换为根分组
                } elseif (!$parentId) {
                    $node->makeRoot($this->record()->createRootId());

                // 移动到其他分组之下
                } else {
                    $newParent = $this->query()
                        ->where('id = ?', $parentId)
                        ->fetchOne();

                    // 父节点存在,移动
                    if ($newParent) {
                        $node->moveAsLastChildOf($newParent);
                    }
                }
            }

            return json_encode(array(
                'code' => 0,
                'message' => '编辑成功',
            ));
        } else {
            if (!$group) {
                $this->error('Group is not exists.');
            }

            $parent = $group->getNode()->getParent();

            // 分组数据
            $data = $group->toArray();
            $data['parent_id'] = $parent['id'];
            $data = json_encode($data);

            // 分组选项
            $options = $this->record()->getParentOptions();
            $options = array(0 => '(' . $this->lang['Root group'] . ')') + $options;
            $options = json_encode($options);

            $this->view->assign(get_defined_vars());
        }
    }

    public function deleteAction()
    {
        $id = $this->get('id');

        $group = $this->query()
            ->where('id = ?', $id)
            ->fetchOne();

        if (!$group) {
            $this->error('Group is not exists');
        } else {
            $group->getNode()->delete();
            return json_encode(array(
                'code' => 0,
                'message' => '删除成功',
            ));
        }
    }
}
