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


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notEquals`           | %name%必须等于%equals%                                         |
| `negative`            | %name%不能等于%equals%                                         |


##### 代码范例
检查指定的两个值是否相等
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
##### 运行结果
```php
'Yes'
```
