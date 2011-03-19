<?php
/**
 * Package
 *
 * AciionController is controller with some default action,such as index,list,
 * add,edit,delete,view and so on.
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
 * @package     Com
 * @subpackage  Module
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2009-11-21 14:37
 */

/**
 * @see Qwin_Application_Module
 */
require_once 'Qwin/Application/Module.php';

class Com_Module extends Qwin_Application_Module
{
    public function __construct()
    {
        $config = Qwin::config();

        // 加载log4php
        if ($config['log']) {
            /* @var $log Logger */
            $log = Qwin::widget('log4php');
            $log->debug('The asc is ' . implode('/', $config['asc']));
        }

        // 关闭魔术引用
        ini_set('magic_quotes_runtime', 0);

        // 设置语言类
        Qwin::setMap('-lang', 'Qwin_Application_Language');
        
        /*if ($config['router']['enable']) {
            $router = Qwin::call('Qwin_Url_Router');
            $router->addList($config['router']['list']);
            $url = Qwin::call('-url', $router);
        }*/
    }
}
