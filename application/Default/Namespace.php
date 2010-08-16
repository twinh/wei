<?php
 /**
 * 后台
 *
 * 后台命名空间
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2009-11-21 14:37 utf-8 中文
 * @since     2009-11-21 14:37 utf-8 中文
 */

class Default_Namespace extends Qwin_Trex_Namespace
{
    public function __construct()
    {
        $ini = Qwin::run('Qwin_Trex_Setup');
        $this->_config = $ini->getConfig();
        $config = &$this->_config;

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
        

        /**
         * 更新类库缓存文件
         */
        if(isset($_GET['_update']))
        {
            // 加入类的路径
            Qwin::addMultiPath(array(
                QWIN_LIB_PATH => 10,
                QWIN_RESOURCE_PATH . DS . 'application' => 10,
                QWIN_ROOT_PATH . DS . 'application' => 10,
            ));
            Qwin::update();
        }

        /**
         * 设置类的对应关系
         */
        Qwin::addMap(array(
            // 权限控制
            '-acl'  => 'Qwin_Acl',
            '-arr'  => 'Qwin_Helper_Array',
            '-url'  => 'Qwin_Url',
            '-ini'  => 'Qwin_Trex_Setup',

            // 前端数据处理
            '-wfe'  => 'Qwin_Wfe',
            '-js'   => 'Qwin_Wfe_Js',
            '-css'  => 'Qwin_Wfe_Css',
            '-html' => 'Qwin_Wfe_Html',
            '-rsc'  => 'Qwin_Wfe_Resource',

            // 表单生成
            '-form' => 'Qwin_Form',
            '-btn'  => 'Qwin_Form_Button',

            '-str'  => 'Qwin_converter_String',
        ));

        /**
         * 加载表单,按钮生成类
         */
        // 表单基本类型,富文本编辑器, jQuery 相关的插件
        Qwin::run('-form')
            ->add('Qwin_Form_Element_Base')
            ->addExt('Qwin_Form_ElementExt_Editor')
            ->addExt('Qwin_Form_ElementExt_JQuery');
        // 部分常用按钮
        Qwin::run('-btn')->add('Qwin_Form_Button_JQuery');

        /**
         * 数据库链接,使用的是Doctrine Orm
         * @todo 助手类
         */
        if($config['database']['setup'])
        {
            if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
            {
                $config['database']['mainAdapter'] = 'localhost';
            } else {
                $config['database']['mainAdapter'] = 'web';
            }
            // 更新配置数据
            Qwin::run('-ini')->setConfig($config);

            //require_once(QWIN_LIB_PATH . '/Doctrine.php');
            //spl_autoload_register(array('Doctrine', 'autoload'));
            $databaseSet = $config['database']['adapter'][$config['database']['mainAdapter']];
            $adapter = $databaseSet['type'] . '://'
                     . $databaseSet['username'] . ':'
                     . $databaseSet['password'] . '@'
                     . $databaseSet['server'] . '/'
                     . $databaseSet['database'];
            $conn = Doctrine_Manager::connection($adapter, $config['project']['name']);
            $conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
            $conn->setCharset($databaseSet['charset']);


            // 加载部分父类
            Qwin::load('Default_Controller');
        } 
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

    /*
    public function afterLoad($set = null, $config = null)
    {
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
*/
    /**
     * 加载语言
     * @param array $set
     * @param array $cofig
     */
/*    */
}
