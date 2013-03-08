[is()](http://twinh.github.com/widget/api/is)
=============================================

检查数据是否能通过指定规则,或指定回调结构的验证

##### 目录
* is($rule, $input, $options)
* is($fn, $input)
* is($rules, $input)

### 检查数据是否能通过指定规则的验证
```php
bool is($rule, $input, $options)
```

##### 参数
* **$rule** `string` 验证器的名称
* **$input** `mixed` 待验证的数据
* **$options** `array` 验证器的配置选项

##### 范例
```php
<?php
 
if ($widget->is('email', 'twinhuang@qq.com')) {
    echo 'twihuang@qq.com is valid email';
} else {
    echo 'twihuang@qq.com is not valid email';
}
```
##### 输出
```php
'twihuang@qq.com is valid email'
```
- - - -

### 检查数据是否能通过指定回调结构的验证
```php
bool is($fn, $input)
```

##### 参数
* **$fn** `callback` 用于验证数据的回调结构
* **$input** `mixed` 待验证的数据


回调结构`$fn`包含三个参数,分别是
* $input `mixed` 待验证的数据
* $callback `\Widget\Validator\Callback` 回调验证器对象
* $widget `\Widget\Widget` 微件管理器

##### 范例
```php
<?php

if ($widget->is(function($input) {
    return 0 === 10 % $input;
}, 3)) {
    echo 'success';
} else {
    echo 'failure';
}
```
##### 输出
```php
'failure'
```
- - - -

### 检查数据是否能通过指定的验证规则数组验证
```php
bool is($rules, $input)
```

##### 参数
* **$rules** `array` 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项
* **$input** `mixed` 待验证的数据

##### 范例
```php
<?php

$rules = array(
    'digit' => true,
    'length' => array(3, 5)
);
if ($widget->is($rules, '123456')) {
    echo 'success';
} else {
    echo 'failure';
}
```
##### 输出
```php
'failure'
```
