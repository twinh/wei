[isEmail()](http://twinh.github.io/widget/api/isEmail)
======================================================

检查数据是否为有效的邮箱地址

### 
```php
bool isEmail( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `format`              | %name%必须是有效的邮箱地址                                     |
| `negative`            | %name%不能是邮箱地址                                           |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"example@example.com"是否为邮箱地址
```php
<?php

if ($widget->isEmail('example@example.com')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
