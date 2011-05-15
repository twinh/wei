<?php
/**
 * Category
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
 * @package     Com
 * @subpackage  Category
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-07-12 17:01:37
 */

class Com_Category_Controller_Category extends Com_ActionController
{
    public function datasanitise($data)
    {
        $treeData = array();
        $newData = array();
        $tree = Qwin::call('Qwin_Tree');
        $tree->assignType('ARRAY');
        $tree->setParentDefaultValue($this->request->get('parentValue'));
        foreach($data as $row)
        {
            $tree->addNode($row);
        }

        $tree->getAllList($treeData);
        $tree->setLayer($treeData);
        foreach($treeData as $id)
        {
            $newData[] = $tree->getValue($id);
        }
        return $newData;
    }

    public function onAfterDb()
    {
        $fileCacheObj = Qwin::call('Qwin_Cache_File');
        $fileCacheObj->connect(Qwin_ROOT_PATH . '/cache/');
        $setting = array_intersect_key($this->_asc, array(
            'package' => '',
            'module' => '',
            'controller' => '',
        ));

        $data = $this->metaHelper->getQueryByAsc($setting)
            ->orderBy('order ASC')
            ->execute()
            ->toArray();

        $cacheName = md5(implode('-', $setting));
        $fileCacheObj->set($cacheName, $data);
    }

    public function getCategoryResource()
    {
        // 获取缓存数据
        //$cateogryCache = Qwin::call('Qwin_Cache_List')->getCache('Category');
        $fileCacheObj = Qwin::call('Qwin_Cache_File');
        $fileCacheObj->connect(Qwin_ROOT_PATH . '/cache/');
        $setting = array(
            'package' => 'Common',
            'module' => 'Category',
            'controller' => 'Category',
        );
        $cacheName = md5(implode('-', $setting));
        $cateogryCache = $fileCacheObj->get($cacheName);

        // 转换为树
        $treeData = array();
        $tree = new Qwin_Tree();
        $tree->setParentDefaultValue(NULL);
        foreach($cateogryCache as $row)
        {
            $tree->addNode($row);
        }
        $tree->getAllList($treeData, NULL);
        $tree->setLayer($treeData);

        // 转换为表单资源
        $categoryResource = array(
            'NULL' => '',
        );
        foreach($treeData as $id)
        {
            $layer = $tree->getLayer($id);
            if(0 != $layer)
            {
                $categoryResource[$id] = str_repeat('┃', $layer - 1) . '┣' . $tree->getValue($id);
            } else {
                $categoryResource[$id] = $tree->getValue($id);
            }
        }
        return $categoryResource;
    }
}
