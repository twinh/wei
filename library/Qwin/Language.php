<?php
/**
 * Language
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
 * @subpackage  Language
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-25 16:55:25
 */

class Qwin_Language
{
    /**
     * 相似语言对照表
     * @var array
     * @todo 对应规则
     */
    protected $_closeLanguage = array(
        'en' => 'en-US',
        'zh' => 'zh-CN',
        'cn' => 'zh-CN',
    );

    protected $_languageMap = array(
        'en-US', 'zh-CN',
    );

    public function __construct()
    {
        foreach($this->_languageMap as $language)
        {
            $this->_closeLanguage[strtolower(str_replace(array('-', '_'), '', $language))] = $language;
        }
    }

    public function toClassStyle($language)
    {
        return str_replace(array('-', '_'), '', $language);
    }

    public function tostandardStyle($language)
    {
        $language2 = strtolower($language);
        if(isset($this->_closeLanguage[$language2]))
        {
            return $this->_closeLanguage[$language2];
        }
        return $language;
    }
}