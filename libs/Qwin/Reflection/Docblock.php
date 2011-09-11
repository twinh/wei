<?php
/**
 * Docblock
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-09-08 00:42:07
 */

class Qwin_Reflection_Docblock extends Zend_Reflection_Docblock
{
    /**
     * Parse the docblock
     *
     * @return void
     */
    protected function _parse()
    {
        $docComment = $this->_docComment;

        // First remove doc block line starters
        $docComment = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
        $docComment = ltrim($docComment, "\r\n"); // @todo should be changed to remove first and last empty line

        $this->_cleanDocComment = $docComment;
        
        /**
         * 与Zend不同,Qwin认为标签的内容可以跨行 ,从第二行开始认定为长描述
         * @tagName type description and so on
         *          tag content
         */
        
        // 获取简短和详细描述
        $lineNumber = 0;
        while (($newlinePos = strpos($docComment, "\n")) !== false) {
            $lineNumber++;
            $line = substr($docComment, 0, $newlinePos);
            
            if (!preg_match('#^[ ]*@(\w+)(\s|$)#', $line)) {
                // 第一二行,如果开头不为标签,则认为是简短描述
                if ($lineNumber < 3) {
                    $this->_shortDescription .= $line . "\n";
                // 从第三行开始到标签出现,均认为是详细描述
                } else {
                    $this->_longDescription .= $line . "\n";
                }
            } else {
                break;
            }
            
            $docComment = substr($docComment, $newlinePos + 1);
        }

        // 查找所有的标签
        preg_match_all('#@(\w+.*?)\n(?=[ ]*@[\w+.*?])#s', $docComment . '@end', $matches);
        foreach ($matches[0] as $match) {
            $this->_tags[] = Qwin_Reflection_Docblock_Tag::factory($match);
        }

        $this->_shortDescription = rtrim($this->_shortDescription);
        $this->_longDescription  = rtrim($this->_longDescription);
    }
}
