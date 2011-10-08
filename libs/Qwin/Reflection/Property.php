<?php
/**
 * Property
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
 * @since       2011-09-16 09:18:51
 */

class Qwin_Reflection_Property extends Zend_Reflection_Property
{
    public function toArray()
    {
        $docblock = $this->getDocComment();
        if (false != $docblock) {
            /* @var $var Qwin_Reflection_Docblock_Tag_Var */
            $var = $docblock->getTag('var');
            if (false != $var) {
                $type = $var->getType();
            } else {
                $type = 'void';
            }
        } else {
            $type = 'void';
        }

        return array(
            'name' => $this->getName(),
            'type' => $type,
            'varName' => '$' . $this->getName(),
            'modifiers' => implode(' ', Reflection::getModifierNames($this->getModifiers())),
        );
    }
    
    /**
     * Get declaring class reflection object
     *
     * @return Qwin_Reflection_Class
     */
    public function getDeclaringClass($reflectionClass = 'Qwin_Reflection_Class')
    {
        return parent::getDeclaringClass($reflectionClass);
    }

    /**
     * Get docblock comment
     *
     * @param  string $reflectionClass
     * @return Qwin_Reflection_Docblock|false False if no docblock defined
     */
    public function getDocComment($reflectionClass = 'Qwin_Reflection_Docblock')
    {
        return parent::getDocComment($reflectionClass);
    }
}