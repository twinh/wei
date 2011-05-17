<?php
/**
 * qwin
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
 * @subpackage  Function
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-08-18 10:28:04
 */

/**
 * 快速加载类
 *
 * @return obejct|null
 */
function qw($class, $param = null)
{
    //return Qwin::call($class, $param);
}

function qw_a()
{
    
}

/**
 * 输出$v的值，做调试用
 *
 * @param mixed $v
 * @param mixed $exit 是否退出，中断操作
 */
function qw_d($v = null, $exit = true)
{
    var_dump($v);
    if (true === $exit) {
        exit;
    }
}

function qw_f()
{
    
}

function qw_form($option)
{
    static $form;
    null == $form && $form = Qwin::call('-widget')->get('Form');
    return $form->renderElement($option);
}

function qw_h()
{
    // TODO hook
}

/**
 * 输出$v的值，做调试用
 *
 * @param mixed $v
 * @param mixed $exit 是否退出，中断操作
 */
function qw_p($v = null, $exit = true)
{
    echo '<p><pre>';
    print_r($v);
    echo '</pre><p>';
    if (true === $exit) {
        exit;
    }
}

function qw_url(array $data = null)
{
    return Qwin::call('-url')->build($data);
}
function qw_u($value1, $value2 = 'index', array $params = array())
{
    return Qwin::call('-url')->url($value1, $value2, $params);
}

function qw_w($name)
{
    static $widget;
    null == $widget && $widget = Qwin::call('-widget');
    return $widget->get($name);
}

function qw_l()
{
    // TODO language or qw_t
}

function qw_t($name = null)
{
    static $lang;
    null == $lang && $lang = Qwin::call('-widget')->get('Lang');
    return $lang->t($name);
}

function qw_null_text($data = null)
{
    if (null != $data) {
        return $data;
    }
    return '<em>(' . qw_lang('LBL_NULL') .')</em>';
}