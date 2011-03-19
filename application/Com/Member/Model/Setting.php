<?php
/**
 * Setting
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
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2011-01-14 10:41:58
 */

class Com_Member_Model_Setting
{
    /**
     * 风格目录
     * @var string
     */
    protected $_stylePath;

    public function __construct()
    {
        $this->_stylePath = QWIN_PATH . '/view/style';
    }

    public function getStyles($path = null)
    {
        if (!$path) {
            $path = $this->_stylePath;
        }
        $files = scandir($path);
        $styles = array();

        // 如果存在配置文件,表示有效风格
        foreach ($files as $file) {
            $styleFile = $path . '/' . $file . '/config.php';
            if (!is_file($styleFile)) {
                continue;
            }
            $styles[] = (require $styleFile) + array(
                'path' => $file
            );
        }

        // 重置风格路径
        $this->_stylePath = $path;
        return $styles;
    }

    public function getPath()
    {
        return $this->_stylePath;
    }
}
