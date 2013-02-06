<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

return array(
    'alnum'                     => '该项只能由字母(a-z)和数字(0-9)组成',
    'alpha'                     => '该项只能由字母(a-z)组成',
    'callback'                  => '该项不合法',
    'chinese'                   => '该项只能由中文组成',
    'date'                      => '该项不是合法的日期,格式应该是{{ format }},例如:{{ example }}',
    'dateTime'                  => '该项不是合法的日期时间,格式应该是{{ format }}',
    'digit'                     => '该项只能由数字(0-9)组成',
    'dir'                       => '指定的目录不存在',
    'doubleByte'                => '该项只能由双字节字符组成',
    'email'                     => '该项必须是有效的邮箱地址',
    'equal'                     => '指定的值不相等',
    'exists'                    => '指定的路径不存在',
    'file'                      => '指定的文件不存在',
    'file.notReadable'          => '该文件不可读',
    'file.minSize'              => '该文件太小了({{ size }}字节),允许的最小文件大小为{{ minSize }}字节', 
    'file.maxSize'              => '该文件太大了({{ size }}字节),允许的最大文件大小为{{ maxSize }}字节',
    'file.exts'                 => '该文件扩展名({{ ext }})不合法,允许的扩展名为:{{ exts }}',
    'file.excludeExts'          => '该文件扩展名({{ ext }})不合法,不允许扩展名为:{{ excludeExts }}',
    'file.mimeTypeNotDetected'  => '无法检测该文件的媒体类型',
    'file.mimeTypes'            => '文件的媒体类型不合法',
    'file.excludeMimeTypes'     => '文件的媒体类型不合法',
    'image'                     => '该项必须是有效的图片',
    'image.notFound'            => '该图片不存在,或不可读',
    'image.notDetected'         => '该文件不是有效的图片,或是无法检测到图片的尺寸',
    'image.widthTooBig'         => '该图片宽度太大({{ width }}px), 允许的最大宽度为{{ maxWidth }}px',
    'image.widthTooSmall'       => '该图片的宽度太小({{ width }}px),允许的最小宽度应为{{ minWidth }}px', 
    'image.heightTooBig'        => '该图片高度太大({{ height }}px), 允许的最大高度为{{ maxHeight }}px',
    'image.heightTooSmall'      => '该图片高度太小({{ height }}px), 允许的最小高度为{{ minHeight }}px',
    'in'                        => '该项必须在指定的数据中:{{ array }}',
    'length'                    => '该项的长度必须在{{ min }}和{{ max }}之间',
    'maxLength'                 => '该项的长度必须小于等于{{ limit }}',
    'minLength'                 => '该项的长度必须大于等于{{ limit }}',
    'mobile'                    => '手机号码必须是13位长度的数字,以13,15,18开头',
    'phone'                     => '电话号码格式不正确',
    'postcode'                  => '该项必须是6位长度的数字',
    'QQ'                        => 'QQ号码格式不正确',  
    'range'                     => '该项不在指定范围内',
    'regex'                     => '该项格式不正确',
    'required'                  => '该项不能为空',
    'time'                      => '时间格式不正确',
    'type'                      => '该项的类型必须是{{ type }}'
);