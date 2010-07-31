<?php
 /**
 * 谷歌翻译接口
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
 * @version   2009-12-18 00:25
 * @since     2009-12-18 00:25
 * @todo      cache, more..
 */

class GoolgeTranslateApi
{
	function translate($data, $src_lang = 'en', $dest_lang = 'zh')
	{
		$data = urlencode($data);
		$json = file_get_contents('http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' . $data . '&langpair=' . $src_lang . '%7C' . $dest_lang);
		$obj = qw('-arr')->jsonDecode($json, 'php');
		return $obj->responseData->translatedText;
	}
}
?>
