<?php
/**
 * Language
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
 * @package     Common
 * @subpackage  Service
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-10-09 22:15:32
 */

class Common_Service_Language extends Common_Service
{
    protected $_language = null;

    /**
     * 应用结构配置
     */
    protected $_config = array(
        'namespace' => null,
        'module' => null,
        'controller' => null,
        'action' => null,
    );

    /**
     * 获取语言的名称
     *
     * @param array $config
     * @return array 服务结果
     */
    public function getLanguage($config = null)
    {
        if(null == $this->_language)
        {
            $config = $this->_multiArrayMerge($this->_config, $config);
            $this->_getLanguage($config);
        }
        return array(
            'result' => true,
            'data' => $this->_language,
        );
    }

    /**
     * 获取语言名称,返回格式为类名化,如Zhcn,Enus
     *
     * @param array $config 应用结构配置
     * @return string 语言名称
     */
    public function _getLanguage($config)
    {
        $request    = Qwin::run('-request');
        $session    = Qwin::run('-session');
        $webConfig  = Qwin::run('-config');
        $language   = null;

        // 按优先级排列语言的数组
        $languageList = array(
            $request->g('language'),
            $session->get('language'),
            $webConfig['interface']['language'],
        );

        foreach($languageList as $value)
        {
            if(null != $value)
            {
                $language = $value;
                break;
            }
        }

        // 转换为类名格式
        $language = $this->_toClassName($language);

        // 检查语言类是否存在
        $languageClass = $config['namespace'] . '_' . $config['module'] . '_Language_' . $language;
        // TODO　当某一模块的语言类不存在时,将自动变为默认的语言
        if(!class_exists($languageClass))
        {
            $language = $this->_toClassName($webConfig['interface']['language']);
        }

        $session->set('language', $language);
        $this->_language = $language;
        return $language;
    }

    /**
     * 将字符串转换成标准的类名
     *
     * @param string $string 转换前的字符串
     * @return 标准类名格式的字符串
     * @todo 使用正则替换
     */
    protected function _toClassName($string)
    {
        $string = str_replace(array('-', '_'), '', $string);
        return ucfirst(strtolower($string));
    }
}
