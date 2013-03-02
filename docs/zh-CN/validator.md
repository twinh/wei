验证器
=====
为了保证数据的安全合法,Widget提供了一个简洁高效的验证器,用于检查数据的正确性.目前包括以下这些验证器:

数据类型及组成
* [Alnum](#alnum) - 检查数据是否只由字母(a-z)和数字(0-9)组成
* [Alpha](#alpha) - 检查数据是否只由字母(a-z)组成
* [Blank](#blank) - 检查数据是否为空(不允许空格)
* [Decimal](#decimal) - 检查数据是否为小数
* [Digit](#digit) - 检查数据是否只由数字组成
* [DoubleByte](#doublebyte) - 检查数据是否只由双字节字符组成
* [Empty](#empty) - 检查数据是否为空(允许空格)
* [EndsWith](#endswith) - 检查数据是否以指定字符串结尾
* [Equals](#equals) - 检查数据是否与指定数据相等
* [In](#in) - 检查数据是否在指定的数组中
* [Lowercase](#lowercase) - 检查数据是否为小写
* [Null](#null) - 检查数据是否为null
* [Number](#number) - 检查数据是否为有效数字
* [Regex](#regex) - 检查数据是否匹配指定的正则表达式
* [Require](#require) - 检查数据是否为空(用于组合验证,如果允许为空且数据为空,则不对数据进行剩余规则的校验)
* [StartsWith](#startswith) - 检查数据是否以指定字符串开头
* [Type](#type) - 检查数据是否为指定的类型
* [Uppercase](#uppercase) - 检查数据是否为大写

长度大小
* [Length](#length) - 检查数据是否为指定的长度
* [Max](#max) - 检查数据是否小于等于指定的值
* [MaxLength](#maxlength) - 检查数据是否小于等于指定长度
* [Min](#min) - 检查数据是否大于等于指定的值
* [MinLength](#minlength) - 检查数据是否大于等于指定长度
* [Range](#range) - 检查数据是否在指定的两个值之间

日期和时间
* [Date](#date) - 检查数据是否为合法的日期
* [DateTime](#datetime) - 检查数据是否为合法的日期时间
* [Time](#time) - 检查数据是否为合法的时间

文件目录
* [Dir](#dir)－ 检查数据是否为存在的目录
* [Exists](#exists) - 检查数据是否为存在的文件或目录
* [File](#file) - 检查数据合法的文件
* [Image](#image) - 检查数据是否为有效的图片

网络
* [Email](#email) - 检查数据是否为有效的邮箱地址
* [Ip](#ip) - 检查数据是否为有效的IP地址
* [Tld](#tld) - 检查数据是否为顶级域名
* [Url](#url) - 检查数据是否为有效的URL地址
* [Uuid](#uuid) - 检查数据是否为有效的UUID

区域:所有
* [CreditCard](#creditcard) - 检查数据是否为合法的信用卡号码

区域:中国
* [Chinese](#chinese) - 检查数据是否只由中文组成
* [IdCardCn](#idcardcn) - 检查数据是否为有效的中国身份证
* [IdCardHk](#idcardhk) - 检查数据是否为有效的香港身份证
* [IdCardMo](#idcardmo) - 检查数据是否为有效的澳门身份证
* [IdCardTw](#idcardtw) - 检查数据是否为有效的台湾身份证
* [Phone](#phone) - 检查数据是否为有效的电话号码
* [Postcode](#postcode) - 检查数据是否为有效的邮政编码
* [QQ](#qq) - 检查数据是否为有效的QQ号码
* [Mobile](#mobile) - 检查数据是否为有效的手机号码

分组
* [AllOf](#allof) - 检查数据是否通过所有的规则校验
* [OneOf](#oneof) - 检查数据是否满足指定规则中的任何一条

第三方集成
* [EntityExists](#entityexists) - 检查Doctrine ORM实体是否存在

其他
* [All](#all) - 检查集合里的每一项是否符合指定的规则
* [Callback](#callback) - 检查数据是否通过指定回调方法验证
* [DivisibleBy](#divisibleby) - 检查数据是否能被指定的除数整除

### All
检查集合里的每一项是否符合指定的规则

基本用法
```php
$input = array(1, 123456, 'abc');
$rules = array(
    'digit' => true,
    'maxLength' => 5
);
if (!$widget->isAll($input, $rules)) {
    print_r($widget->isAll->getMessages());
}
```

详细参数
```php
isAll($input, array $rules = array())
```
* $rules - 验证规则数组,键名是验证器名称,值是验证器参数

### AllOf
检查数据是否通过所有的规则校验

基本用法
```php
$input = 'abc';
$rules = array(
    'digit' => true,
    'maxLength' => 5
);
if (!$widget->isAllOf($input, $rules)) {
    print_r($widget->isAllOf->getMessages());
}
```

详细参数
```php
isAllOf($input, array $rules = array())
```
* $rules = 验证规则数组,键名是验证器名称,值是验证器参数

### Alnum 
检查数据是否只由字母(a-z)和数字(0-9)组成

基本用法
```php
$input = '[a]';
if (!$widget->isAlnum($input)) {
    print_r($widget->isAlnum->getMessages());
}
```

详细参数

isAlnum($input, $pattern = null)
* pattern - 指定校验的正则表达式

### Alpha
检查数据是否只由字母(a-z)组成

基本用法
```php
$input = '123';
if (!$widget->isAlpha($input)) {
    print_r($widget->isAlnum->getMessages());
}
```

详细参数
```php
isAlpha($input, $pattern = null)
```
* pattern - 指定校验的正则表达式

### Blank
检查数据是否为空(不允许空格)

基本用法
```php
$input = '123';
if (!$widget->isBlank($input)) {
    print_r($widget->isBlank->getMessages());
}
```

详细参数
```php
isBlank($input)
```

### Callback
检查数据是否通过指定回调方法验证

基本用法  
```php
$input = 'abc';
if (!$widget->isCallback($input, function($input){ 
    return '123' === $input; 
})) {
    print_r($widget->isCallback->getMessages());
}
```

详细参数
```php
isCallback($input, \Closure $fn = null, $message = null)
```
* $fn - 指定验证的回调结构
* $message - 验证不通过时返回的信息

### Chinese
检查数据是否只由中文组成

基本用法
```php
$input = '123';
if (!$widget->isChinese($input)) {
    print_r($widget->isChinese->getMessages());
}
```

详细参数
```php
isChinese($input)
```

### CreditCard
检查数据是否为合法的信用卡号码

允许指定的信用卡类型有: American Express, Diners Club, Discover, JCB, MasterCard, China UnionPay 和 Visa

基本用法
```php
$input = '4111111111111111'; // Visa
if (!$widget->isCreditCard($input)) {
    print_r($widget->isCreditCard->getMessages());
}

// 指定类型只能是Visa或银联卡
$widget->isCreditCard($input, 'UnionPay,Visa');

// 第二个参数也可以是数组,每个元素表示一种类型
$widget->isCreditCard($input, array('UnionPay', 'Visa'));
```

详细参数
```php
isisCreditCard($input, $type = null)
```
* type - 指定信用卡类型,多个使用`,`隔开,或是使用数组,留空表示允许任意信用卡号.

下表为目前允许的信用卡类型

| **发卡机构**     | **中文名称** | **值**       |
|------------------|--------------|--------------|
| American Express | 美国运通     | `Amex`       |
| Diners Club      | 大来卡       | `DinersClub` |
| Discover Card    | 发现卡       | `Discover`   |
| JCB              | -            |`JCB`         |
| MasterCard       | 万事达卡     | `MasterCard` |
| China UnionPay   | 中国银联     | `UnionPay`   | 
| Visa             | -            | `Visa`       |

### Date
检查数据是否为合法的日期

基本用法
```php
$input = 'abc';
if (!$widget->isDate($input) {
    print_r($widget->isDate->getMessages());
}
```

详细参数
```php
isDate($input, $format = 'Y-m-d')
```
* $format - 指定日期格式

对象属性
* 同上

### DateTime
检查数据是否为合法的日期时间

基本用法
```php
$input = 'abc';
if (!$widget->isDateTime($input) {
    print_r($widget->isDateTime->getMessages());
}
```

详细参数
```php
isDateTime($input, $format = 'Y-m-d H:i:s')
```
* $format - 指定日期格式

对象属性
* 同上

### Decimal
检查数据是否为小数

基本用法
```php
$input = '0.0.1';
if (!$widget->isDecimal($input)) {
    print_r($widget->isDecimal->getMessages());
}
```

详细参数
```php
isDecimal($input)
```

### Digit
检查数据是否只由数字组成

基本用法
```php
$input = 'abc123';
if (!$widget->isDigit($input)) {
    print_r($widget->isDigit->getMessages());
}
```

详细参数
```php
isDigit($input)
```

### Dir
检查数据是否为存在的目录

基本用法
```php
$input = '/notfound/directory';
if (!$widget->isDir($input)) {
    print_r($widget->isDir->getMessages());
}
```

详细参数
```php
isDir($input)
```

### DivisibleBy
检查数据是否能被指定的除数整除

基本用法
```php
$input = 10;
if (!$widget->isDivisibleBy($input, 3)) {
    print_r($widget->isDivisibleBy->getMessages());
}
```

详细参数
```php
isDivisibleBy($input, int|float $divisor = null)
```
* $divisor - 除数

### DoubleByte
检查数据是否只由双字节字符组成

基本用法
```php
$input = '中文abc';
if (!$widget->isDoubleByte($input, 3)) {
    print_r($widget->isDoubleByte->getMessages());
}
```

详细参数
```php
isDoubleByte($input)
```

### Email
检查数据是否为有效的邮箱地址

基本用法
```php
$input = 'abc';
if (!$widget->isEmail($input)) {
    print_r($widget->isEmail->getMessages());
}
```

详细参数
```php
isEmail($input)
```

### Empty
检查数据是否为空(允许空格)

基本用法
```php
$input = 'abc';
if (!$widget->isEmpty($input)) {
    print_r($widget->isEmpty->getMessages());
}
```

详细参数
```php
isEmpty($input)
```

### EndsWith
检查数据是否以指定字符串结尾

基本用法
```php
$input = 'abc';
if (!$widget->isEndsWith($input, 'd')) {
    print_r($widget->isEndsWith->getMessages());
}
```

详细参数
```php
isEndsWith($input, $findMe = null, $case = null)
```
* $findMe - 要查找的字符串,如果是数组,只要数据以数组中任何一个元素结尾就算验证通过
* $case - 是否区分大小写

### EntityExists
检查Doctrine ORM实体是否存在

基本用法
```php
$input = 'abc';
if (!$widget->isEntityExists($input, 'User', 'username')) {
    print_r($widget->isEntityExists->getMessages());
}
```

详细参数
```php
isEntityExists($input, $entityClass = null, $field = null)
```
* $entityClass - 实体的类名
* $field - 指定的字段名称,留空表示主键

### Equals
检查数据是否与指定数据相等

基本用法
```php
$input = 'abc';
if (!$widget->isEquals($input, 'bbc')) {
    print_r($widget->isEquals->getMessages());
}
```

详细参数
```php
isEquals($input, $equals = null, $strict = null)
```
* $equals - 与数据比较的值
* $strict - 是否使用全等(===)进行比较

### Exists
检查数据是否为存在的文件或目录

基本用法
```php
$input = 'abc';
if (!$widget->isExists($input)) {
    print_r($widget->isExists->getMessages());
}
```

详细参数
```php
isExists($input)
```

### File
检查数据合法的文件

基本用法
```php
$input = 'file/not/found';
if (!$widget->isFile($input)) {
    print_r($widget->isFile->getMessages());
}
```

详细参数
```php
isFile($input, array $options = array())
```
* $options 选项数组
    * maxSize - 允许的文件最大字节数
    * minSize - 允许的文件最小字节数
    * ext - 允许的文件扩展名数组
    * excludeExts - 不允许的文件扩展名数组
    * mimeType - 允许的文件媒体类型
    * excludeMimeTypes - 不允许的文件媒体类型

### IdCardCn
检查数据是否为有效的中国身份证

基本用法
```php
$input = 'abc';
if (!$widget->isIdCardCn($input)) {
    print_r($widget->isIdCardCn->getMessages());
}
```

详细参数
```php
isIdCardCn($input)
```

### IdCardHk
检查数据是否为有效的香港身份证

基本用法
```php
$input = 'abc';
if (!$widget->isIdCardHk($input)) {
    print_r($widget->isIdCardHk->getMessages());
}
```

详细参数
```php
isIdCardHk($input)
```

### IdCardMo
检查数据是否为有效的澳门身份证

基本用法
```php
$input = 'abc';
if (!$widget->isIdCardMo($input)) {
    print_r($widget->isIdCardMo->getMessages());
}
```

详细参数
```php
isIdCardMo($input)
```

### IdCardTw
检查数据是否为有效的台湾身份证

基本用法
```php
$input = 'abc';
if (!$widget->isIdCardTw($input)) {
    print_r($widget->isIdCardTw->getMessages());
}
```

详细参数
```php
isIdCardTw($input)
```

### Image
检查数据是否为有效的图片

基本用法
```php
$input = 'image/not/found';
if (!$widget->isImage($input)) {
    print_r($widget->isImage->getMessages());
}
```

详细参数
```php
isImage($input, array $options = array())
```
* $options 选项数组
    * maxWidth - 允许的图片最大宽度
    * minWidth - 允许的图片最小宽度
    * maxHeight - 允许的图片最大高度
    * minHeight - 允许的图片最小高度

### In
检查数据是否在指定的数组中

基本用法
```php
$input = 'abc';
if (!$widget->isIn($input)) {
    print_r($widget->isIn->getMessages());
}
```

详细参数
```php
isIn($input)
```

### Ip
检查数据是否为有效的IP地址

基本用法
```php
$input = 'abc';
if (!$widget->isIp($input)) {
    print_r($widget->isIp->getMessages());
}
```

详细参数
```php
isIp($input)
```

### Length
检查数据是否为指定的长度

基本用法
```php
$input = 'abc';
if (!$widget->isLength($input)) {
    print_r($widget->isLength->getMessages());
}
```

详细参数
```php
isLength($input)
```

### Lowercase
检查数据是否为小写

基本用法
```php
$input = 'abc';
if (!$widget->isLowercase($input)) {
    print_r($widget->isLowercase->getMessages());
}
```

详细参数
```php
isLowercase($input)
```

### Max
检查数据是否小于等于指定的值

基本用法
```php
$input = 'abc';
if (!$widget->isMax($input)) {
    print_r($widget->isMax->getMessages());
}
```

详细参数
```php
isMax($input)
```

### MaxLength
检查数据是否小于等于指定长度

基本用法
```php
$input = 'abc';
if (!$widget->isMaxLength($input)) {
    print_r($widget->isMaxLength->getMessages());
}
```

详细参数
```php
isMaxLength($input)
```

### Min
检查数据是否大于等于指定的值

基本用法
```php
$input = 'abc';
if (!$widget->isMin($input)) {
    print_r($widget->isMin->getMessages());
}
```

详细参数
```php
isMin($input)
```

### MinLength
检查数据是否大于等于指定长度

基本用法
```php
$input = 'abc';
if (!$widget->isMinLength($input)) {
    print_r($widget->isMinLength->getMessages());
}
```

详细参数
```php
isMinLength($input)
```

### Mobile
检查数据是否为有效的手机号码

基本用法
```php
$input = 'abc';
if (!$widget->isMobile($input)) {
    print_r($widget->isMobile->getMessages());
}
```

详细参数
```php
isMobile($input)
```

### NoneOf
-

基本用法
```php
$input = 'abc';
if (!$widget->isNoneOf($input)) {
    print_r($widget->isNoneOf->getMessages());
}
```

详细参数
```php
isNoneOf($input)
```

### Null
检查数据是否为null

基本用法
```php
$input = 'abc';
if (!$widget->isNull($input)) {
    print_r($widget->isNull->getMessages());
}
```

详细参数
```php
isNull($input)
```

### Number
检查数据是否为有效数字

基本用法
```php
$input = 'abc';
if (!$widget->isNumber($input)) {
    print_r($widget->isNumber->getMessages());
}
```

详细参数
```php
isNumber($input)
```

### OneOf
检查数据是否满足指定规则中的任何一条

基本用法
```php
$input = 'abc';
if (!$widget->isOneOf($input)) {
    print_r($widget->isOneOf->getMessages());
}
```

详细参数
```php
isOneOf($input)
```

### Phone
检查数据是否为有效的电话号码

基本用法
```php
$input = 'abc';
if (!$widget->isPhone($input)) {
    print_r($widget->isPhone->getMessages());
}
```

详细参数
```php
isPhone($input)
```

### PlateNumberCn
-

基本用法
```php
$input = 'abc';
if (!$widget->isPlateNumberCn($input)) {
    print_r($widget->isPlateNumberCn->getMessages());
}
```

详细参数
```php
isPlateNumberCn($input)
```

### Postcode
检查数据是否为有效的邮政编码

基本用法
```php
$input = 'abc';
if (!$widget->isPostcode($input)) {
    print_r($widget->isPostcode->getMessages());
}
```

详细参数
```php
isPostcode($input)
```

### QQ
检查数据是否为有效的QQ号码

基本用法
```php
$input = 'abc';
if (!$widget->isQQ($input)) {
    print_r($widget->isQQ->getMessages());
}
```

详细参数
```php
isQQ($input)
```

### Range
检查数据是否在指定的两个值之间

基本用法
```php
$input = 'abc';
if (!$widget->isRange($input)) {
    print_r($widget->isRange->getMessages());
}
```

详细参数
```php
isRange($input)
```

### Regex
检查数据是否匹配指定的正则表达式

基本用法
```php
$input = 'abc';
if (!$widget->isRegex($input)) {
    print_r($widget->isRegex->getMessages());
}
```

详细参数
```php
isRegex($input)
```

### Required
-

基本用法
```php
$input = 'abc';
if (!$widget->isRequired($input)) {
    print_r($widget->isRequired->getMessages());
}
```

详细参数
```php
isRequired($input)
```

### SomeOf
-

基本用法
```php
$input = 'abc';
if (!$widget->isSomeOf($input)) {
    print_r($widget->isSomeOf->getMessages());
}
```

详细参数
```php
isSomeOf($input)
```

### StartsWith
检查数据是否以指定字符串开头

基本用法
```php
$input = 'abc';
if (!$widget->isStartsWith($input)) {
    print_r($widget->isStartsWith->getMessages());
}
```

详细参数
```php
isStartsWith($input)
```

### Time
检查数据是否为合法的时间

基本用法
```php
$input = 'abc';
if (!$widget->isTime($input)) {
    print_r($widget->isTime->getMessages());
}
```

详细参数
```php
isTime($input)
```

### Tld
检查数据是否为顶级域名

基本用法
```php
$input = 'abc';
if (!$widget->isTld($input)) {
    print_r($widget->isTld->getMessages());
}
```

详细参数
```php
isTld($input)
```

### Type
检查数据是否为指定的类型

基本用法
```php
$input = 'abc';
if (!$widget->isType($input)) {
    print_r($widget->isType->getMessages());
}
```

详细参数
```php
isType($input)
```

### Uppercase
检查数据是否为大写

基本用法
```php
$input = 'abc';
if (!$widget->isUppercase($input)) {
    print_r($widget->isUppercase->getMessages());
}
```

详细参数
```php
isUppercase($input)
```

### Url
检查数据是否为有效的URL地址

基本用法
```php
$input = 'abc';
if (!$widget->isUrl($input)) {
    print_r($widget->isUrl->getMessages());
}
```

详细参数
```php
isUrl($input)
```

### Uuid
检查数据是否为有效的UUID

基本用法
```php
$input = 'abc';
if (!$widget->isUuid($input)) {
    print_r($widget->isUuid->getMessages());
}
```

详细参数
```php
isUuid($input)
```
