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

function d($a = null, $exit = true)
{
    var_dump($a);
    if (true === $exit) {
        exit;
    }
}
function p($a = null, $exit = true)
{
    echo '<p><pre>';
    print_r($a);
    echo '</pre><p>';
    if (true === $exit) {
        exit;
    }
}

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

function qw_f()
{
    
}

function qw_form($option)
{
    static $form;
    null == $form && $form = Qwin::widget('form');
    return $form->renderElement($option);
}

function qw_form_extend($option, $form = null)
{
    static $widget;
    if (!isset($option['_extend'])) {
        return false;
    }

    null == $widget && $widget = Qwin_Widget::getInstance();
    $result = '';
    foreach ($option['_extend'] as $callback) {
        $widgetName = array_shift($callback);
        if ($widget->isExists($widgetName)) {
            $param = array(
                'option' => $callback,
                'form' => $option,
            );
            $result .= $widget->get($widgetName)->render($param);
        } else {
            $result .= Qwin_Class::callByArray($callback);
        }
    }
    return $result;
}

function qw_url(array $data = null)
{
    return Qwin::call('-url')->url($data);
}

function qw_lang($name = null)
{
    static $lang;
    null == $lang && $lang = Qwin::call('Qwin_Application_Language');
    return $lang->t($name);
}

function qw_lang_to_js()
{
    return 'Qwin.Lang = ' . json_encode(Qwin::call('Qwin_Application_Language')->toArray()) . ';';
}

function qw_null_text($data = null)
{
    if (null != $data) {
        return $data;
    }
    return '<em>(' . qw_lang('LBL_NULL') .')</em>';
}