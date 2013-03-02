验证器
=====
为了保证数据的安全合法,Widget提供了一个简洁高效的验证器,用于检查数据的正确性.目前包括以下这些验证器:
* [Alnum](#alnum) - 检查数据是否只由字母(a-z)和数字(0-9)组成
* [Alpha](#alpha) - 检查数据是否只由字母(a-z)组成
* [Blank](#blank) - 检查数据是否为空
* [Callback](#callback) - 检查数据是否通过指定回调方法验证
* [Chinese](#chinese) - 检查数据是否只由中文组成
* [CreditCard](#creditcard) - 检查数据是否为合法的信用卡号码
* [Date](#date) - 检查数据是否为合法的日期
* [DateTime](#datetime) - 检查数据是否为合法的日期时间
* [Decimal](#decimal) - 检查数据是否为小数
* [Digit](#digit) - 检查数据是否只由数字组成
* [Dir](#dir)－ 检查数据是否为存在的目录
* [DoubleByte](#doublebyte) - 检查数据是否只由双字节字符组成
* [Email](#email) - 检查数据是否为有效的邮箱地址
* [Empty](#empty) - 检查数据是否为空
* [EndsWith](#endswith) - 检查数据是否以指定字符串结尾
* [EntityExists](#entityexists) - 检查Doctrine ORM实体是否存在
* [Equals](#equals) - 检查数据是否与指定数据相等
* [Exists](#exists) - 检查数据是否为存在的文件或目录
* [File](#file) - 检查数据合法的文件
* [IdCardCn](#idcardcn) - 检查数据是否为有效的中国身份证
* [IdCardHk](#idcardhk) - 检查数据是否为有效的香港身份证
* [Image](#image) - 检查数据是否为有效的图片
* [In](#in) - 检查数据是否在指定的数组中
* [Ip](#ip) - 检查数据是否为有效的IP地址
* [Length](#length) - 检查数据是否为指定的长度
* [Lowercase](#lowercase) - 检查数据是否为小写
* [Max](#max) - 检查数据是否小于等于指定的值
* [MaxLength](#maxlength) - 检查数据是否小于等于指定长度
* [Min](#min) - 检查数据是否大于等于指定的值
* [MinLength](#minlength) - 检查数据是否大于等于指定长度
* [Mobile](#mobile) - 检查数据是否为有效的手机号码
* [Null](#null) - 检查数据是否为null
* [Number](#number) - 检查数据是否为有效数字
* [OneOf](#oneof) - 检查数据是否满足指定规则中的任何一条
* [Phone](#phone) - 检查数据是否为有效的电话号码
* [Postcode](#postcode) - 检查数据是否为有效的邮政编码
* [QQ](#qq) - 检查数据是否为有效的QQ号码
* [Range](#range) - 检查数据是否在指定的两个值之间
* [Regex](#regex) - 检查数据是否匹配指定的正则表达式
* [Require](#require) - 检查数据是否为空
* [StartsWith](#startswith) - 检查数据是否以指定字符串开头
* [Time](#time) - 检查数据是否为合法的时间
* [Tld](#tld) - 检查数据是否为顶级域名
* [Type](#type) - 检查数据是否为指定的类型
* [Uppercase](#uppercase) - 检查数据是否为小写
* [Url](#url) - 检查数据是否为有效的URL地址
* [Uuid](#uuid) - 检查数据是否为有效的UUID


### Alnum 
检查数据是否只由字母(a-z)和数字(0-9)组成

基本用法
```php
$input = '[a]';
if (!$widget->isAlnum($input)) {
    print_r($widget->isAlnum->getMessages());
}
```

可选参数
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

可选参数
* pattern - 指定校验的正则表达式

### Blank
检查数据是否为空

基本用法
```php
$input = '123';
if (!$widget->isBlank($input)) {
    print_r($widget->isBlank->getMessages());
}
```

可选参数
* 无

### Chinese
检查数据是否只由中文组成

基本用法
```php
$input = '123';
if (!$widget->isChinese($input)) {
    print_r($widget->isChinese->getMessages());
}
```
可选参数

* 无

### CreditCard
检查数据是否为合法的信用卡号码

允许指定的信用卡类型有: American Express, Diners Club, Discover, JCB, MasterCard, China UnionPay 和 Visa

基本用法
```php
$input = '4111111111111111'; // Visa
if (!$widget->isCreditCard($input, 'UnionPay')) {
    print_r($widget->isChinese->getMessages());
}
```
#### 可选参数
* type - 指定信用卡类型,多个可以为数组,或是以逗号`,`隔开,使用`all`或留空表示允许任意类型的卡号

分组验证器
---------
###All - 检查集合里的每一项是否符合指定的规则###

###AllOf - 检查输入数据是否通过所有的规则校验###
