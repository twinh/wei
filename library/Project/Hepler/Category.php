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

class Project_Hepler_Category
{
    private $_resourceCache;
    private $_fileCache;

    function  __construct()
    {
        Qwin::load('Qwin_Tree');
    }

    public function getTreeResource($set, $parent = null, $keyArr = array('id', 'parent_id', 'name'), $isAddPrefix = true)
    {
        $name = md5(implode('-', $set));

        /**
         * 将文件缓存存到该对象中
         * 在页面执行过程中,对于每个不同的配置(set),只会执行一次
         */
        if(!isset($this->_fileCache[$name]))
        {
            // TODO 使用文件缓存
            $this->_fileCache[$name] = Qwin::run('Qwin_Trex_Metadata')
                ->getDoctrineQuery($set)
                ->execute()
                ->toArray();
            /*
            // 获取文件缓存
            $cacheObj = Qwin::run('Project_Helper_Cache');
            $this->_fileCache[$name] = $cacheObj->getFileCacheBySetting($set);*/
            /**
             * 设置文件缓存,不论页面运行多少次,只会在第一次执行
             */
            /*if(null == $this->_fileCache[$name])
            {
                $this->_fileCache[$name] = $cacheObj->setFileCacheBySetting($set);
            }*/
        }

        /**
         * 将文件缓存转换为资源缓存
         * 在页面执行过程中,对于每个不同的资源,只会进行一次缓存
         */
        $args = array($set, $parent, $keyArr, $isAddPrefix);
        $resourceName = $this->_getResourceName($args);
        if(isset($this->_resourceCache[$resourceName]))
        {
            return $this->_resourceCache[$resourceName];
        }
        
        // 配置树
        $treeData = array();
        $tree = new Qwin_Tree();
        $tree
            ->setParentDefaultValue(null)
            ->setId($keyArr[0])
            ->setParentId($keyArr[1])
            ->setName($keyArr[2]);
        foreach($this->_fileCache[$name] as $row)
        {
             $tree->addNode($row);
        }
        $tree->getAllList($treeData, $parent);

        $this->_resourceCache[$resourceName]['NULL'] = '';
        // 添加前缀
        if($isAddPrefix)
        {
            $tree->setLayer($treeData);
            foreach($treeData as $id)
            {
                $layer = $tree->getLayer($id);
                if(0 != $layer)
                {
                    $this->_resourceCache[$resourceName][$id] = str_repeat('┃', $layer - 1) . '┣' . $tree->getValue($id);
                } else {
                    $this->_resourceCache[$resourceName][$id] = $tree->getValue($id);
                }
            }
        } else {
            foreach($treeData as $id)
            {
                $this->_resourceCache[$resourceName][$id] = $tree->getValue($id);
            }
        }

        return $this->_resourceCache[$resourceName];
    }

    public function convertTreeResource($code, $set, $parent = null, $keyArr = array('id', 'parent_id', 'name'), $isAddPrefix = true)
    {
        $cache = $this->getTreeResource($set, $parent, $keyArr, $isAddPrefix);
        if(!isset($cache[$code]))
        {
            return '-';
        }
        return $cache[$code];
    }

    private function _getResourceName($args)
    {
        // 编码参数,参数作为缓存的标识
        $name = '';
        foreach($args as $key)
        {
            if(is_array($key))
            {
                $name .= implode($key);
            } else {
                $name .= $key;
            }
        }
        return md5($name);
    }
}
