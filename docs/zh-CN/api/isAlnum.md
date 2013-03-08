[isAlnum()](http://twinh.github.com/widget/api/isAlnum)
=======================================================

检查数据是否只由字母(a-z)和数字(0-9)组成

##### 目录
* isAll( $input )

### 检查数据是否只由字母(a-z)和数字(0-9)组成
```php
bool isAll( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
```php
<?php

$input = 'abc123';
if ($widget->isAlnum($input)) {
    echo 'success';
} else {
    echo 'failure';
}
```
##### 输出
```php
'success'
```
