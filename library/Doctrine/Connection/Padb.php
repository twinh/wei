<?php
/**
 * Padb
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
 * @subpackage  Padb
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-24 13:01:41
 */

class Doctrine_Connection_Padb extends Doctrine_Connection_Common
{
    /**
     * @var string $driverName
     */
    protected $driverName = 'Padb';

    public function connect()
    {
        $connected = parent::connect();

        Doctrine_Manager::getInstance()
                ->setAttribute(Doctrine_Core::ATTR_QUERY_CLASS, 'Qwin_Padb_Query');

        return $connected;
    }

    /**
     * 设置字符类型呢
     *
     * @param string $charset 字符类型
     */
    public function setCharset($charset)
    {
        $this->exec(array(
            array(null, 'setCharset'),
            array($charset),
        ));
        $this->dbh->_conn->setCharset($charset);
        parent::setCharset($charset);
    }

    /*public function __call($name, $arguments)
    {
        $this->exec(array(
            array(null, $name),
            $arguments,
        ));
    }*/
}
