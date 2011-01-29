<?php
/**
 * Minify
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
 * @since       2011-01-24 19:03:11
 */

class Common_Helper_Minify
{
    protected $_js = array();

    protected $_css = array();

    protected $_cachePath;


    public function  __construct($cachePath = null)
    {
        if (null != $cachePath) {
            $this->_cachePath = $cachePath;
        } else {
            $this->_cachePath = QWIN_ROOT_PATH . '/cache/mini';
        }
    }

    public function addJs($file)
    {
        if (file_exists($file)) {
            $this->_js[] = $file;
        }
        return $this;
    }

    public function addCss($file)
    {
        if (file_exists($file)) {
            $this->_css[] = $file;
        }
        return $this;
    }

    public function packJs()
    {
        if (empty($this->_js)) {
            return $this;
        }

        $name = md5(implode('|', $this->_js));
        $fileName = $this->_cachePath . '/' . $name . '.php';
        if (file_exists($fileName)) {
            return $name;
        }
        file_put_contents($fileName, '<?php return ' . var_export($this->_js, true) . ';' );

        return $name;
    }

    public function packCss()
    {
        if (empty($this->_css)) {
            return $this;
        }

        $name = md5(implode('|', $this->_css));
        $fileName = $this->_cachePath . '/' . $name . '.php';
        if (file_exists($fileName)) {
            return $name;
        }
        file_put_contents($fileName, '<?php return ' . var_export($this->_css, true) . ';' );

        return $name;
    }

    public function getCacheFile($name)
    {
        return $this->_cachePath . '/' . $name . '.php';
    }
}