<?php
/**
 * Article
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
 * @package     Public
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-03 7:55:33
 */

class Public_Article_Controller_Article extends Public_Controller
{
    public function actionList()
    {
        $id = $this->request->g('id');
        $page = intval($this->request->g('page'));
        $page <= 0 && $page = 1;
        $row = 10;

        if(null == $id)
        {
            return $this->setView('alert', '分类不存在');
        }

        $this->_query = $query = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Article',
                'controller' => 'Article'
            ), false);
        $data = $query
            ->where('category_id = ?', $id)
            ->orderBy('date_created DESC')
            ->offset(($page - 1) * $row)
            ->limit($row)
            ->execute();

        $page = array(
            'url' => '?action=List&id=' . $id,
            'nowPage' => $page,
            'count' => $query->count(),
            'row' => $row,
        );
        $pageCode = Project_Hepler_Page::create($page);


        /**
         * 右栏分类
         */
        $categoryQuery = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Category',
                'controller' => 'Category'
            ), false);
        $categoryData = $categoryQuery
            ->where('parent_id = ?', 'news')
            ->execute();

        /**
         * 右栏资讯
         */
        $lastArticle = $this->_getLastArticle();
        $hotArticle = $this->_getHotArticle();

        /**
         * 当前分类
         */
        $nowCategory = $categoryQuery
            ->where('id = ?' , $id)
            ->fetchOne();

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/article/list.php'),
                array('category', './view/article/category.php'),
                array('last-article', './view/article/last-article.php'),
                array('hot-article', './view/article/hot-article.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    public function actionView()
    {
        $id = $this->request->g('id');

        $this->_query = $query = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Article',
                'controller' => 'Article'
            ));
        $data = $query
            ->where('id = ?', $id)
            ->fetchOne();

        /**
         * 增加访问量
         */
        $data['hit'] += 1;
        $data->save();

        $nextData = $this->_getNextArticle($data);
        $preData = $this->_getPreArticle($data);

        /**
         * 右栏分类
         */
        $categoryQuery = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Category',
                'controller' => 'Category'
            ), false);
        $categoryData = $categoryQuery
            ->where('parent_id = ?', 'news')
            ->execute();

        /**
         * 右栏资讯
         */
        $lastArticle = $this->_getLastArticle();
        $hotArticle = $this->_getHotArticle();

        /**
         * 当前分类
         */

        $nowCategory = $categoryQuery
            ->where('id = ?' , $data['category_id'])
            ->fetchOne();

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/article/view.php'),
                array('category', './view/article/category.php'),
                array('last-article', './view/article/last-article.php'),
                array('hot-article', './view/article/hot-article.php'),
            ),
            'data' => get_defined_vars(),
        );
    }

    public function _getNextArticle($data)
    {
        return $this->_query
            ->where('date_created > ?', $data['date_created'])
            ->orderBy('date_created DESC')
            ->fetchOne();
    }

    public function _getPreArticle($data)
    {
        return $this->_query
            ->where('date_created < ?', $data['date_created'])
            ->orderBy('date_created DESC')
            ->fetchOne();
    }

    public function _getLastArticle()
    {
        return $this->_query
            ->removeDqlQueryPart('where')
            ->orderBy('date_created DESC')
            ->limit(6)
            ->execute();
    }

    public function _getHotArticle()
    {
        return $this->_query
            ->removeDqlQueryPart('where')
            ->orderBy('hit DESC')
            ->limit(6)
            ->execute();
    }
}

