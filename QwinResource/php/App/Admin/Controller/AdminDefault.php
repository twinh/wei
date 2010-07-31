<?php
 /**
 * 后台默认首页
 *
 * 数据库操作后台控制器,包括显示环境参数,登入,登出
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
 * @version   2009-11-21 13:07
 * @since     2009-11-21 13:07
 */

class Admin_Controller_Default extends Qwin_Miku_Controller
{
    function actionDefault()
    {
        if(!qw('-acl')->isLogin())
        {
            qw('-url')->to(url(array('Admin', 'Default', 'Login')));
        } else {
            qw('-url')->to(url(array('Admin', 'Default', 'SystemInfo')));
        }
    }

    function actionSystemInfo()
    {
        //系统资料
        $this->__view = array(
            'info' => array(
                'php_version'        =>    PHP_VERSION,
                'mysql_server_info'  =>    mysql_get_server_info(),
                'server_software'    =>    $_SERVER['SERVER_SOFTWARE'],
                'server_name'        =>    $_SERVER['SERVER_NAME'],
                'server_addr_port'   =>    ($_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR']) . " : " . $_SERVER['SERVER_PORT'],
                'server_doc_root'    =>    dirname($_SERVER['SCRIPT_FILENAME']),
                'server_time'        =>    gmdate("Y-m-d H:i:s", time()+8*60*60),
                'upload_file'        =>    ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled',
            ),
        );
        

        // 初始化控制面板中心内容的视图变量数组,加载控制面板视图
        $this->__cp_content = 'Resource/View/AdminSystemInfo';
    	$this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
    }

    function actionPhpInfo()
    {
        // 获取phpinfo
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_end_clean();
        $info = explode('<body>', $info);
        $info = explode('</body>', $info[1]);

        //preg_match_all("/body(.+?)body/", $info, $matches);
        $this->__view = array(
            'php_info' => &$info[0],
        );
        $this->__cp_content = 'Resource/View/AdminPhpInfo';
    	$this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
    }

    function actionLogin()
    {
        if(qw('-acl')->isLogin())
        {
            qw('-hpjs')->show('您已经登录!', url(array('admin')));
        }
        if(!$_POST)
        {
            // 从模型配置数组中取出表单初始值
            $data = $this->setting->getSettingValue($this->__meta['field'], array('form', '_value'));
			
			// 根据配置和控制器中的对应方法转换数据
			$data = $this->setting->converSingleData($this->__meta['field'], $this->__query['action'], $data);
			
			$tip_data = $this->setting->getTipData($this->__meta['field']);
			
			// 获取 jQuery Validate 的验证规则
			$validator_rule = qw('Qwin_JQuery_Validator')->getRule($this->__meta['field']);
			
			// 排序
			$this->__meta['field'] = $this->setting->orderSettingArr($this->__meta['field']);
			// 分组
			$this->__meta['field'] = $this->setting->groupingSettingArr($this->__meta['field']);
			
			// 初始化视图变量数组
			$this->__view = array(
				'set' => $this->__meta,
				'data' => $data,
				'tip_data' => &$tip_data,
				'tip_name' => &$tip_name,
				'validator_rule' => &$validator_rule,
				'http_referer' => urlencode(qw('-str')->set($_SERVER['HTTP_REFERER']))
			);

			$this->__cp_content = 'Resource/View/Element/AdminForm';
    	    $this->loadView(qw('-ini')->load('Resource/View/AdminControlPanel', false));
        } else {
            $this->__query['action'] = 'db';
            $sql_field = $this->setting->getSettingList($this->__meta['field'], 'isSqlField');
            $data = qw('-gpc')->post($sql_field);

            // 根据配置和控制器中的对应方法转换数据
            $data = $this->setting->converSingleData($this->__meta['field'], 'db', $data);

            // 检验数据
            //$valid_data = $this->getValidData($this->__meta['field']);
            //$this->validData($this->__meta['field'], $valid_data, $data);

            // 检查密码
            $data['password'] = md5($data['password']);
            qw('-qry')->setTable('admin_user');
            $query_arr = array(
                'WHERE' => "`username` = '$data[username]'",
            );
            $sql = qw('-qry')->getOne($query_arr);
            $user_data = qw('-db')->getOne($sql);
            if(!$user_data['id'])
            {
                QMsg::show('不存在的用户名!', 'goback');
            }
            if($user_data['password'] != $data['password'])
            {
                QMsg::show('密码错误!', 'goback');
            }
            qw('-acl')->setLogin($user_data);

            // 跳转页面地址,默认是上个页面的地址即$_SERVER['HTTP_REFERER'],如果不存在,则跳转到当前控制器的默认页面
            $url = urldecode(qw('-gpc')->p('_page'));
            if($url)
            {
                qw('-url')->to($url);
            } else {
                qw('-url')->to(url(array('admin')));
                //qw('-url')->to(url(array('admin', $this->__query['controller'])));
            }
        }
    }

    function actionLogout()
    {
        Acl::setLogout();
        QMsg::show('登出成功!', url(array('admin')));
    }

    function actionToggleMenuState()
    {
        $category_id = intval(qw('-ini')->p('category_id'));
        $category_state = intval(qw('-ini')->p('category_state'));
        $category_state = qw('-arr')->forceInArray($category_state, array(0, 1));
        $_SESSION['menu_category'][$category_id] = $category_state;
    }
}
