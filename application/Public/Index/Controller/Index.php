<?php
/**
 * Index
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
 * @package     Qwin
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-02 23:48:05
 */

class Public_Index_Controller_Index extends Public_Controller
{
    public function actionIndex()
    {
        // 初始文章的查询对象
        $articleQuery = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Article',
                'controller' => 'Article',
            ), false);

        // 最新资讯
        $lastArticle = $articleQuery
            ->orderBy('date_created DESC')
            ->limit(9)
            ->fetchArray();

        // 热门资讯
        $hotArticle = $articleQuery
            ->orderBy('hit DESC')
            ->limit(9)
            ->fetchArray();

        // 社团动态
        $article1 = $articleQuery
            ->where('category_id = ?', 'a741ed15-0273-4742-a31e-462d28a235ab')
            ->orderBy('date_modified DESC')
            ->limit(9)
            ->fetchArray();

        // 商家动态
        $article2 = $articleQuery
            ->where('category_id = ?', '593fd224-4f40-45b4-a052-0a9d4e424c7e')
            ->fetchArray();

        // 外联案例
        $article3 = $articleQuery
            ->where('category_id = ?', '8270813c-04ee-4b97-b295-ef9e2a810bb8')
            ->fetchArray();

        // 成功活动
        $article4 = $articleQuery
            ->where('category_id = ?', 'c02ccb2c-7b3e-445f-ba95-a619b49ad9da')
            ->fetchArray();

        /**
         * 公告
         */
        $noctie = $articleQuery
            ->where('category_id = ?', 'notice')
            ->orderBy('date_created DESC')
            ->limit(8)
            ->fetchArray();

        /**
         * 初始化链接的查询对象
         */
        $linkQuery = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Link',
                'controller' => 'Link',
            ));
        
        /**
         * 图文热播
         */
        $slide = $linkQuery
            ->where('category_id = ?', 'slide')
            ->orderBy('date_created DESC')
            ->fetchArray();

        /**
         * 友情链接
         */
        $sharelink = $linkQuery
            ->where('category_id = ?', 'link-sharelink')
            ->execute();

        // 兼职招聘
        $jobQuery = $this
            ->metaHelper
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Job',
                'controller' => 'Job',
            ), false);
        $job1 = $jobQuery
            ->select('id, title, date_modified')
            ->where('type = 1')
            ->orderBy('date_modified DESC')
            ->limit(9)
            ->fetchArray();
        $job2 = $jobQuery
            ->where('type = 2')
            ->fetchArray();

        /**
         * 设置视图
         */
        $this->_view = array(
            'class' => 'Public_View',
            'element' => array(
                array('content', './view/index/index.php'),
            ),
            'data' => get_defined_vars(),
        );
    }
}
