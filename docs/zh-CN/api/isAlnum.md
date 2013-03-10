[isAlnum()](http://twinh.github.com/widget/api/isAlnum)
=======================================================

检查数据是否只由字母(a-z)和数字(0-9)组成

### 
```php
bool isAlnum( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查数据是否只由字母和数字组成
```php
<?php

$input = 'abc123';
if ($widget->isAlnum($input)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
