<?php
/**
 * Default
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
 * @since       2010-08-04 10:58:17
 */

class Util_Minify_Controller extends Controller_Widget
{
    public function actionIndex()
    {
        $config = Qwin::config();
        ini_set('zlib.output_compression', '0');

        $options['maxAge'] = 1800;
        $options['minApp']['groupsOnly'] = true;

        // IIS may need help
        if (0 === stripos(PHP_OS, 'win')) {
            $_SERVER['DOCUMENT_ROOT'] = dirname($config['root']);
        }

        // 设置缓存类型
        $cachePath = $config['root'] . 'cache/minify';
        Minify::setCache($cachePath);

        // 调试
        if ($config['debug']) {
            $options['debug'] = true;
        }

        // 日志记录
        if ($config['log']) {
            Minify_Logger::setLogger(FirePHP::getInstance(true));
        }
        
        // 获取文件
        $request = Qwin::call('-request');
        $name = $request->get('g');
        $file = Qwin::call('-widget')->get('Minify')->getCacheFile($name);
        if (!is_file($file)) {
            Qwin::call('-widget')->get('Log4php')->info('minify file "' . $name . '" not found.');
            exit;
        }
        $options['minApp']['groups'][$name] = require $file;

        // serve!
        $result = Minify::serve('MinApp', $options);
        $this->_view->setDisplayed();
    }
}
