<?php
/**
 * Interface
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
 * @subpackage  Struct
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       v0.5.1 2010-12-10 09:49:15
 */

interface Qwin_Struct_Interface
{
    /**
     * 为结构体增加一个元素
     *
     * @param mixed $element 元素
     * @param array $option 附加选项
     */
    public function addElement($element, array $option = null);

    /**
     * 移除结构体中的一个元素
     *
     * @param string $name 元素名称
     */
    public function removeElement($name);

    /**
     * 检查元素是否存在
     *
     * @param string $name 元素名称
     */
    public function isElementExists($name);

    /**
     * 清空结构体
     */
    public function clear();

    /**
     * 直接解析数组为结构体
     *
     * @param array $array 数组
     */
    public function fromArray(array $array);

    /**
     * 不经过解析,将数组加入结构体中
     * 注意,应该正确使用该方法
     *
     * @param array $array 数组,数组应该是合法的,有正确结构的,
     *                     例如从数据库读出的数据,经过转换的数据,经过验证的数据
     */
    public function fromTrustedArray(array $array);

    /**
     * 将结构体转换为数组
     */
    public function toArray();

    /**
     * 检验数据是否为合法结构
     *
     * @param array $data
     */
    public function valid($data);
}
