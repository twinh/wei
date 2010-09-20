<?php
/**
 * Metadata
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @subpackage  Metadata
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-7-28 17:26:04
 */

class Trex_Metadata extends Qwin_Trex_Metadata
{
    /**
     * 设置基本的元数据,包括编号,创建时间,修改时间和操作.
     */
    public function setCommonMetadata()
    {
         return $this->setIdMetadata()
             ->setCreatedData()
             ->setModifiedData()
             ->setOperationMetadata();
    }

    /**
     * 设置编号域的元数据配置,编号是最为常见的域
     *
     * @return obejct 当前对象
     */
    public function setIdMetadata()
    {
        $this->addField(array(
            'id' => array(
                'basic' => array(
                    'order' => -1,
                ),
                'form' => array(
                    '_type' => 'hidden',
                    'name' => 'id',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isList' => 1,
                    'isDbField' => 1,
                    'isDbQuery' => 1,
                    'isReadonly' => 0,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置创建域,包括创建人和创建时间
     *
     * @return object 当前对象
     */
    public function setCreatedData()
    {
        $this->addField(array(
            'created_by' => array(
                'basic' => array(
                    'order' => 1020,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isList' => 0,
                    'isReadonly' => 1,
                ),
            ),
            'date_created' => array(
                'basic' => array(
                    'order' => 1060,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isReadonly' => 1,
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置修改域,包括修改人和修改时间
     *
     * @return object 当前对象
     */
    public function setModifiedData()
    {
        $this->addField(array(
            'modified_by' => array(
                'basic' => array(
                    'order' => 1040,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isList' => 0,
                ),
            ),
            'date_modified' => array(
                'basic' => array(
                    'order' => 1080,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
            ),
        ));
        return $this;
    }

    /**
     * 设置操作域的元数据配置,操作域主要用于列表
     *
     * @return obejct 当前对象
     */
    public function setOperationMetadata()
    {
        $this->addField(array(
            'operation' => array(
                'basic' => array(
                    'order' => 1100,
                ),
                'form' => array(
                    '_type' => 'custom',
                ),
                'attr' => array(
                    'isLink' => 0,
                    'isDbField' => 0,
                    'isDbQuery' => 0,
                    'isView' => 0,
                ),
            ),
        ));
        return $this;
    }
}
