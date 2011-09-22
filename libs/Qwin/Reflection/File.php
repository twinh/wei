<?php
/**
 * Qwin Framework
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
 */

/**
 * File
 * 
 * @package     Qwin
 * @subpackage  Reflection
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-9-21 23:29:27
 */
class Qwin_Reflection_File extends Zend_Reflection_File
{
    /**
     * Return the docblock
     *
     * @param  string $reflectionClass Reflection class to use
     * @return Qwin_Reflection_Docblock
     */
    public function getDocblock($reflectionClass = 'Qwin_Reflection_Docblock')
    {
        return parent::getDocblock($reflectionClass);
    }

    /**
     * Return the reflection classes of the classes found inside this file
     *
     * @param  string $reflectionClass Name of reflection class to use for instances
     * @return array Array of Qwin_Reflection_Class instances
     */
    public function getClasses($reflectionClass = 'Qwin_Reflection_Class')
    {
        return parent::getClasses($reflectionClass);
    }

    /**
     * Return the reflection functions of the functions found inside this file
     *
     * @param  string $reflectionClass Name of reflection class to use for instances
     * @return array Array of Qwin_Reflection_Functions
     */
    public function getFunctions($reflectionClass = 'Qwin_Reflection_Function')
    {
        return parent::getFunctions($reflectionClass);
    }

    /**
     * Retrieve the reflection class of a given class found in this file
     *
     * @param  null|string $name
     * @param  string $reflectionClass Reflection class to use when creating reflection instance
     * @return Qwin_Reflection_Class
     * @throws Zend_Reflection_Exception for invalid class name or invalid reflection class
     */
    public function getClass($name = null, $reflectionClass = 'Qwin_Reflection_Class')
    {
        return parent::getClass($name, $reflectionClass);
    }
}