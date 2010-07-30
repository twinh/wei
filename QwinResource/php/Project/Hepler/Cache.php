<?php
/**
 * Cache
 *
 * Copyright (c) 2009-2010 Twin. All rights reserved.
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-7-18 17:58:17
 * @since     2010-7-18 17:58:17
 */

class Project_Helper_Cache
{
    public function setFileCacheBySetting($setting)
    {
        $ctrler = Qwin::run('-c');
        $fileCacheObj = Qwin::run('Qwin_Cache_File');
        $fileCacheObj->connect(ROOT_PATH . '/Cache/');
        /**
         * 去除无关的键名
         */
        $setting = array_intersect_key($setting, array(
            'namespace' => '',
            'module' => '',
            'controller' => '',
        ));

        $data = $ctrler->meta->getQuery($setting)
            ->execute()
            ->toArray();
        
        $cacheName = md5(implode('-', $setting));
        $fileCacheObj->set($cacheName, $data);
        return $data;
    }

    public function getFileCacheBySetting($setting)
    {
        $cacheName = md5(implode('-', $setting));
        return Qwin::run('Qwin_Cache_File')->get($cacheName);
    }
}
