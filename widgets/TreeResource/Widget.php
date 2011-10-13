<?php
/**
 * Category
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-7-17 9:41:38
 * @since     2010-7-17 9:41:38
 */

class TreeResource_Widget extends Qwin_Widget
{
    protected $_defaultKeys = array(
        'id', 'parent_id', 'name',
    );

    private $_resourceCache;
    private $_fileCache;

    public function get($module, $parent = null, $keys = array(), $isPrefix = true)
    {
        /**
         * 将文件缓存存到该对象中
         * 在页面执行过程中,对于每个不同的配置(set),只会执行一次
         */
        if (!isset($this->_fileCache[$module])) {
            $this->_fileCache[$module] = Com_Meta::getQueryByModule($module)
                ->execute()
                ->toArray();
        }

        /**
         * 将文件缓存转换为资源缓存
         * 在页面执行过程中,对于每个不同的资源,只会进行一次缓存
         */
        $args = array($module, $parent, $keys, $isPrefix);
        $name = $this->_getResourceName($args);
        if (isset($this->_resourceCache[$name])) {
            return $this->_resourceCache[$name];
        }

        empty($keys) && $keys = $this->_defaultKeys;

        // 配置树
        $treeData = array();
        $tree = new Qwin_Tree();
        $tree
            ->setParentDefaultValue(null)
            ->setId($keys[0])
            ->setParentId($keys[1])
            ->setName($keys[2]);
        foreach($this->_fileCache[$module] as $row) {
             $tree->addNode($row);
        }
        $tree->getAllList($treeData, $parent);

        $this->_resourceCache[$name]['null'] = '';
        // 添加前缀
        if ($isPrefix) {
            $lang = Qwin::call('-lang');
            $prefix1 = $lang->t('LBL_TREE_PREFIX_1');
            $prefix2 = $lang->t('LBL_TREE_PREFIX_2');
            $tree->setLayer($treeData);
            foreach ($treeData as $id) {
                $layer = $tree->getLayer($id);
                if (0 != $layer) {
                    $this->_resourceCache[$name][$id] = str_repeat($prefix1, $layer - 1) . $prefix2 . $tree->getValue($id);
                } else {
                    $this->_resourceCache[$name][$id] = $tree->getValue($id);
                }
            }
        } else {
            foreach($treeData as $id)
            {
                $this->_resourceCache[$name][$id] = $tree->getValue($id);
            }
        }

        return $this->_resourceCache[$name];
    }

    public function sanitise($code, $module, $parent = null, $keys = array(), $isPrefix = true)
    {
        $cache = $this->getTreeResource($module, $parent, $keys, $isPrefix);
        if (!isset($cache[$code])) {
            return '-';
        }
        return $cache[$code];
    }

    private function _getResourceName($args)
    {
        // 编码参数,参数作为缓存的标识
        $module = '';
        foreach($args as $key) {
            if (is_array($key)) {
                $module .= implode($key);
            } else {
                $module .= $key;
            }
        }
        return md5($module);
    }
}
