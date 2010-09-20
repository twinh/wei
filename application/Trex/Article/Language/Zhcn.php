<?php
/**
 * 中文简体语言
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
 * @package     Trex
 * @subpackage  Article
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 17:39:25
 */

class Trex_Article_Language_Zhcn extends Trex_Language_Zhcn
{
    public function __construct()
    {
        parent::__construct();
        $this->_data += array(
            'LBL_FIELD_PARENT_ID' => '父分类',
            'LBL_FIELD_ANCESTOR_ID' => '祖先分类',
            'LBL_FIELD_META' => 'Meta数据',
            'LBL_FIELD_TO_URL' => '跳转',
            'LBL_FIELD_HIT' => '人气',
            'LBL_FIELD_PAGE_NAME' => '页面名称',
            'LBL_FIELD_CONTENT_PREVIEW' => '内容预览',
            'LBL_FIELD_CATEGORY_ID' => '分类编号',
            'LBL_FIELD_CATEGORY' => '分类',
            'LBL_FIELD_TEMPLATE' => '模板',
            'LBL_FIELD_AUTHOR' => '作者',
            'LBL_FIELD_THUMB' => '缩略图',
            'LBL_FIELD_ORDER' => '顺序',
            'LBL_FIELD_IS_POSTED' => '是否发布',
            'LBL_FIELD_IS_INDEX' => '是否显示在首页',
            'LBL_FIELD_CONTENT' => '内容',
            'LBL_FIELD_JUMP_TO_URL' => '跳转到',

            'LBL_GROUP_PAGE_DATA' => '页面资料',
            'LBL_GROUP_SETTING_DATA' => '配置资料',

            'LBL_MODULE_ARTICLE' => '文章',
            'LBL_MODULE_ARTICLE_CATEGORY' => '文章分类',

            'LBL_ACTION_CREATE_HTML' => '生成静态页面',

            'MSG_TEMPLATE_NOT_EXISTS' => '文章模板不存在,请返回修改!',
            'LBL_CREATE_ALL_HMTL' => '生成所有的静态页面',

            'LBL_FIELD_AREA' => '地区',

            'LBL_FIELD_SHORT_TITLE' => '简短标题',
            'LBL_FIELD_META_KEYWORDS' => '关键字',
            'LBL_FIELD_META_DESCRIPTION' => '描述',
            'LBL_FIELD_TITLE_STYLE' => '标题样式',
            'LBL_FIELD_TITLE_COLOR' => '标题颜色',
            'LBL_FIELD_POST_DATA' => '发布日期',
            'LBL_FIELD_ARTICLE_ID' => '文章编号',
            'LBL_GROUP_META_DATA' => 'Seo资料',
        );
    }
}
