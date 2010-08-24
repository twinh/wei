<?php
/**
 * Captcha
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
 * @version   2010-06-07
 * @since     2010-06-07
 */

class Trex_Trex_Controller_Default extends Qwin_Trex_Controller
{
    public function actionIndex()
    {
        $arr = Qwin::run('-arr');

        // 加盟数据
        $joinInSet = array(
            'namespace' => 'Admin',
            'module' => 'Article',
            'controller' => 'Article',
        );
        $joinInQuery = $this->meta->getQuery($joinInSet);
        $joinInData = $joinInQuery
            ->where('category_id = ?', '5c8f41a7-b99d-102d-95a1-07cffb7b76dd')
            ->orderBy('date_created DESC')
            ->limit(5)
            ->execute()
            ->toArray();

        // 产品数据
        $productSet = array(
            'namespace' => 'Admin',
            'module' => 'Product',
            'controller' => 'Product',
        );
        $productQuery = $this->meta->getQuery($productSet);
        $productData = $joinInQuery->where('category_id = ?', '93bf1cd0-3980-4e97-8bb3-9e67f1065f34')
            ->orderBy('date_created DESC')
            ->limit(2)
            ->execute()
            ->toArray();

        // 图片切换
        $linkSet = array(
            'namespace' => 'Admin',
            'module' => 'Link',
            'controller' => 'Link',
        );
        $linkQuery = $this->meta->getQuery($linkSet);
        $linkData = $linkQuery->where('category_id = ?', '65e2183e-528a-4de6-8739-e01a8148f43d')
            ->orderBy('order ASC, date_created DESC')
            ->execute()
            ->toArray();
        $linkData2 = array();
        foreach($linkData as $row)
        {
            $linkData2[] = array(
                'title' => $row['name'],
                'src' => $row['img_url'],
                'href' => $row['url'],
                'target' => $row['target'],
            );
        }
        $linkJsObject = $arr->jsonEncode($linkData2);

        // 加盟图片切换
        $linkData = $linkQuery->where('category_id = ?', '779a723c-5f1a-48f5-8503-6bc6e1d62375')
            ->orderBy('order ASC, date_created DESC')
            ->execute()
            ->toArray();
        $linkData2 = array();
        foreach($linkData as $row)
        {
            $linkData2[] = array(
                'title' => $row['name'],
                'src' => $row['img_url'],
                'href' => $row['url'],
                'target' => $row['target'],
            );
        }
         $linkJsObject2 = $arr->jsonEncode($linkData2);
        

        // 加载单变量
        $singleVar = require QWIN_ROOT_PATH . '/Cache/Php/List/SingleVar.php';

        $this->__view = array(
            'joinInData' => &$joinInData,
            'productData' => &$productData,
            'linkJsObject' => &$linkJsObject,
            'linkJsObject2' => &$linkJsObject2,
            'singleVar' => &$singleVar,
            'htmlTitle' => $singleVar['Html_Title'],

        );
        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        $this->__view_element = array(
            'content' => QWIN_ROOT_PATH . '/Public/template/index.php',
        );
        $this->loadView(QWIN_ROOT_PATH . '/Public/template/default.php');
    }
}
