<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

return [
    // default
    'This value' => '该项',
    '%name% is not valid' => '%name%不合法',
    '%name% must be a string' => '%name%必须是字符串',

    // all
    '%name% must be of type array' => '%name%必须是数组',
    '%name%\'s %index% item' => '%name%的第%index%项',

    // allOf
    '%name% must be passed by all of these rules' => '%name%必须满足以下所有规则',

    // alnum
    '%name% must contain letters (a-z) and digits (0-9)' => '%name%只能由字母(a-z)和数字(0-9)组成',
    '%name% must not contain letters (a-z) or digits (0-9)' => '%name%不能只由字母(a-z)和数字(0-9)组成',

    // alpha
    '%name% must contain only letters (a-z)' => '%name%只能由字母(a-z)组成',
    '%name% must not contain  only letters (a-z)' => '%name%不能只由字母(a-z)组成',

    // blank
    '%name% must be blank' => '%name%必须为空',
    '%name% must not be blank' => '%name%不能为空',

    // charLength
    '%name% must be %length% characters' => '%name%必须是%length%个字符',
    '%name% must be between %min% to %max% characters' => '%name%必须包含%min%-%max%个字符',

    // chinese
    '%name% must contain only Chinese characters' => '%name%只能由中文组成',
    '%name% must not contain only Chinese characters' => '%name%不能只由中文组成',

    // color
    '%name% must be valid hex color, e.g. #FF0000' => '%name%必须是有效的十六进制颜色,例如#FF0000',
    '%name% must not be valid hex color' => '%name%不能是有效的十六进制颜色',

    // contains
    '%name% must contains %search%' => '%name%必须包含%search%',
    '%name% must not contains %search%' => '%name%不能包含%search%',

    // creditCard
    '%name% must be valid credit card number' => '%name%必须是有效的信用卡号',
    '%name% must not be valid credit card number' => '%name%不能是有效的信用卡号',

    // date
    '%name% must be a valid date, the format should be "%format%", e.g. %example%' => '%name%不是合法的日期,格式应该是%format%,例如:%example%',
    '%name% must not be a valid date' => '%name%不能是合法的日期',

    // dateTime
    '%name% must be a valid datetime' => '%name%必须是合法的日期时间',
    '%name% must be a valid datetime, the format should be "%format%", e.g. %example%' => '%name%不是合法的日期时间,格式应该是%format%,例如:%example%',
    '%name% must be earlier than %before%' => '%name%必须早于%before%',
    '%name% must be later than %after%' => '%name%必须晚于%after%',
    '%name% must not be a valid datetime' => '%name%不能是合法的日期时间',

    // decimal
    '%name% must be decimal' => '%name%必须是小数',
    '%name% must not be decimal' => '%name%不能是小数',

    // digit
    '%name% must contain only digits (0-9)' => '%name%只能由数字(0-9)组成',
    '%name% must not contain only digits (0-9)' => '%name%不能只由数字(0-9)组成',

    // dir
    '%name% must be an existing directory' => '%name%必须是存在的目录',
    '%name% must be a non-existing directory' => '%name%必须是不存在的目录',

    // divisibleBy
    '%name% must be divisible by %divisor%' => '%name%必须被%divisor%整除',
    '%name% must not be divisible by %divisor%' => '%name%不可以被%divisor%整除',

    // doubleByte
    '%name% must contain only double byte characters' => '%name%只能由双字节字符组成',
    '%name% must not contain only double byte characters' => '%name%不能只由双字节字符组成',

    // email
    '%name% must be valid email address' => '%name%必须是有效的邮箱地址',
    '%name% must not be an email address' => '%name%不能是邮箱地址',

    // emptyValue
    '%name% must be empty' => '%name%必须为空',
    '%name% must not be empty' => '%name%不能为空',

    // endsWith
    '%name% must end with "%findMe%"' => '%name%必须以%findMe%结尾',
    '%name% must not end with "%findMe%"' => '%name%不能以%findMe%结尾',

    // entityExists
    '%name% not exists' => '%name%不存在',
    '%name% already exists' => '%name%已存在',

    // equalTo
    '%name% must be equals %value%' => '%name%必须等于%value%',
    '%name% must not be equals %value%' => '%name%不能等于%value%',

    // identicalTo
    '%name% must be identical to %value%' => '%name%必须完全等于%value%',
    '%name% must not be identical to %value%' => '%name%不能完全等于%value%',

    // greaterThan
    '%name% must be greater than %value%' => '%name%必须大于%value%',
    '%name% must not be greater than %value%' => '%name%不能大于%$value%',

    // greaterThanOrEqual
    '%name% must be greater than or equal to %value%' => '%name%必须大于或等于%value%',
    '%name% must not be greater than or equal to %value%' => '%name%必须不大于或等于%value%',

    // lessThan
    '%name% must be less than %value%' => '%name%必须小于%value%',
    '%name% must not be less than %value%' => '%name%不能小于%value%',

    // lessThanOrEqual
    '%name% must be less than or equal to %value%' => '%name%必须小于或等于%value%',
    '%name% must not be less than or equal to %value%' => '%name%必须不小于或等于%value%',

    // exists
    '%name% must be an existing file or directory' => '%name%必须是存在的文件或目录',
    '%name% must be a non-existing file or directory' => '%name%必须是不存在的文件或目录',

    // file
    '%name% is not found or not readable' => '%name%不存在或不可读',
    '%name% is too large(%sizeString%), allowed maximum size is %maxSizeString%' => '%name%太大了(%sizeString%),允许的最大文件大小为%maxSizeString%',
    '%name% is too small(%sizeString%), expected minimum size is %minSizeString%' => '%name%太小了(%sizeString%),允许的最小文件大小为%minSizeString%',
    '%name% extension(%ext%) is not allowed, allowed extension: %exts%' => '%name%的扩展名(%ext%)不合法,只允许扩展名为:%exts%',
    '%name% extension(%ext%) is not allowed, not allowed extension: %excludeExts%' => '%name%的扩展名(%ext%)不合法,不允许扩展名为:%excludeExts%',
    '%name% mime type could not be detected' => '无法检测%name%的媒体类型',
    '%name% mime type "%mimeType%" is not allowed' => '%name%的媒体类型不合法',
    '%name% must be a non-existing file' => '%name%必须是不存在的文件',

    // idCardCn
    '%name% must be valid Chinese identity card' => '%name%必须是有效的中国身份证',
    '%name% must not be valid Chinese identity card' => '%name%不能是有效的中国身份证',

    // idCardHk
    '%name% must be valid Hong Kong identity card' => '%name%必须是有效的香港身份证',
    '%name% must not be valid Hong Kong identity card' => '%name%不能是有效的香港身份证',

    // idCardMo
    '%name% must be valid Macau identity card' => '%name%必须是有效的澳门身份证',
    '%name% must not be valid Macau identity card' => '%name%不能是有效的澳门身份证',

    // idCardTw
    '%name% must be valid Taiwan identity card' => '%name%必须是有效的台湾身份证',
    '%name% must not be valid Taiwan identity card' => '%name%不能是有效的台湾身份证',

    // image
    '%name% is not a valid image or the size of the image could not be detected' => '%name%不是有效的图片,或是无法检测到图片的尺寸',
    '%name% width is too big (%width%px), allowed maximum width is %maxWidth%px' => '%name%的宽度太大(%width%px), 允许的最大宽度为%maxWidth%px',
    '%name% width is too small (%width%px), expected minimum width is %minWidth%px' => '%name%的宽度太小(%width%px),允许的最小宽度应为%minWidth%px',
    '%name% height is too big (%height%px), allowed maximum height is %maxHeight%px' => '%name%的高度太大(%height%px), 允许的最大高度为%maxHeight%px',
    '%name% height is too small (%height%px), expected minimum height is %minHeight%px' => '%name%的高度太小(%height%px), 允许的最小高度为%minHeight%px',

    // in
    '%name% must be in %array%' => '%name%必须在指定的数据中:%array%',
    '%name% must not be in %array%' => '%name%不能在指定的数据中:%array%',

    // ip
    '%name% must be valid IP' => '%name%必须是有效的IP地址',
    '%name% must not be IP' => '%name%不能是IP地址',

    // length
    '%name% must have a length of %length%' => '%name%的长度必须是%length%',
    '%name% must contain %length% item(s)' => '%name%必须包含%length%项',
    '%name% must have a length between %min% and %max%' => '%name%的长度必须在%min%和%max%之间',
    '%name% must contain %min% to %max% item(s)' => '%name%必须包含%min%到%max%项',
    '%name%\'s length could not be detected' => '无法检测到%name%的长度',

    // lowercase
    '%name% must be lowercase' => '%name%不能包含大写字母',
    '%name% must not be lowercase' => '%name%不能包含小写字母',

    // luhn
    '%name% is not a valid number' => '%name%不是有效的数字',

    // maxLength
    '%name% must have a length lower than %max%' => '%name%的长度必须小于等于%max%',
    '%name% must contain no more than %max% items' => '%name%最多只能包含%max%项',

    // minLength
    '%name% must have a length greater than %min%' => '%name%的长度必须大于等于%min%',
    '%name% must contain at least %min% item(s)' => '%name%至少需要包括%min%项',

    // mobileCn
    '%name% must be valid mobile number' => '%name%必须是11位数字,以13到19开头',

    // null
    '%name% must be null' => '%name%必须是null值',
    '%name% must not be null' => '%name%不能为null值',

    // number
    '%name% must be valid number' => '%name%必须是有效的数字',
    '%name% must not be number' => '%name%不能是数字',

    // naturalNumber
    '%name% must be positive integer or zero' => '%name%必须是大于等于0的整数',

    // oneOf
    '%name% must be passed by at least one rule' => '%name%至少需要满足以下任何一条规则',

    // password
    'Password' => '密码',
    '%name% must have a length greater than %minLength%' => '%name%的长度必须大于等于%minLength%',
    '%name% must have a length lower than %maxLength%' => '%name%的长度必须小于等于%maxLength%',
    '%name% must contains %missingType%' => '%name%必须包含%missingType%',
    '%name% must contains %missingCount% of these characters : %missingType%' => '%name%必须包含%missingType%中的%missingCount%种',
    'digits (0-9)' => '数字(0-9)',
    'letters (a-z)' => '字母(a-z)',
    'lowercase letters (a-z)' => '小写字母(a-z)',
    'uppercase letters (A-Z)' => '大写字母(A-Z)',
    'non-alphanumeric (For example: !, @, or #) characters' => '非字母数组字符(如!,@,#等)',

    // phone, phoneCn
    '%name% must be valid phone number' => '%name%必须是有效的电话号码',
    '%name% must not be phone number' => '%name%不能是电话号码',

    // plateNumberCN
    '%name% must be valid Chinese plate number' => '%name%必须是正确的车牌格式',
    '%name% must not be valid Chinese plate number' => '%name%不能是正确的车牌格式',

    // positiveInteger
    '%name% must be positive integer' => '%name%必须是大于0的整数',

    // postcode
    '%name% must be six length of digit' => '%name%必须是6位长度的数字',
    '%name% must not be postcode' => '%name%不能是邮政编码',

    // QQ
    '%name% must be valid QQ number' => '%name%必须是有效的QQ号码',
    '%name% must not be valid QQ number' => '%name%不能是QQ号码',

    // range
    '%name% must between %min% and %max%' => '%name%必须在%min%到%max%之间',
    '%name% must not between %min% and %max%' => '%name%不能在%min%到%max%之间',

    // regex
    '%name% must match against pattern "%pattern%"' => '%name%必须匹配模式"%pattern%"',
    '%name% must not match against pattern "%pattern%"' => '%name%必须不匹配模式"%pattern%"',

    // require
    '%name% is required' => '%name%不能为空',

    // sameOf
    '%name% must be passed by at least %left% of %count% rules' => '%name%至少需要满足以下%count%条规则中的%left%条',

    // startsWith
    '%name% must start with "%findMe%"' => '%name%必须以%findMe%开头',
    '%name% must not start with "%findMe%"' => '%name%不能以%findMe%开头',

    // time
    '%name% must be a valid time, the format should be "%format%", e.g. %example%' => '%name%不是合法的时间,格式应该是%format%,例如:%example%',
    '%name% must not be a valid time' => '%name%不能是合法的时间',

    // tld
    '%name% must be a valid top-level domain' => '%name%必须是有效的顶级域名',
    '%name% must not a valid top-level domain' => '%name%不能是有效的顶级域名',

    // type
    '%name% must be %typeName%' => '%name%必须是%typeName%',
    '%name% must not be %typeName%' => '%name%不能是%typeName%',
    // is_xxx
    'array' => '数组',
    'bool' => '布尔',
    'float' => '浮点数',
    'int' => '整型',
    'integer' => '整型',
    'null' => 'NULL',
    'numeric' => '数字',
    'object' => '对象',
    'resource' => '资源',
    'scalar' => '标量',
    'string' => '字符串',
    // ctype_xxx
    'alnum' => '字母(a-z)或数字(0-9)',
    'alpha' => '字母',
    'cntrl' => '控制字符',
    'digit' => '数字',
    'graph' => '可显示字符',
    'lower' => '小写字母(a-z)',
    'print' => '可打印字符', // 包括空格
    'punct' => '标点符号',
    'space' => '空白字符',
    'upper' => '大写字母(A-Z)',
    'xdigit' => '16进制数字',

    // uppercase
    '%name% must be uppercase' => '%name%不能包含小写字母',
    '%name% must not be uppercase' => '%name%不能包含大写字母',

    // url
    '%name% must be valid URL' => '%name%必须是有效的URL地址',
    '%name% must not be URL' => '%name%不能是URL地址',

    // uuid
    '%name% must be valid UUID' => '%name%必须是有效的UUID',
    '%name% must not be valid UUID' => '%name%不能是有效的UUID',

    // upload
    'No file uploaded or the total file size is too large, allowed maximum size is %postMaxSize%' => '没有文件被上传,或您上传的总文件大小超过%postMaxSize%',
    'No file uploaded, please select a file to upload' => '没有文件被上传,请选择一个文件上传',
    '%name% is larger than the MAX_FILE_SIZE value in the HTML form' => '%name%的大小超过HTML表单设置',
    '%name% was partial uploaded, please try again' => '%name%未完全上传,请再试一遍',
    'The temporary upload directory is missing' => '未找到上传文件的临时目录',
    'Cannot write %name% to disk' => '无法保存%name%',
    'File upload stopped by extension' => '文件上传被扩展中止',
    'No file uploaded' => '没有文件被上传',
    'Cannot move uploaded file' => '无法移动上传的文件',
];
