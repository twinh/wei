<?php
/**
 * Tag
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
 * @since       2011-09-08 00:45:22
 */

class Qwin_Reflection_Docblock_Tag extends Zend_Reflection_Docblock_Tag
{
    /**
     * @var array Array of Class names
     * @todo add more type
     */
    protected static $_tagClasses = array(
        'param'  => 'Zend_Reflection_Docblock_Tag_Param',
        'return' => 'Qwin_Reflection_Docblock_Tag_Return',
        'var'    => 'Qwin_Reflection_Docblock_Tag_Var',
        'event'  => 'Qwin_Reflection_Docblock_Tag_Event',
    );


    /**
     * @var string
     */
    protected $_shortDescription = null;
    
    /**
     * @var string
     */
    protected $_longDescription = null;
    
    /**
     * return array format of tag data
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'longDescription' => $this->getLongDescription(),
            'shortDescription' => $this->getShortDescription(),
        );
    }

    /**
     * Factory: Create the appropriate annotation tag object
     *
     * @param  string $tagDocblockLine
     * @return Zend_Reflection_Docblock_Tag
     */
    public static function factory($tagDocblockLine)
    {
        $matches = array();

        if (!preg_match('#^@(\w+)(\s|$)#', $tagDocblockLine, $matches)) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('No valid tag name found within provided docblock line.');
        }

        $tagName = $matches[1];
        if (array_key_exists($tagName, self::$_tagClasses)) {
            $tagClass = self::$_tagClasses[$tagName];
            return new $tagClass($tagDocblockLine);
        }
        return new self($tagDocblockLine);
    }


    /**
     * Constructor
     *
     * @param  string $tagDocblockLine
     * @return void
     */
    public function __construct($tagDocblockLine)
    {
        $matches = array();

        // find the line
        if (!preg_match('#^@(\w+)[ \s]+(.+?)$#s', $tagDocblockLine, $matches)) {
            require_once 'Zend/Reflection/Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line does not contain a valid tag');
        }

        $this->_name = $matches[1];
        if (isset($matches[2]) && $matches[2]) {
            $this->_description = $matches[2];
        }
    }
    
    /**
     * Get docblock short description
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->_shortDescription;
    }

    /**
     * Get docblock long description
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->_longDescription;
    }
    
    /**
     * Parse the description
     * 
     * @param string $description
     */
    protected function _parseDescription($description)
    {
        $this->_description = $description;
        if(false !== ($pos = strpos($description, "\n"))) {
            $this->_shortDescription = substr($description, 0, $pos);
            $this->_longDescription = substr($description, $pos + 1);
        } else {
            $this->_shortDescription = $description;
        }
    }
}
