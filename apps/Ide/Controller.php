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
 * @since       2011-05-28 12:42:31
 */

class Ide_Controller extends Qwin_Controller
{
    public function indexAction()
    {
        return true;
        $request = $this->_request;
        $lang = $this->getWidget()->call('Lang');
        if ($request->isJson()) {
            // 点击打开的文件夹
            $folder = $request->get('folder');
            if ('/' != substr($folder, -1)) {
                $folder .= '/';
            }
            
            // 模块所在路径
            $modulePath = Qwin::config('resource') . 'apps/' . $folder;
            // 返回的JSON数据
            $json = array();
            $fileJson = array();
            
            if (is_dir($modulePath)) {
                // 当前的目录是否为语言目录
                $isLang = '/lang/' === substr($folder, -6);
                if ($isLang) {
                    $langResource = Qwin::call('Ide_Option_Widget')->get('language');
                }
                foreach (scandir($modulePath) as $file) {
                    if (isset($file[0]) && '.' == $file[0]) {
                        continue;
                    }
                    // 文件夹,表示模块目录或特殊目录,如语言目录
                    if (!$isLang && is_dir($modulePath . '/' . $file)) {
                        if ('lang' === $file) {
                            $langJson = array(
                                'data' => array(
                                    'title' => $lang['LANGUAGE'] . $lang['FOLDER'],
                                    'attr' => array(
                                        'name' => $folder . $file . '/',
                                        'title' => $title,
                                    ),
                                ),
                                'state' => 'closed',
                            );
                        } else {
                            $lang->appendByModule($folder . $file);
                            $title = $lang['MOD' . strtoupper(strtr($folder, '/', '_') . $file)] . $lang['MODULE'];
                            $json[] = array(
                                'data' => array(
                                    'title' => $title,
                                    'attr' => array(
                                        'name' => $folder . $file . '/',
                                        'title' => $title,
                                    ),
                                ),
                                'state' => 'closed',
                            );
                        }
                    } else {
                        if ($isLang) {
                            $title = basename($file, '.php');
                            if (isset($langResource[$title])) {
                                $title = $langResource[$title]['name'];
                            }
                        } else {
                            switch ($file) {
                                case 'Controller.php' :
                                    $title = $lang['CONTROLLER'] . $lang['FILE'];
                                    break;
                                case 'Meta.php' :
                                    $title = $lang['META'] . $lang['FILE'];
                                    break;
                                case 'Model.php' :
                                    $title = $lang['MODEL'] . $lang['FILE'];
                                    break;
                                // 不是预料中的文件,可能是非法文件,跳过不显示
                                default :
                                    continue 2;
                            }
                        }
                        $fileJson[] = array(
                            'data' => array(
                                'title' => $title,
                            ),
                        );
                    }
                }
                // 首先显示模块文件夹,接着显示语言文件夹,最后显示文件
                array_push($json, $langJson);
                $json = array_merge($json, $fileJson);
            }
            $this->getView()->displayJson($json);
        }
    }
}
