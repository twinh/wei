<?php
/**
 * Common
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
 * @since       2011-01-24 18:40:36
 */

class Mini_Common_Controller_Common extends Qwin_App_Controller
{
    public function actionIndex()
    {
        ini_set('zlib.output_compression', '0');

        $min_errorLogger = true;
        $min_allowDebugFlag = true;
        $min_documentRoot = '';
        $min_cacheFileLocking = true;
        $min_serveOptions['bubbleCssImports'] = false;
        $min_serveOptions['maxAge'] = 1800;
        $min_serveOptions['minApp']['groupsOnly'] = false;
        $min_symlinks = array();
        $min_uploaderHoursBehind = 0;
        $min_libPath = dirname(__FILE__) . '/lib';
        
        $min_cachePath = QWIN_ROOT_PATH . '/cache/mini';

        define('MINIFY_MIN_DIR', dirname(__FILE__));

        Minify::$uploaderHoursBehind = $min_uploaderHoursBehind;
        Minify::setCache($min_cachePath, $min_cacheFileLocking);

        if ($min_documentRoot) {
            $_SERVER['DOCUMENT_ROOT'] = $min_documentRoot;
        } elseif (0 === stripos(PHP_OS, 'win')) {
            Minify::setDocRoot(); // IIS may need help
        }

        $min_serveOptions['minifierOptions']['text/css']['symlinks'] = $min_symlinks;
        // auto-add targets to allowDirs
        foreach ($min_symlinks as $uri => $target) {
            $min_serveOptions['minApp']['allowDirs'][] = $target;
        }

        if ($min_allowDebugFlag) {
            if (! empty($_COOKIE['minDebug'])) {
                foreach (preg_split('/\\s+/', $_COOKIE['minDebug']) as $debugUri) {
                    if (false !== strpos($_SERVER['REQUEST_URI'], $debugUri)) {
                        $min_serveOptions['debug'] = true;
                        break;
                    }
                }
            }
        }

        if ($min_errorLogger) {
            if (true === $min_errorLogger) {
                Minify_Logger::setLogger(FirePHP::getInstance(true));
            } else {
                Minify_Logger::setLogger($min_errorLogger);
            }
        }

        // check for URI versioning
        if (preg_match('/&\\d/', $_SERVER['QUERY_STRING'])) {
            $min_serveOptions['maxAge'] = 31536000;
        }

        $request = Qwin::run('-request');
        $name = $request->g('g');
        $minifyHelper = Qwin::run('-manager')->getHelper('Minify', 'Common');
        $file = $minifyHelper->getCacheFile($name);
        if (file_exists($file)) {
            $min_serveOptions['minApp']['groups'][$name] = require $file;
        } else {
            exit('');
        }

        // serve!
        Minify::serve('MinApp', $min_serveOptions);
    }
}
