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

class Default_Category_Controller_Category extends Qwin_Trex_Controller
{
    /**
     * 列表
     */
    public function actionDefault()
    {
        return Qwin::run('Qwin_Trex_Action_List');
    }

    /**
     * 添加
     */
    public function actionAdd()
    {
        return Qwin::run('Qwin_Trex_Action_Add');
    }

    /**
     * 编辑
     */
    public function actionEdit()
    {
        return Qwin::run('Qwin_Trex_Action_Edit');
    }

    /**
     * 删除
     * @todo 删除时应该将自分类也删除,不残留在数据库 or 提供一个清理函数,清理数据库中无关联的数据
     */
    public function actionDelete()
    {
        return Qwin::run('Qwin_Trex_Action_Delete');
    }

    /**
     * 列表的 json 数据
     */
    public function actionJsonList()
    {
        $gpc = Qwin::run('-gpc');
        $meta = &$this->__meta;
        $primaryKey = $meta['db']['primaryKey'];
        $nowPage = 1;
        $rowNum = 10000;

        // 修改主键域配置,以适应jqgrid
        $meta['field'][$primaryKey]['list'] = array(
            'isUrlQuery' => false,
            'isList' => true,
            'isSqlField' => true,
            'isSqlQuery' => true,
        );

        // 显示在列表页的域
        $listField = $this->meta->getSettingList($meta['field'], 'isList');
        
        
        $query = $this->meta->getQuery($this->__query);
        // 增加排序(order)语句
        $query = $this->meta->addOrderToQuery($meta, $query);
        // 增加查找(where)语句
        $query = $this->meta->addWhereToQuery($meta, $query);
        $dbData = $query->execute()->toArray();
        $count = $query->count();

        $treeData = array();
        $tree = Qwin::run('Qwin_Tree');
        $tree->setDataType('ARRAY');
        // TODO
        $tree->setParentDefaultValue($gpc->g('parentValue'));
        foreach($dbData as $row)
        {
            $tree->addNode($row);
        }
        
        $tree->getAllList($treeData);
        $tree->setLayer($treeData);
        
        foreach($treeData as $id)
        {
            $data[] = $tree->getValue($id);
        }
        // 根据配置和控制器中的对应方法转换数据
        $data = $this->meta->convertMultiData($meta['field'], 'list', $data);

        
        $i = 0;
        $jqgridData = array();
        foreach($data as $row)
        {
            $jqgridData[$i][$primaryKey] = $row[$primaryKey];
            foreach($listField as $field)
            {
                $jqgridData[$i]['cell'][] = $row[$field];
            }
            $i++;
        }
        
        $json_data = array(
            'page' => $nowPage,
            // 总页面数
            'total' => ceil($count / $rowNum),
            'records' => $count,
            'rows' => $jqgridData,
        );
        echo Qwin::run('-arr')->jsonEncode($json_data);
    }

    /**
     * 查看
     */
    public function actionShow()
    {
        return Qwin::run('Qwin_Trex_Action_Show');
    }


    public function onAfterDb()
    {
        //Qwin::run('Project_Helper_Cache')->setFileCacheBySetting($this->__query);
        $fileCacheObj = Qwin::run('Qwin_Cache_File');
        $fileCacheObj->connect(ROOT_PATH . '/Cache/');
        $setting = array_intersect_key($this->__query, array(
            'namespace' => '',
            'module' => '',
            'controller' => '',
        ));

        $data = $this->meta->getQuery($setting)
            ->orderBy('order ASC')
            ->execute()
            ->toArray();

        $cacheName = md5(implode('-', $setting));
        $fileCacheObj->set($cacheName, $data);
    }

    public function convertListOperation($val, $name, $data, $copyData)
    {
        $html = '';
        if('special' == $copyData['parent_id'])
        {
            $html .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_ADD_SPECIAL_ARTICLE') .'" href="' . url(array( $this->__query['namespace'],  'Article',  'Article', 'Add'), array('sign' => $data['id'])) . '"><span class="ui-icon ui-icon-plus"></span></a>';
            $html .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_VIEW_SPECIAL_ARTICLE') .'" href="' . url(array( $this->__query['namespace'],  'Article',  'Article'), array('searchField' => 'category_2', 'searchValue' => $data['id'])) . '"><span class="ui-icon ui-icon-note"></span></a>';
            $html .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_VIEW_SPECIAL') .'" target="_blank" href="Special.php?name=' . $data['sign'] . '"><span class="ui-icon ui-icon-gear"></span></a>';
        }
        $html .= '<a class="ui-state-default ui-jqgrid-icon ui-corner-all" title="' . $this->t('LBL_ACTION_ADD_SUBCATEGORY') .'" href="' . url(array( $this->__query['namespace'],  $this->__query['module'],  $this->__query['controller'], 'Add'), array('data[parent_id]' => $data[$this->__meta['db']['primaryKey']])) . '"><span class="ui-icon ui-icon-plusthick"></span></a>';
        $html .= $this->meta->getOperationLink(
            $this->__meta['db']['primaryKey'],
            $data[$this->__meta['db']['primaryKey']],
            $this->__query
        );
        return $html;
    }

    public function convertDbId($val)
    {
        return $this->Qwin_converter_String->getUuid($val);
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

    public function convertDbDateCreated()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
    }

    public function convertDbDateModified()
    {
        return date('Y-m-d H:i:s', TIMESTAMP);
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
        $fileCacheObj->connect(ROOT_PATH . '/Cache/');
        $setting = array(
            'namespace' => 'Default',
            'module' => 'Category',
            'controller' => 'Category',
        );
        $cacheName = md5(implode('-', $setting));
        $cateogryCache = $fileCacheObj->get($cacheName);

        // 转换为树
        $treeData = array();
        Qwin::load('Qwin_Tree');
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

    public function convertAddOrder()
    {
        $class = Qwin::run('-ini')->getClassName('Model', $this->__query);
        return $this->meta->getInitalOrder($class);
    }
}
