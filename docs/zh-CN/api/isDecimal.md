[isDecimal()](http://twinh.github.com/widget/api/isDecimal)
===========================================================

检查数据是否为小数

### 
```php
bool isDecimal( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"0.0.1"是否为小数
```php
<?php

if ($widget->isDecimal('1.0.0')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'No'
```
