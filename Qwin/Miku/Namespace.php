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
    public function beforeLoad($set = null, $config = null)
    {
        $ini = Qwin::run('-ini');
        $config = $ini->getConfig();

        $this->_setupSession($config);

        // 打开缓冲区
        ob_start();

        // 增加类的对应关系
        Qwin::addMap(array(
            // 权限控制
            '-acl'  => 'Qwin_Acl',

            // 前端数据处理
            '-wfe'  => 'Qwin_Wfe',
            '-js'   => 'Qwin_Wfe_Js',
            '-css'  => 'Qwin_Wfe_Css',
            '-html' => 'Qwin_Wfe_Html',
            '-rsc'  => 'Qwin_Wfe_Resource',

            // 表单生成
            '-form' => 'Qwin_Form',
            '-btn'  => 'Qwin_Form_Button',

            '-str'  => 'Qwin_Converter_String',
        ));
      
        // 数据库连接
        if($config['db']['setup'])
        {
            if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
            {
                $name = 'db';                
            } else {
                $name = 'web_db';
            }
        }
        require_once(QWIN_LIB_PATH . '/Doctrine.php');
        spl_autoload_register(array('Doctrine', 'autoload'));

        $adapter = $config[$name]['type'] . '://'
                 . $config[$name]['username'] . ':'
                 . $config[$name]['password'] . '@'
                 . $config[$name]['server'] . '/'
                 . $config[$name]['database'];
        $conn = Doctrine_Manager::connection($adapter, $config['project']['name']);
        $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
        $conn->setCharset($config['db']['charset']);
        //$this->profiler = new Doctrine_Connection_Profiler();
        //$conn->setListener($this->profiler);
        
        // 检查权限
        /*if(!qw('-acl')->isPermit($set))
        {
            $set['controller'] = 'HttpError';
            $set['action'] = 401;
            return false;
        }*/

        // 加载表单生成类
        // 表单基本类型,富文本编辑器, jQuery 相关的插件
        Qwin::run('-form')
            ->add('Qwin_Form_Element_Base')
            ->addExt('Qwin_Form_ElementExt_Editor')
            ->addExt('Qwin_Form_ElementExt_JQuery');

        // 加载按钮生成类
        // 部分常用按钮
        Qwin::run('-btn')->add('Qwin_Form_Button_JQuery');
    }

    public function onLoad($set = null, $config = null)
    {
        $ini = Qwin::run('-ini');
        $controller = Qwin::run('-c');
        // 加载配置文件
        
        Qwin::load('Qwin_Miku_Metadata');
        $metadataName = $ini->getClassName('Metadata', $set);
        $metadata = Qwin::run($metadataName);
        if(NULL == $metadata)
        {
            $metadataName = 'Qwin_Miku_Metadata';
            $metadata = Qwin::run($metadataName);
        }
        Qwin::addMap('-s', $metadataName);
        // TODO 是否要包含在控制器中?
        $controller->__meta = $metadata->defaultMetadata();

        
        $metadata->setMetadata();
        print_r($metadata);exit;

        $controller->meta = $metadata;
        // 语言转换
        $this->_loadLang($set, $config);
        $controller->__meta = $metadata->converLang($controller->__meta, $controller->lang);

        // 获取模型类名称
        $modelName = $ini->getClassName('Model', $set);
        $model = Qwin::run($modelName);
        if(NULL == $model)
        {
            $modelName = 'Qwin_Miku_Model';
            $model = Qwin::run($modelName);
        }
        Qwin::addMap('-model', $modelName);
        $controller->model = $model;
        if(isset($controller->__meta['db']['table']))
        {
            $controller->meta->metadataToModel($controller->__meta, $model);
        }
    }

    public function afterLoad($set = null, $config = null)
    {
        /*$time = 0;
        foreach ($this->profiler as $event) {
            $time += $event->getElapsedSecs();
            echo $event->getName() . " " . sprintf("%f", $event->getElapsedSecs()) . "\n";
            echo $event->getQuery() . "\n";
            $params = $event->getParams();
            if( ! empty($params)) {
                print_r($params);
            }
        }
        echo "Total time: " . $time  . "\n";*/
        $controller = Qwin::run('-c');

        $js_lang = 'Qwin.Lang = ';
        $js_lang .= Qwin::run('-arr')->toJsObject($controller->lang);
        $js_lang .= ';';
        Qwin::run('-js')->addJs('lang', $js_lang);
        $output = ob_get_contents();
        '' != $output && ob_end_clean();
        $html = Qwin::run('-html')->packAll();
        $js =  Qwin::run('-js')->packAll();
        $css = Qwin::run('-css')->packAll();
        $output = str_replace(
            array('<!--{CSS}-->', '<!--{JS}-->'),
            array($css, $html . $js),
            $output
        );
        echo $output;
        unset($output);
    }

    /**
     * 加载语言
     * @param array $set
     * @param array $cofig
     */
    private function _loadLang($set = null, $config = null)
    {
        // TODO default language
        // 获取语言名称
        $ini = Qwin::run('-ini');
        $url = Qwin::run('-url');
        $controller = Qwin::run('-c');

        $lang = '';
        // 按优先级排列语言的数组
        $lang_arr = array(
            $url->g('lang'),
            Qwin::run('-ses')->get('lang'),
            $config['i18n']['language'],
        );
        foreach($lang_arr as $val)
        {
            if(null != $val)
            {
                $lang = $val;
                break;
            }
        }
        Qwin::run('-ses')->set('lang', $lang);
        //$controller->lang_name = $lang;
        $controller->meta->lang = $lang;

        // 加载项目语言
        $lang_file = ROOT_PATH . '/Common/Lang/' . $lang . '.php';
        if(file_exists($lang_file))
        {
            $controller->lang = require_once $lang_file;
        } else {
            $lang_file = ROOT_PATH . '/Common/Lang/' . $config['i18n']['language'] . '.php';
            $controller->lang = require_once $lang_file;
        }

        // 加载当前模块语言
        $module_lang_file = ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $lang . '.php';
        if(file_exists($module_lang_file))
        {
            $controller->lang += require_once $module_lang_file;
        } else {
            $module_lang_file = ROOT_PATH . '/App/' . $set['namespace'] . '/' . $set['module'] . '/Lang/' . $config['i18n']['language'] . '.php';
            if(file_exists($module_lang_file))
            {
                $controller->lang += require_once $module_lang_file;
            }
        }
    }

    /**
     * 启动会话
     * @param array $config 配置数组
     */
    private function _setupSession($config)
    {
        Qwin::addMap('-ses', 'Qwin_Session');
        $namespace = md5($_SERVER['SERVER_NAME'] . $config['project']['name']);
        Qwin::run('-ses', $namespace);
    }
}
