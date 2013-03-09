[isDigit()](http://twinh.github.com/widget/api/isDigit)
=======================================================

检查数据是否只由数字组成

### 
```php
bool isDigit( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"abc123"是否为数字
```php
<?php

if ($widget->isDigit('abc123')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'No'
```
