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
 * PgSql
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2012-07-26
 */
class Qwin_DbCache_PgSql extends Qwin_DbCache_Driver
{
    /**
     * Sql queries
     *
     * @var array
     */
    protected $_sqls = array(
        'prepare' => null,
        'checkTable' => 'SELECT 1 from %s',
        'create' => 'CREATE TABLE %s (id varchar(255) PRIMARY KEY, value text, "lastModified" int4, expire int4)',
        'get' => 'SELECT value FROM %s WHERE id = :id AND expire > :expire',
        'set' => 'INSERT INTO %s (id, value, "lastModified", expire) VALUES (:id, :value, :lastModified, :expire)',
        'remove' => 'DELETE FROM %s WHERE id = :id',
        'replace' => 'UPDATE %s SET value = :value, "lastModified" = :lastModified, expire = :expire WHERE id = :id',
        'increment' => 'UPDATE %s SET value = value::int + :offset, "lastModified" = :lastModified WHERE id = :id',
        'clear' => 'DELETE FROM %s',
        'drop' => 'DROP TABLE %s',
    );
}
