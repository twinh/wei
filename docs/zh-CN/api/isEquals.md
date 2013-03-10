[isEquals()](http://twinh.github.com/widget/api/isEquals)
=========================================================

检查数据是否与指定数据相等

### 
```php
bool isEquals( $input [, $equals [, $strict ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$equals** `mixed` 与数据比较的值
* **$strict** `bool` 是否使用全等(===)进行比较,默认使用等于(==)比较

##### 范例
检查"10:00:00"是否为合法的时间
```php
<?php

$post = array(
    'password' => '123456',
    'password_confirmation' => '123456',
);

if ($widget->isEquals($post['password'], $post['password_confirmation'])) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
