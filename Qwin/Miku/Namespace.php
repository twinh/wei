<?php
/**
 * Namespace
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
 * @subpackage  Namespace
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-4-6 20:47:47
 */

class Qwin_Miku_Namespace
{
    /**
     * 网站配置数组
     * @var array
     */
    protected $_config;

    public function __construct()
    {
        $ini = Qwin::run('Qwin_Miku_Setup');
        $this->_config = $ini->getConfig();

        /**
         * 设置会话
         */
        Qwin::addMap('-ses', 'Qwin_Session');
        $namespace = md5($_SERVER['SERVER_NAME'] . $this->_config['project']['name']);
        Qwin::run('-ses', $namespace);

        /**
         * 打开缓冲区
         */
        ob_start();
    }

    public function  __destruct()
    {
        /**
         * 获取缓冲数据,输出并清理
         */
        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        echo $output;
        unset($output);
    }
}
