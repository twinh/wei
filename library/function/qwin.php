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

/**
 * 快速加载类
 *
 * @param string $name
 * @return object|null
 */
function qwin($name)
{
    return Qwin::call($name);
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
    /*static $url, $config;
    null == $url && $url = Qwin::call('-url');
    if (null == $array1) {
        null == $config && $config = Qwin::call('-config');
        $array1 = $config['asc'];
    }*/
    return $url->createUrl($array1, $array2);

}

function qw_lang($name = null)
{
    static $lang;
    null == $lang && $lang = Qwin::call('-lang');
    return $lang->t($name);
}

function qw_lang_to_js()
{
    return 'Qwin.Lang = ' . json_encode(Qwin::call('-lang')->toArray()) . ';';
}

function qw_null_text($data = null)
{
    if (null != $data) {
        return $data;
    }
    return '<em>(' . qw_lang('LBL_NULL') .')</em>';
}

function qw_referer_page($page = null)
{
    !isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] = $page;
    return urlencode($_SERVER['HTTP_REFERER']);
}

function qw_title_decorator($title, $fontStyle = null, $color = null)
{
    return Qwin_Helper_Html::titleDecorator($title, $fontStyle, $color);
}

function qw_jquery_link($url, $title, $icon, $aClass = null, $target = '_self')
{
    return Qwin_Util_JQuery::link($url, $title, $icon, $aClass, $target);
}

function qw_jquery_button($type, $title, $icon)
{
    return '<button type="' . $type . '" class="ui-button-none ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ' . $icon . '"></span><span class="ui-button-text">' . $title . '</span></button>' . "\r\n";
}

function qw_jquery_operation_button($url, $title, $icon)
{
    return Qwin_Util_JQuery::icon($url, $title, $icon);
}
