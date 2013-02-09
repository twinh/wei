<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

return array(
    // default
    'This value' => '该项',
    '%name% is not valid' => '%name%不合法',
    
    // alnum
    'This value must contain letters (a-z) and digits (0-9)' => '该项只能由字母(a-z)和数字(0-9)组成',
    
    // alpha
    'This value must contain only letters (a-z)' => '该项只能由字母(a-z)组成',
    
    // chinese
    'This value must contain only Chinese characters' => '该项只能由中文组成',
    
    // date
    '%name% is not a valid date, the format should be "%format%", eg: %example%' => '%name%不是合法的日期,格式应该是%format%,例如:%example%',
    
    // dateTime
    'This value is not a valid datetime, the format should be "%format%", eg: %example%'  => '该项不是合法的日期时间,格式应该是%format%,例如:%example%',
    
    // digit
    'This value must contain only digits (0-9)' => '该项只能由数字(0-9)组成', 
    
    // dir
    'This value must be an existing directory' => '指定的目录不存在',
    
    // 'doubleByte'
    'This value must contain only double byte characters' => '该项只能由双字节字符组成',
    
    // email
    'This value must be valid email address' => '该项必须是有效的邮箱地址',
    
    // equal
    'This value must be equals %value%' => '指定的值不相等',
    
    // exists
    'This value must be an existing file or directory' => '指定的路径不存在',
    
    // file
    'This value must be a valid file' => '指定的文件不存在',
    'This value must be an existing file' => '指定的文件不存在',
    'This file is not readable' => '该文件不可读',
    'This file is too large(%size%), allowed maximum size is %maxSize%' => '该文件太大了(%size%),允许的最大文件大小为%maxSize%',
    'This file is too small(%size%), expected minimum size is %minSize%' => '该文件太小了(%size%),允许的最小文件大小为%minSize%',
    'This file extension(%ext%) is not allowed, allowed extension: %exts%' => '该文件扩展名(%ext%)不合法,允许的扩展名为:%exts%',
    'This file extension(%ext%) is not allowed, not allowed extension: %excludeExts%' => '该文件扩展名(%ext%)不合法,不允许扩展名为:%excludeExts%',
    'This file mime type could not be detected' => '无法检测该文件的媒体类型',
    'This file mime type "%mimeType%" is not allowed' => '文件的媒体类型不合法',
    
    // image
    'This value must be a valid image' => '该项必须是有效的图片',
    'This image is not found or not readable' => '该图片不存在,或不可读',
    'This file is not a valid image or the size of the image could not be detected' => '该文件不是有效的图片,或是无法检测到图片的尺寸',
    'This image width is too big (%width%px), allowed maximum width is %maxWidth%px' => '该图片宽度太大(%width%px), 允许的最大宽度为%maxWidth%px',
    'This image width is too small (%width%px), expected minimum width is %minWidth%px' => '该图片的宽度太小(%width%px),允许的最小宽度应为%minWidth%px', 
    'This image height is too big (%height%px), allowed maximum height is %maxHeight%px' => '该图片高度太大(%height%px), 允许的最大高度为%maxHeight%px',
    'This image height is too small (%height%px), expected minimum height is %minHeight%px' => '该图片高度太小(%height%px), 允许的最小高度为%minHeight%px',
    
    // in
    'This value must be in %array%' => '%name%必须在指定的数据中:%array%',
    
    // length
    'This value must have a length between %min% and %max%' => '该项的长度必须在%min%和%max%之间',
    
    // max
    'This value must be less or equal than %limit%' => '该项必需小于等于%limit%',
    
    // maxLength
    'This value must have a length lower than %limit%' => '该项的长度必须小于等于%limit%',
    
    // min
    'This value must be greater or equal than %limit%' => '该项必须大于等于%limit%',
    
    // minLength
    'This value must have a length greater than %limit%' => '该项的长度必须大于等于%limit%',
    
    // mobile
    'This value must be valid mobile number' => '手机号码必须是13位长度的数字,以13,15,18开头',
    
    // null
    'This value must be null' => '该项必须是null值',
    
    // number
    'This value must be valid number' => '该项必须是有效的数字',
    
    // phone
    'This value must be valid phone number' => '电话号码格式不正确',
    
    // postcode
    'This value must be six length of digit' => '该项必须是6位长度的数字',
    
    // QQ
    'This value must be valid QQ number' => 'QQ号码格式不正确',
    
    // range
    'This value must between %min% and %max%' => '该项必须在%min%到%max%之间',
    
    // regex
    'This value must match against pattern "%pattern%"' => '该项必须匹配模式"%pattern%"',
    
    // require
    'This value is required' => '该项不能为空',
    
    // time
    'This value is not a valid time, the format should be "%format%", eg: %example%' => '该项不是合法的时间,格式应该是%format%,例如:%example%',
    
    // type
    'This value must be of type %type%' => '该项的类型必须是%type%'
);