<?php

namespace Widget\Validator\Message;

/**
 * Description of ZhCn
 *
 * @author Twin
 */
class ZhCn extends AbstractMessage
{
    protected $messages = array(
        'alnum'             => '该项只能由字母(a-z)和数字(1-9)组成',
        'alpha'             => '该项只能由字母组成',
        'callback'          => '该项不合法',
        'digit'             => '该项只能由数字组成',
        'dir'               => '指定的目录不存在',
        'doubleByte'        => '该项只能由双字节字符组成',
        'postcode'          => '该项必须是6位长度的数字',
        'email'             => '该项必须是有效的邮箱地址',
        'equal'             => '指定的值不相等',
        'exists'            => '指定的路径不存在',
        'file'              => '指定的文件不存在',
        'length'            => '该项的长度不正确',
        'mobile'            => '手机号码必须是13位长度的数字,以13,15,18开头',
        'phone'             => '电话号码格式不正确',
        'QQ'                => 'QQ号码格式不正确',
        'regex'             => '该项格式不正确',
        'time'              => '时间格式不正确',
    );
}
