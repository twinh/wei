<?php
/**
 * ReflectionComment
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
 * @since       2011-09-04 18:19:25
 * @todo        重新定义类名
 * @todo        完善export和__toString方法
 * @todo        参考其他的反射类,使行为一致
 */

class ReflectionComment implements Reflector
{
    /**
     * 注释内容
     * 
     * @var string
     */
    protected $_comment;
    
    /**
     * 标签数组
     * @var array
     */
    protected $_tags = array();
    
    public function __construct($comment)
    {
        $this->_comment = $comment;
        preg_match_all ('/\*\s+@(.+?)\s+(.+?)\n/', $comment, $data);
        if (!empty($data)) {
            $this->_tags = array_combine($data[1], $data[2]);
        }
    }
    
    public function getTag($name)
    {
        return isset($this->_tags[$name]) ? $this->_tags[$name] : null;
    }
    
    public static function export()
    {
        return null;
    }

    public function __toString()
    {
        return null;
    }
}