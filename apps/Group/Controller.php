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
            /* @var $query Qwin_Query */
            $query = $this->query()
                ->select('g.*, c.username, m.username')
                ->from('Group_Record g')
                ->leftJoin('g.creator c')
                ->leftJoin('g.modifier m')
                ->orderBy('lft');

            $node = $this->getInt('nodeid');
            if(0 < $node) {
                $n_lft = (integer)$this->request('n_left');
                $n_rgt = (integer)$this->request('n_right');
                $n_lvl = (integer)$this->request('n_level');

                $n_lvl = $n_lvl+1;
                $query->andWhere('lft > ? AND rgt < ? AND level = ?', array(
                    $n_lft, $n_rgt, $n_lvl
                ));
            } else {
                $query->andWhere('level = 0');
            }

            $data = $query->fetchArray();
            foreach ($data as &$row) {
                if ($row['creator']) {
                    $row['created_by'] = $row['creator']['username'];
                }
                if ($row['modifier']) {
                    $row['updated_by'] = $row['modifier']['username'];
                }
            }
            unset($row);

            $response = new stdClass();
            $response->page = 1;
            $response->total = 1;
            $response->records = count($data);
            $i=0;
            foreach ($data as $row) {
                $leaf = 1 >= $row['rgt'] - $row['lft'];
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell']= array(
                    $row['id'],
                    $row['name'],
                    $row['created_by'],
                    $row['updated_by'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['id'],
                    $row['level'],
                    $row['lft'],
                    $row['rgt'],
                    $leaf,
                    false
                );
                $i++;
            }
            return json_encode($response);
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

                // get max
                $max = $this->query()
                    ->orderBy('rgt DESC')
                    ->where('level = 0')
                    ->fetchOne();

                if ($max) {
                    $group->getNode()->insertAsNextSiblingOf($max);
                } else {
                    $tree->createRoot($group);
                }

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
            try {
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

                /* @var $node Doctrine_Node_NestedSet */
                $node = $group->getNode();

                $parent = $node->getParent();

                // 父分组不改变,直接保存
                if ((!$parent && !$parentId) || ($parentId == $parent['id'])) {
                    $group->save();

                // 转换为根分组
                } elseif (!$parentId) {
                    $tree = $group->getTable()->getTree();

                    $max = $this->query()
                        ->orderBy('rgt DESC')
                        ->where('level = 0')
                        ->fetchOne();

                    $node->moveAsNextSiblingOf($max);
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

                return json_encode(array(
                    'code' => 0,
                    'message' => '编辑成功',
                ));
            } catch (Exception $e) {
                return json_encode(array(
                    'code' => -1,
                    'message' => $e->getMessage()
                ));
            }
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
