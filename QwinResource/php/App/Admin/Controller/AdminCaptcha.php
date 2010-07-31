<?php
 /**
 * 验证码
 *
 * 验证码前台控制器
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
 * @version   2009-11-21 14:36
 * @since     2009-11-21 14:36
 */

class Admin_Controller_Captcha extends Qwin_Miku_Controller
{
	function actionDefault()
	{
		session_register("verify_code");
		$number = mt_rand(10000,99999);
		echo $this->getVerifyCode($number);
		$_SESSION['verify_code'] = $number;
	}
	
	function getVerifyCode($content="")
	{
		$content = preg_replace( "/(\w)/", "\\1 ", $content );
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache"); 
		header("Content-Type: image/jpeg");
		
		$image_x = 73;
		$image_y = 26;
		$im  = imagecreatetruecolor($image_x, $image_y);
		$white  = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $white);

		for ($i=1; $i<=100; $i++) { 
			imagestring($im,1,mt_rand(1,$image_y),mt_rand(1,$image_x),"*", imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255))); 
		} 
		for ($i=0;$i<strlen($content);$i++){ 
			imagestring($im, mt_rand(3,5),$i*$image_y/4+mt_rand(2,4),mt_rand(1,5), $content[$i], imagecolorallocate($im,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200))); 
		}

		imagejpeg($im);
		imagedestroy($im);
	}
}
