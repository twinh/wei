<?php
/**
 * Enus
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
 * @since       2010-08-16 19:03:38
 */

class Trex_Article_Language_Enus extends Trex_Language
{
    public function __construct()
    {
        $this->_data +=  array(
            'LBL_FIELD_PARENT_ID' => 'Parent Id',
            'LBL_FIELD_ANCESTOR_ID' => 'Ancestor Id',
            'LBL_FIELD_META' => 'Metadata',
            'LBL_FIELD_TO_URL' => 'Jump to',
            'LBL_FIELD_HIT' => 'Hit',
            'LBL_FIELD_PAGE_NAME' => 'Page Name',
            'LBL_FIELD_CONTENT_PREVIEW' => 'Preview',
            'LBL_FIELD_CATEGORY_ID' => 'Category Id',
            'LBL_FIELD_CATEGORY' => 'Category',
            'LBL_FIELD_TEMPLATE' => 'Template',
            'LBL_FIELD_AUTHOR' => 'Author',
            'LBL_FIELD_THUMB' => 'Thumb',
            'LBL_FIELD_ORDER' => 'Order',
            'LBL_FIELD_IS_POSTED' => 'Posted?',
            'LBL_FIELD_IS_INDEX' => 'Show in index?',
            'LBL_FIELD_JUMP_TO_URL' => 'Jump To Url',
            'LBL_FIELD_SHORT_TITLE' => 'Short Title',
            'LBL_FIELD_META_KEYWORDS' => 'Keywords',
            'LBL_FIELD_META_DESCRIPTION' => 'Description',
            'LBL_FIELD_TITLE_STYLE' => 'Title Style',
            'LBL_FIELD_TITLE_COLOR' => 'Title Color',
            'LBL_FIELD_POST_DATA' => 'Post Date',
            'LBL_FIELD_ARTICLE_ID' => 'Article Id',

            'LBL_GROUP_PAGE_DATA' => 'Page Data',
            'LBL_GROUP_SETTING_DATA' => 'Setting Data',
            'LBL_GROUP_META_DATA' => 'Seo Data',

            'LBL_MODULE_ARTICLE' => 'Article',
            'LBL_MODULE_ARTICLE_CATEGORY' => 'Article Category',

            'LBL_ACTION_CREATE_HTML' => 'Create Html Page',

            'MSG_TEMPLATE_NOT_EXISTS' => 'The article\'s template is not exists, please return and modify!',
            'LBL_CREATE_ALL_HMTL' => 'Create all html page',
        );
    }
}
