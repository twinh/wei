<?php
/**
 * Captcha
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
 * @subpackage  
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-27 10:48:59
 */

class Project_Helper_Captcha
{
    public static function create($content)
    {
        $content = preg_replace( "/(\w)/", "\\1 ", $content );
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: image/jpeg");

        $image_x = 60;
        $image_y = 25;
        $im  = imagecreatetruecolor($image_x, $image_y);
        $white  = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $white);

        for ($i=1; $i<=100; $i++) {
                imagestring($im,1,mt_rand(1,$image_y),mt_rand(1,$image_x),"*", imagecolorallocate($im,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255)));
        }
        for ($i=0;$i<strlen($content);$i++){
                imagestring($im, mt_rand(3,5),$i*$image_y/4+mt_rand(2,4),mt_rand(1,5), $content[$i], imagecolorallocate($im,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200)));
        }

        imagejpeg($im);
        imagedestroy($im);
    }
}
