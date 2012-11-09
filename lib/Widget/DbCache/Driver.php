<?php
/**
 * Widget Framework
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
 * Driver
 *
 * @package     Widget
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-07-26
 */
abstract class Widget_DbCache_Driver
{
    /**
     * Sql queries
     *
     * @var array
     */
    protected $_sqls = array();

    /**
     *  Get one sql query
     *
     * @param  string $type sql type, the key of the $this->_sqls array
     * @return string
     */
    public function getSql($type)
    {
        return isset($this->_sqls[$type]) ? $this->_sqls[$type] : false;
    }

    /**
     *  Get all sql queries
     *
     * @return array
     */
    public function getSqls()
    {
        return $this->_sqls;
    }
}
