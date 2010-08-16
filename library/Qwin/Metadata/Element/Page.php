<?php
/**
 * Page
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-27 12:44:44
 */

class Qwin_Metadata_Element_Page extends Qwin_Metadata_Element_Abstract
{
    public function getSampleData()
    {
        return array(
            'title' => null,
            'description' => null,
        );
    }

    public function setTitle()
    {
        
    }

    /**
     * 转换语言
     *
     * @param array $language 用于转换的语言
     * @return Qwin_Metadata_Element_Field 当前类
     */
    public function translate($language)
    {
        $this->_data['titleCode'] = $this->_data['title'];
        if(isset($language[$this->_data['title']]))
        {
            $this->_data['title'] = $language[$this->_data['title']];
        }

        $this->_data['descriptionCode'] = $this->_data['description'];
        if(isset($language[$this->_data['description']]))
        {
            $this->_data['description'] = $language[$this->_data['description']];
        }

        return $this;
    }
}
