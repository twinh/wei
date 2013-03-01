验证器
=====
为了保证数据的安全合法,Widget提供了一个简洁高效的验证器,用于检查数据的正确性.

### Alnum - 检查数据是否只由字母(a-z)和数字(0-9)组成
##### 基本用法
```php
$input = '[a]';
if (!$widget->isAlnum($input)) {
    print_r($widget->isAlnum->getMessages());
}
```
##### 可选参数
* pattern - 指定校验的正则表达式

### Alpha - 检查数据是否只由字母(a-z)组成
#### 基本用法
```php
$input = '123';
if (!$widget->isAlpha($input)) {
    print_r($widget->isAlnum->getMessages());
}
```
#### 可选参数
* pattern - 指定校验的正则表达式

### Blank - 检查数据是否为空
#### 基本用法
```php
$input = '123';
if (!$widget->isBlank($input)) {
    print_r($widget->isBlank->getMessages());
}
```
#### 可选参数
* 无

### Chinese - 检查数据是否只由中文组成
#### 基本用法
```php
$input = '123';
if (!$widget->isChinese($input)) {
    print_r($widget->isChinese->getMessages());
}
```
#### 可选参数
* 无

### CreditCard - 检查数据是否为合法的信用卡号码
允许指定的信用卡类型有: American Express, Diners Club, Discover, JCB, MasterCard, China UnionPay 和 Visa
#### 基本用法
```php
$input = '4111111111111111';
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
