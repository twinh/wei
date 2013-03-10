[isEmail()](http://twinh.github.com/widget/api/isEmail)
=======================================================

检查数据是否为有效的邮箱地址

### 
```php
bool isEmail( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"example@example.com"是否为邮箱地址
```php
<?php

if ($widget->isEmail('example@example.com')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
