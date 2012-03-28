<?php
/**
 * Controller
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
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
 * @since       2011-04-12 06:39:26
 */

class Util_Filetree_Controller extends Qwin_Application_Controller
{
    /**
     * jQuery File Tree PHP Connector
     *
     * Version 1.01
     *
     * Cory S.N. LaViska
     * A Beautiful Site (http://abeautifulsite.net/)
     * 24 March 2008
     *
     * History:
     * 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
     * 1.00 - released (24 March 2008)
     *
     * Output a list of files for jQuery File Tree
     *
     * Fixed By Twin 2009-04-04
     * 修改 htmlentities 为 htmlspecialchars
     * 添加 iconv 转换为utf-8 简体中文
     */
    public function actionIndex()
    {
	$request = Qwin::call('-request');
        $dir = urldecode($request->post('dir'));
        $root = '';

        // check
        if (substr($dir, 0, 7) != 'upload/') {
            exit('Forbidden');
        }

        if (file_exists($root . $dir)) {
            $files = scandir($root . $dir);
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $dir . $file) &&
                        $file != '.' &&
                        $file != '..' &&
                        is_dir($root . $dir . $file) &&
                        substr($file, 0, 1) != '.' &&
                        substr($file, 0, 1) != '_'
                    ){
                        $file = iconv("GBK", "UTF-8", $file);
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlspecialchars($dir . $file) . "/\">" . htmlspecialchars($file) . "</a></li>";
                    }
                }
                // All files
                foreach( $files as $file ) {
                    if (
                        file_exists($root . $dir . $file) &&
                        $file != '.' &&
                        $file != '..' &&
                        !is_dir($root . $dir . $file) &&
                        substr($file, 0, 1) != '.' &&
                        substr($file, 0, 1) != '_'
                    ) {
                        $ext = preg_replace('/^.*\./', '', $file);
                        $ext = strtolower($ext);
                        $file = iconv("GBK", "UTF-8", $file);
                        echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlspecialchars($dir . $file) . "\">" . htmlspecialchars($file) . "</a></li>";
                    }
                }
                echo "</ul>";
            }
        }
    }
}
