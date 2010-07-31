<?php
 /**
 * 碎片
 *
 * Copyright (c) 2009 Twin. All rights reserved.
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
 * @version   2009-11-21 17:16
 * @since     2009-11-21 17:16
 */
class Clip
{
	static private $_cache;
	function load()
	{
		require ArrayCache::getClassPath('clip');
		foreach($_CACHE['class']['clip'] as $val)
		{
			self::$_cache[$val['var_name']] = $val['code'];
		}
	}
	
	function loadCode($name)
	{
		if(count(self::$_cache) == 0)
		{
			self::load();
		}
		return self::$_cache[$name];
	}
}
function C($name)
{
	return Clip::loadCode($name);
}
