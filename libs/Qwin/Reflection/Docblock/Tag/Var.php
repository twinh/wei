<?php
/**
 * Var
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2011-09-06 14:31:39
 */

class Qwin_Reflection_Docblock_Tag_Var extends Qwin_Reflection_Docblock_Tag
{
    /**
     * @var string
     */
    protected $_type = null;

    /**
     * Constructor
     *
     * @param string $tagDocblockLine
     */
    public function __construct($tagDocblockLine)
    {
        $matches = array();

        if (!preg_match('#^@(\w+)\s+([\w|\\\]+)[ ]*(\s+.*)?#s', $tagDocblockLine, $matches)) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line is does not contain a valid tag');
        }

        if ($matches[1] != 'var') {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line is does not contain a valid @var tag');
        }

        $this->_name = 'var';
        $this->_type = $matches[2];
        
        if (isset($matches[3])) {
            $this->_parseDescription($matches[3]);
        }
    }

    /**
     * Get parameter variable type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
}
