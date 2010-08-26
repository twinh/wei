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
 * @version   2010-7-12 17:01:37
 * @since     2010-7-12 17:01:37
 */

class Trex_Category_Controller_Category extends Trex_Controller
{
    public function dataConverter($data)
    {
        $treeData = array();
        $newData = array();
        $tree = Qwin::run('Qwin_Tree');
        $tree->setDataType('ARRAY');
        $tree->setParentDefaultValue($this->_request->g('parentValue'));
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
        //Qwin::run('Project_Helper_Cache')->setFileCacheBySetting($this->_set);
        $fileCacheObj = Qwin::run('Qwin_Cache_File');
        $fileCacheObj->connect(QWIN_ROOT_PATH . '/cache/');
        $setting = array_intersect_key($this->_set, array(
            'namespace' => '',
            'module' => '',
            'controller' => '',
        ));

        $data = $this->_meta->getDoctrineQuery($setting)
            ->orderBy('order ASC')
            ->execute()
            ->toArray();

        $cacheName = md5(implode('-', $setting));
        $fileCacheObj->set($cacheName, $data);
    }

    public function convertListOperation($value, $name, $data, $copyData)
    {
        $primaryKey = $this->_meta['db']['primaryKey'];
        $url = $this->_url->createUrl($this->_set, array('action' => 'Add', '_data[parent_id]' => $data[$primaryKey]));
        $html = Qwin_Helper_Html::jQueryButton($url, $this->_lang->t('LBL_ACTION_ADD_SUBCATEGORY'), 'ui-icon-plusthick')
              . parent::convertListOperation($value, $name, $data, $copyData);
        return $html;
    }

    public function convertListName($val, $name, $data, $copyData)
    {
        if(NULL != $copyData['parent_id'])
        {
            // 缓存Tree对象
            if(!isset($this->treeObj))
            {
                $this->treeObj = Qwin::run('Qwin_Tree');
            }
            $layer = $this->treeObj->getLayer($data['id']);
            // 只有一层
            if(0 >= $layer)
            {
                return $val;
            } else {
                return str_repeat('┃', $layer - 1) . '┣' . $val;
            }
        }
        return $val;
    }
    
    public function convertDbParentId($val, $name, $data)
    {
        '0' == $val && $val = 'NULL';
        return $val;
    }

    public function getCategoryResource()
    {
        // 获取缓存数据
        //$cateogryCache = Qwin::run('Qwin_Cache_List')->getCache('Category');
        $fileCacheObj = Qwin::run('Qwin_Cache_File');
        $fileCacheObj->connect(QWIN_ROOT_PATH . '/cache/');
        $setting = array(
            'namespace' => 'Trex',
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
