<?php
/**
 * Controller
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
 * @since       2011-9-9 12:52:21
 */

class Sync_Controller extends Controller_Widget
{
    protected $_defaults = array(
        'repository'    => '127.0.0.1\backup\\',
        'workCopy'      => 'E:\work\website\qwin2\\',
        'username'      => null,
        'password'      => null,
        ''
    );
    
    public function actionIndex()
    {
        
    }
    
    public function actionInit()
    {
        $path = $this->_request->get('path');
        if ('/' != substr($path, -1)) {
            $path .= '/';
        }
        if (!is_dir($path)) {
            throw new Exception('Path "' . $path . '" not found.');
        }
        
        $syncPath = $path . '.qws/';
        if (!is_dir($syncPath)) {
            mkdir($syncPath);
        }
        
        $files = $this->_getFiles($path);
        Qwin_Util_File::writeArray($path . '.qws/files.php', $files);       
    }
    
    protected function _getFiles($path = '')
    {
        $files = array();
        $results = scandir($path);
        foreach ($results as $result) {
            if ('.' == $result || '..' == $result) {
                continue;
            }
            
            if (is_file($file = $path . $result)) {
                $files[] = array(
                    'file' => $file,
                    'sha1' => sha1_file($file),
                );
            } else {
                $files = array_merge($files, $this->_getFiles($path . $result . '/'));
            }
        }
        return $files;
    }
    
    /**
     * 检出
     * 将版本库的数据取出到工作拷贝目录
     */
    public function actionCheckout()
    {
        if (!is_dir($this->_options['workCopy'])) {
            throw new Exception('Work copy "' . $this->_options['workCopy'] . '" not found.');
        }
        
        file_get_contents($this->_options['repository'] . 'sync.zip');
    }
    
    /**
     * 更新
     */
    public function actionUpdate()
    {
        // 循环取出当前工作副本文件,和服务器的文件列表对比,不一样的话,要求服务器打包不同的文件,弹出下载
        
        // 本地获得下载的文件,解压,覆盖到开发机 或是通知svn上传
        
        return true;
    }
    
    /**
     * 提交
     */
    public function actionCommit()
    {
        if (!is_file($file = $this->_options['workCopy']) . '.qws/sync.php') {
            throw new Exception('The patch "' .  $this->_options['workCopy']. '" is not under control.');
        }
        
        // 循环取出当前工作副本的文件和同步文件做对比,如果都存在,对比sha1,如果一方不存在,检查是添加还是删除,
        
        // 由此提供一个差异文件列表
        
        // 将文件列表打包,上传到服务器
        
        // 服务器解压,覆盖,删除,或增加对应文件
    }
}