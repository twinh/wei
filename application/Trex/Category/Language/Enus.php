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
 * @subpackage  Category
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-16 19:50:28
 */

class Trex_Category_Language_Enus extends Trex_Language
{
    public function __construct()
    {
        $this->_data += array(
            'LBL_FIELD_PARENT_ID' => 'Parent Category',
            'LBL_FIELD_TO_URL' => 'Jump to url',
            'LBL_FIELD_ORDER' => 'Order',
            'LBL_FIELD_IMAGE' => 'Image',
            'LBL_FIELD_IMAGE_2' => 'Image 2',
            'LBL_FIELD_SIGN' => 'Sign',

            'LBL_ACTION_ADD_SUBCATEGORY' => 'Add Subcategory',
            'LBL_ACTION_ADD_SPECIAL_ARTICLE' => 'Add Special Article',
            'LBL_ACTION_VIEW_SPECIAL_ARTICLE' => 'Show Special Article',
            'LBL_ACTION_VIEW_SPECIAL' => 'View Special',

            'LBL_MODULE_CATEGORY' => 'Category',
        );
    }
}
